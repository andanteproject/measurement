<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Pressure\Imperial\InchOfMercury;
use Andante\Measurement\Quantity\Pressure\Imperial\InchOfWater;
use Andante\Measurement\Quantity\Pressure\Imperial\PoundPerSquareFoot;
use Andante\Measurement\Quantity\Pressure\Imperial\PoundPerSquareInch;
use Andante\Measurement\Quantity\Pressure\ImperialPressure;
use Andante\Measurement\Quantity\Pressure\Pressure;
use Andante\Measurement\Quantity\Pressure\SI\Atmosphere;
use Andante\Measurement\Quantity\Pressure\SI\Bar;
use Andante\Measurement\Quantity\Pressure\SI\Gigapascal;
use Andante\Measurement\Quantity\Pressure\SI\Hectopascal;
use Andante\Measurement\Quantity\Pressure\SI\Kilopascal;
use Andante\Measurement\Quantity\Pressure\SI\Megapascal;
use Andante\Measurement\Quantity\Pressure\SI\Millibar;
use Andante\Measurement\Quantity\Pressure\SI\Pascal;
use Andante\Measurement\Quantity\Pressure\SI\Torr;
use Andante\Measurement\Quantity\Pressure\SIPressure;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Pressure\ImperialPressureUnit;
use Andante\Measurement\Unit\Pressure\SIPressureUnit;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Provides default configuration for Pressure quantities.
 *
 * Registers all SI and imperial pressure units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to pascal)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to pascal):
 * - 1 Pa = 1 Pa (base)
 * - 1 hPa = 100 Pa
 * - 1 kPa = 1000 Pa
 * - 1 MPa = 1,000,000 Pa
 * - 1 GPa = 1,000,000,000 Pa
 * - 1 bar = 100,000 Pa
 * - 1 mbar = 100 Pa
 * - 1 atm = 101,325 Pa (exact by definition)
 * - 1 Torr = 133.32236842105263 Pa (1/760 atm)
 * - 1 psi = 6894.757293168361 Pa
 * - 1 psf = 47.88025898033584 Pa
 * - 1 inHg = 3386.389 Pa
 * - 1 inH₂O = 249.08891 Pa
 */
final class PressureProvider implements QuantityDefaultConfigProviderInterface
{
    /**
     * Bar to pascal: 1 bar = 100,000 Pa.
     */
    private const BAR_TO_PASCAL = '100000';

    /**
     * Standard atmosphere to pascal: 1 atm = 101,325 Pa (exact).
     */
    private const ATMOSPHERE_TO_PASCAL = '101325';

    /**
     * Torr to pascal: 1 Torr = 1/760 atm = 101325/760 Pa.
     */
    private const TORR_TO_PASCAL = '133.32236842105263';

    /**
     * PSI to pascal: 1 psi = 6894.757293168361 Pa.
     * Calculated as: lbf/in² = (4.4482216152605 N) / (0.0254 m)² = 6894.757...
     */
    private const PSI_TO_PASCAL = '6894.757293168361';

    /**
     * PSF to pascal: 1 psf = 1/144 psi.
     */
    private const PSF_TO_PASCAL = '47.88025898033584';

    /**
     * Inch of mercury to pascal: 1 inHg = 3386.389 Pa (at 0°C).
     */
    private const INCH_MERCURY_TO_PASCAL = '3386.389';

    /**
     * Inch of water to pascal: 1 inH₂O = 249.08891 Pa (at 4°C).
     */
    private const INCH_WATER_TO_PASCAL = '249.08891';

    private static ?self $instance = null;

    private function __construct()
    {
    }

    public static function global(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * Reset the global instance (for testing).
     *
     * @internal
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    /**
     * Centralized SI pressure unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to pascal)].
     *
     * @return array<array{SIPressureUnit, class-string, numeric-string}>
     */
    private function getSIUnits(): array
    {
        return [
            [SIPressureUnit::Pascal, Pascal::class, '1'],
            [SIPressureUnit::Hectopascal, Hectopascal::class, '100'],
            [SIPressureUnit::Kilopascal, Kilopascal::class, '1000'],
            [SIPressureUnit::Megapascal, Megapascal::class, '1000000'],
            [SIPressureUnit::Gigapascal, Gigapascal::class, '1000000000'],
            [SIPressureUnit::Bar, Bar::class, self::BAR_TO_PASCAL],
            [SIPressureUnit::Millibar, Millibar::class, '100'],
            [SIPressureUnit::Atmosphere, Atmosphere::class, self::ATMOSPHERE_TO_PASCAL],
            [SIPressureUnit::Torr, Torr::class, self::TORR_TO_PASCAL],
        ];
    }

    /**
     * Centralized imperial pressure unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to pascal)].
     *
     * @return array<array{ImperialPressureUnit, class-string, numeric-string}>
     */
    private function getImperialUnits(): array
    {
        return [
            [ImperialPressureUnit::PoundPerSquareInch, PoundPerSquareInch::class, self::PSI_TO_PASCAL],
            [ImperialPressureUnit::PoundPerSquareFoot, PoundPerSquareFoot::class, self::PSF_TO_PASCAL],
            [ImperialPressureUnit::InchOfMercury, InchOfMercury::class, self::INCH_MERCURY_TO_PASCAL],
            [ImperialPressureUnit::InchOfWater, InchOfWater::class, self::INCH_WATER_TO_PASCAL],
        ];
    }

    public function registerUnits(UnitRegistry $registry): void
    {
        foreach ($this->getSIUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
    }

    public function registerConversionFactors(ConversionFactorRegistry $registry): void
    {
        foreach ($this->getSIUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
    }

    public function registerResultMappings(ResultQuantityRegistry $registry): void
    {
        $formula = new DimensionalFormula(length: -1, mass: 1, time: -2);

        // Unit-specific classes → mid-level class (preserves system)
        foreach ($this->getSIUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, SIPressure::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ImperialPressure::class);
        }

        // Mid-level classes → themselves
        $registry->register(SIPressure::class, $formula, SIPressure::class);
        $registry->register(ImperialPressure::class, $formula, ImperialPressure::class);

        // Generic
        $registry->register(Pressure::class, $formula, Pressure::class);
        $registry->registerGeneric($formula, Pressure::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        $formula = new DimensionalFormula(length: -1, mass: 1, time: -2);

        // Default unit for pressure dimension (SI: pascal)
        $registry->register($formula, SIPressureUnit::Pascal);

        // Imperial system default
        $registry->registerForSystem($formula, UnitSystem::Imperial, ImperialPressureUnit::PoundPerSquareInch);
    }
}
