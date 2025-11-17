<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Acceleration\Acceleration;
use Andante\Measurement\Quantity\Acceleration\Imperial\FootPerSecondSquared;
use Andante\Measurement\Quantity\Acceleration\Imperial\InchPerSecondSquared;
use Andante\Measurement\Quantity\Acceleration\ImperialAcceleration;
use Andante\Measurement\Quantity\Acceleration\Metric\CentimeterPerSecondSquared;
use Andante\Measurement\Quantity\Acceleration\Metric\Gal;
use Andante\Measurement\Quantity\Acceleration\Metric\MeterPerSecondSquared;
use Andante\Measurement\Quantity\Acceleration\Metric\MillimeterPerSecondSquared;
use Andante\Measurement\Quantity\Acceleration\Metric\StandardGravity;
use Andante\Measurement\Quantity\Acceleration\MetricAcceleration;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Acceleration\ImperialAccelerationUnit;
use Andante\Measurement\Unit\Acceleration\MetricAccelerationUnit;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Provides default configuration for Acceleration quantities.
 *
 * Registers all metric and imperial acceleration units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to m/s²)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to m/s²):
 * - 1 m/s² = 1 m/s² (base)
 * - 1 cm/s² = 0.01 m/s²
 * - 1 mm/s² = 0.001 m/s²
 * - 1 Gal = 0.01 m/s² (same as cm/s²)
 * - 1 g = 9.80665 m/s² (standard gravity, exact)
 * - 1 ft/s² = 0.3048 m/s²
 * - 1 in/s² = 0.0254 m/s²
 */
final class AccelerationProvider implements QuantityDefaultConfigProviderInterface
{
    /**
     * Standard gravity: g = 9.80665 m/s² (exact by definition).
     */
    private const STANDARD_GRAVITY_TO_METER_PER_SECOND_SQUARED = '9.80665';

    /**
     * ft/s² to m/s²: 0.3048 m/s² (exact, 1 foot = 0.3048 meters).
     */
    private const FOOT_PER_SECOND_SQUARED_TO_METER_PER_SECOND_SQUARED = '0.3048';

    /**
     * in/s² to m/s²: 0.0254 m/s² (exact, 1 inch = 0.0254 meters).
     */
    private const INCH_PER_SECOND_SQUARED_TO_METER_PER_SECOND_SQUARED = '0.0254';

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
     * Centralized metric acceleration unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to m/s²)].
     *
     * @return array<array{MetricAccelerationUnit, class-string, numeric-string}>
     */
    private function getMetricUnits(): array
    {
        return [
            [MetricAccelerationUnit::MeterPerSecondSquared, MeterPerSecondSquared::class, '1'],
            [MetricAccelerationUnit::CentimeterPerSecondSquared, CentimeterPerSecondSquared::class, '0.01'],
            [MetricAccelerationUnit::MillimeterPerSecondSquared, MillimeterPerSecondSquared::class, '0.001'],
            [MetricAccelerationUnit::Gal, Gal::class, '0.01'],
            [MetricAccelerationUnit::StandardGravity, StandardGravity::class, self::STANDARD_GRAVITY_TO_METER_PER_SECOND_SQUARED],
        ];
    }

    /**
     * Centralized imperial acceleration unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to m/s²)].
     *
     * @return array<array{ImperialAccelerationUnit, class-string, numeric-string}>
     */
    private function getImperialUnits(): array
    {
        return [
            [ImperialAccelerationUnit::FootPerSecondSquared, FootPerSecondSquared::class, self::FOOT_PER_SECOND_SQUARED_TO_METER_PER_SECOND_SQUARED],
            [ImperialAccelerationUnit::InchPerSecondSquared, InchPerSecondSquared::class, self::INCH_PER_SECOND_SQUARED_TO_METER_PER_SECOND_SQUARED],
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
        $formula = new DimensionalFormula(length: 1, time: -2);

        // Unit-specific classes → mid-level class (preserves system)
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, MetricAcceleration::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ImperialAcceleration::class);
        }

        // Mid-level classes → themselves
        $registry->register(MetricAcceleration::class, $formula, MetricAcceleration::class);
        $registry->register(ImperialAcceleration::class, $formula, ImperialAcceleration::class);

        // Generic
        $registry->register(Acceleration::class, $formula, Acceleration::class);
        $registry->registerGeneric($formula, Acceleration::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        $formula = new DimensionalFormula(length: 1, time: -2);

        // Default unit for acceleration dimension (SI: m/s²)
        $registry->register($formula, MetricAccelerationUnit::MeterPerSecondSquared);

        // Imperial system default
        $registry->registerForSystem($formula, UnitSystem::Imperial, ImperialAccelerationUnit::FootPerSecondSquared);
    }
}
