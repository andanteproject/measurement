<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Force\Force;
use Andante\Measurement\Quantity\Force\Imperial\Kip;
use Andante\Measurement\Quantity\Force\Imperial\OunceForce;
use Andante\Measurement\Quantity\Force\Imperial\Poundal;
use Andante\Measurement\Quantity\Force\Imperial\PoundForce;
use Andante\Measurement\Quantity\Force\ImperialForce;
use Andante\Measurement\Quantity\Force\SI\Dyne;
use Andante\Measurement\Quantity\Force\SI\Kilonewton;
use Andante\Measurement\Quantity\Force\SI\Meganewton;
use Andante\Measurement\Quantity\Force\SI\Micronewton;
use Andante\Measurement\Quantity\Force\SI\Millinewton;
use Andante\Measurement\Quantity\Force\SI\Newton;
use Andante\Measurement\Quantity\Force\SIForce;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Force\ImperialForceUnit;
use Andante\Measurement\Unit\Force\SIForceUnit;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Provides default configuration for Force quantities.
 *
 * Registers all SI and imperial force units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to newton)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to newton):
 * - 1 N = 1 N (base)
 * - 1 kN = 1000 N
 * - 1 MN = 1,000,000 N
 * - 1 mN = 0.001 N
 * - 1 μN = 0.000001 N
 * - 1 dyn = 0.00001 N (10⁻⁵ N)
 * - 1 lbf = 4.4482216152605 N
 * - 1 ozf = 0.27801385095378 N
 * - 1 kip = 4448.2216152605 N
 * - 1 pdl = 0.138254954376 N
 */
final class ForceProvider implements QuantityDefaultConfigProviderInterface
{
    /**
     * Dyne to newton: 1 dyn = 10⁻⁵ N.
     */
    private const DYNE_TO_NEWTON = '0.00001';

    /**
     * Pound-force to newton: 1 lbf = g₀ × 1 lb = 9.80665 × 0.45359237 N.
     */
    private const POUND_FORCE_TO_NEWTON = '4.4482216152605';

    /**
     * Ounce-force to newton: 1 ozf = 1/16 lbf.
     */
    private const OUNCE_FORCE_TO_NEWTON = '0.27801385095378';

    /**
     * Kip to newton: 1 kip = 1000 lbf.
     */
    private const KIP_TO_NEWTON = '4448.2216152605';

    /**
     * Poundal to newton: 1 pdl = 1 lb⋅ft/s².
     */
    private const POUNDAL_TO_NEWTON = '0.138254954376';

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
     * Centralized SI force unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to newton)].
     *
     * @return array<array{SIForceUnit, class-string, numeric-string}>
     */
    private function getSIUnits(): array
    {
        return [
            [SIForceUnit::Newton, Newton::class, '1'],
            [SIForceUnit::Kilonewton, Kilonewton::class, '1000'],
            [SIForceUnit::Meganewton, Meganewton::class, '1000000'],
            [SIForceUnit::Millinewton, Millinewton::class, '0.001'],
            [SIForceUnit::Micronewton, Micronewton::class, '0.000001'],
            [SIForceUnit::Dyne, Dyne::class, self::DYNE_TO_NEWTON],
        ];
    }

    /**
     * Centralized imperial force unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to newton)].
     *
     * @return array<array{ImperialForceUnit, class-string, numeric-string}>
     */
    private function getImperialUnits(): array
    {
        return [
            [ImperialForceUnit::PoundForce, PoundForce::class, self::POUND_FORCE_TO_NEWTON],
            [ImperialForceUnit::OunceForce, OunceForce::class, self::OUNCE_FORCE_TO_NEWTON],
            [ImperialForceUnit::Kip, Kip::class, self::KIP_TO_NEWTON],
            [ImperialForceUnit::Poundal, Poundal::class, self::POUNDAL_TO_NEWTON],
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
        $formula = new DimensionalFormula(length: 1, mass: 1, time: -2);

        // Unit-specific classes → mid-level class (preserves system)
        foreach ($this->getSIUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, SIForce::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ImperialForce::class);
        }

        // Mid-level classes → themselves
        $registry->register(SIForce::class, $formula, SIForce::class);
        $registry->register(ImperialForce::class, $formula, ImperialForce::class);

        // Generic
        $registry->register(Force::class, $formula, Force::class);
        $registry->registerGeneric($formula, Force::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        $formula = new DimensionalFormula(length: 1, mass: 1, time: -2);

        // Default unit for force dimension (SI: newton)
        $registry->register($formula, SIForceUnit::Newton);

        // Imperial system default
        $registry->registerForSystem($formula, UnitSystem::Imperial, ImperialForceUnit::PoundForce);
    }
}
