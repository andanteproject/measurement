<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\CalorificValue\CalorificValue;
use Andante\Measurement\Quantity\CalorificValue\Imperial\BTUPerCubicFoot;
use Andante\Measurement\Quantity\CalorificValue\Imperial\ThermPerCubicFoot;
use Andante\Measurement\Quantity\CalorificValue\ImperialCalorificValue;
use Andante\Measurement\Quantity\CalorificValue\Metric\GigajoulePerCubicMeter;
use Andante\Measurement\Quantity\CalorificValue\Metric\JoulePerCubicMeter;
use Andante\Measurement\Quantity\CalorificValue\Metric\KilojoulePerCubicMeter;
use Andante\Measurement\Quantity\CalorificValue\Metric\MegajoulePerCubicMeter;
use Andante\Measurement\Quantity\CalorificValue\MetricCalorificValue;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\CalorificValue\ImperialCalorificValueUnit;
use Andante\Measurement\Unit\CalorificValue\MetricCalorificValueUnit;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Provides default configuration for CalorificValue quantities.
 *
 * Registers all metric and imperial calorific value units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to J/m³)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors:
 * - Base unit: J/m³ (Joule per cubic meter)
 * - 1 kJ/m³ = 1,000 J/m³
 * - 1 MJ/m³ = 1,000,000 J/m³
 * - 1 GJ/m³ = 1,000,000,000 J/m³
 * - 1 BTU/ft³ = 1055.06 J / 0.0283168 m³ ≈ 37,258.946 J/m³
 * - 1 therm/ft³ = 100,000 BTU/ft³ ≈ 3,725,894,600 J/m³
 */
final class CalorificValueProvider implements QuantityDefaultConfigProviderInterface
{
    /**
     * BTU/ft³ to J/m³ = BTU_TO_JOULE / CUBIC_FOOT_TO_CUBIC_METER
     * ≈ 37258.9458117 J/m³ per BTU/ft³.
     */
    private const BTU_PER_CUBIC_FOOT_TO_JOULE_PER_CUBIC_METER = '37258.9458117';

    /**
     * Therm/ft³ to J/m³ = 100,000 * BTU/ft³
     * ≈ 3,725,894,581.17 J/m³ per therm/ft³.
     */
    private const THERM_PER_CUBIC_FOOT_TO_JOULE_PER_CUBIC_METER = '3725894581.17';

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
     * Centralized metric calorific value unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to J/m³)].
     *
     * @return array<array{MetricCalorificValueUnit, class-string, numeric-string}>
     */
    private function getMetricUnits(): array
    {
        return [
            [MetricCalorificValueUnit::JoulePerCubicMeter, JoulePerCubicMeter::class, '1'],
            [MetricCalorificValueUnit::KilojoulePerCubicMeter, KilojoulePerCubicMeter::class, '1000'],
            [MetricCalorificValueUnit::MegajoulePerCubicMeter, MegajoulePerCubicMeter::class, '1000000'],
            [MetricCalorificValueUnit::GigajoulePerCubicMeter, GigajoulePerCubicMeter::class, '1000000000'],
        ];
    }

    /**
     * Centralized imperial calorific value unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to J/m³)].
     *
     * @return array<array{ImperialCalorificValueUnit, class-string, numeric-string}>
     */
    private function getImperialUnits(): array
    {
        return [
            [ImperialCalorificValueUnit::BTUPerCubicFoot, BTUPerCubicFoot::class, self::BTU_PER_CUBIC_FOOT_TO_JOULE_PER_CUBIC_METER],
            [ImperialCalorificValueUnit::ThermPerCubicFoot, ThermPerCubicFoot::class, self::THERM_PER_CUBIC_FOOT_TO_JOULE_PER_CUBIC_METER],
        ];
    }

    public function registerUnits(UnitRegistry $registry): void
    {
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
    }

    public function registerConversionFactors(ConversionFactorRegistry $registry): void
    {
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
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
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, MetricCalorificValue::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ImperialCalorificValue::class);
        }

        // Mid-level classes → themselves
        $registry->register(MetricCalorificValue::class, $formula, MetricCalorificValue::class);
        $registry->register(ImperialCalorificValue::class, $formula, ImperialCalorificValue::class);

        // Generic
        $registry->register(CalorificValue::class, $formula, CalorificValue::class);
        $registry->registerGeneric($formula, CalorificValue::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        $formula = new DimensionalFormula(length: -1, mass: 1, time: -2);

        // Default unit for calorific value dimension
        $registry->register($formula, MetricCalorificValueUnit::JoulePerCubicMeter);

        // Imperial system default
        $registry->registerForSystem($formula, UnitSystem::Imperial, ImperialCalorificValueUnit::BTUPerCubicFoot);
    }
}
