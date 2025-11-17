<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Density\Density;
use Andante\Measurement\Quantity\Density\Imperial\OuncePerCubicInch;
use Andante\Measurement\Quantity\Density\Imperial\PoundPerCubicFoot;
use Andante\Measurement\Quantity\Density\Imperial\PoundPerCubicInch;
use Andante\Measurement\Quantity\Density\Imperial\PoundPerGallon;
use Andante\Measurement\Quantity\Density\Imperial\SlugPerCubicFoot;
use Andante\Measurement\Quantity\Density\ImperialDensity;
use Andante\Measurement\Quantity\Density\SI\GramPerCubicCentimeter;
use Andante\Measurement\Quantity\Density\SI\GramPerCubicMeter;
use Andante\Measurement\Quantity\Density\SI\GramPerLiter;
use Andante\Measurement\Quantity\Density\SI\KilogramPerCubicMeter;
use Andante\Measurement\Quantity\Density\SI\KilogramPerLiter;
use Andante\Measurement\Quantity\Density\SI\MilligramPerCubicMeter;
use Andante\Measurement\Quantity\Density\SI\TonnePerCubicMeter;
use Andante\Measurement\Quantity\Density\SIDensity;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Density\ImperialDensityUnit;
use Andante\Measurement\Unit\Density\SIDensityUnit;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Provides default configuration for Density quantities.
 *
 * Registers all SI and imperial density units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to kg/m³)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to kg/m³):
 * - 1 kg/m³ = 1 kg/m³ (base)
 * - 1 g/m³ = 0.001 kg/m³
 * - 1 g/cm³ = 1000 kg/m³
 * - 1 g/L = 1 kg/m³
 * - 1 kg/L = 1000 kg/m³
 * - 1 mg/m³ = 0.000001 kg/m³
 * - 1 t/m³ = 1000 kg/m³
 * - 1 lb/ft³ = 16.018463 kg/m³
 * - 1 lb/in³ = 27,679.9 kg/m³
 * - 1 lb/gal (US) = 119.826 kg/m³
 * - 1 oz/in³ = 1,729.99 kg/m³
 * - 1 slug/ft³ = 515.379 kg/m³
 */
final class DensityProvider implements QuantityDefaultConfigProviderInterface
{
    /**
     * Pound per cubic foot to kg/m³.
     * 1 lb/ft³ = 16.018463373960138 kg/m³.
     */
    private const LB_PER_FT3_TO_KG_PER_M3 = '16.018463373960138';

    /**
     * Pound per cubic inch to kg/m³.
     * 1 lb/in³ = 27,679.9047102031 kg/m³.
     * (1 lb = 0.45359237 kg, 1 in³ = 1.6387064e-5 m³).
     */
    private const LB_PER_IN3_TO_KG_PER_M3 = '27679.9047102031';

    /**
     * Pound per US gallon to kg/m³.
     * 1 lb/gal (US) = 119.826427316897 kg/m³.
     * (1 US gal = 3.785411784 L).
     */
    private const LB_PER_GAL_TO_KG_PER_M3 = '119.826427316897';

    /**
     * Ounce per cubic inch to kg/m³.
     * 1 oz/in³ = 1,729.994044 kg/m³.
     * (1 oz = 1/16 lb).
     */
    private const OZ_PER_IN3_TO_KG_PER_M3 = '1729.9940294376944';

    /**
     * Slug per cubic foot to kg/m³.
     * 1 slug/ft³ = 515.3788184 kg/m³.
     * (1 slug = 14.593903 kg).
     */
    private const SLUG_PER_FT3_TO_KG_PER_M3 = '515.3788183931866';

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
     * Centralized SI density unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to kg/m³)].
     *
     * @return array<array{SIDensityUnit, class-string, numeric-string}>
     */
    private function getSIUnits(): array
    {
        return [
            [SIDensityUnit::KilogramPerCubicMeter, KilogramPerCubicMeter::class, '1'],
            [SIDensityUnit::GramPerCubicMeter, GramPerCubicMeter::class, '0.001'],
            [SIDensityUnit::GramPerCubicCentimeter, GramPerCubicCentimeter::class, '1000'],
            [SIDensityUnit::GramPerLiter, GramPerLiter::class, '1'],
            [SIDensityUnit::KilogramPerLiter, KilogramPerLiter::class, '1000'],
            [SIDensityUnit::MilligramPerCubicMeter, MilligramPerCubicMeter::class, '0.000001'],
            [SIDensityUnit::TonnePerCubicMeter, TonnePerCubicMeter::class, '1000'],
        ];
    }

    /**
     * Centralized imperial density unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to kg/m³)].
     *
     * @return array<array{ImperialDensityUnit, class-string, numeric-string}>
     */
    private function getImperialUnits(): array
    {
        return [
            [ImperialDensityUnit::PoundPerCubicFoot, PoundPerCubicFoot::class, self::LB_PER_FT3_TO_KG_PER_M3],
            [ImperialDensityUnit::PoundPerCubicInch, PoundPerCubicInch::class, self::LB_PER_IN3_TO_KG_PER_M3],
            [ImperialDensityUnit::PoundPerGallon, PoundPerGallon::class, self::LB_PER_GAL_TO_KG_PER_M3],
            [ImperialDensityUnit::OuncePerCubicInch, OuncePerCubicInch::class, self::OZ_PER_IN3_TO_KG_PER_M3],
            [ImperialDensityUnit::SlugPerCubicFoot, SlugPerCubicFoot::class, self::SLUG_PER_FT3_TO_KG_PER_M3],
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
        $formula = new DimensionalFormula(length: -3, mass: 1);

        // Unit-specific classes → mid-level class (preserves system)
        foreach ($this->getSIUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, SIDensity::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ImperialDensity::class);
        }

        // Mid-level classes → themselves
        $registry->register(SIDensity::class, $formula, SIDensity::class);
        $registry->register(ImperialDensity::class, $formula, ImperialDensity::class);

        // Generic
        $registry->register(Density::class, $formula, Density::class);
        $registry->registerGeneric($formula, Density::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        $formula = new DimensionalFormula(length: -3, mass: 1);

        // Default unit for density dimension (SI: kg/m³)
        $registry->register($formula, SIDensityUnit::KilogramPerCubicMeter);

        // Imperial system default
        $registry->registerForSystem($formula, UnitSystem::Imperial, ImperialDensityUnit::PoundPerCubicFoot);
    }
}
