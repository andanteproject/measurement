<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Velocity\Imperial\FootPerSecond;
use Andante\Measurement\Quantity\Velocity\Imperial\Knot;
use Andante\Measurement\Quantity\Velocity\Imperial\MilePerHour;
use Andante\Measurement\Quantity\Velocity\ImperialVelocity;
use Andante\Measurement\Quantity\Velocity\Metric\CentimeterPerSecond;
use Andante\Measurement\Quantity\Velocity\Metric\KilometerPerHour;
use Andante\Measurement\Quantity\Velocity\Metric\MeterPerSecond;
use Andante\Measurement\Quantity\Velocity\Metric\MillimeterPerSecond;
use Andante\Measurement\Quantity\Velocity\MetricVelocity;
use Andante\Measurement\Quantity\Velocity\Velocity;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\UnitSystem;
use Andante\Measurement\Unit\Velocity\ImperialVelocityUnit;
use Andante\Measurement\Unit\Velocity\MetricVelocityUnit;

/**
 * Provides default configuration for Velocity quantities.
 *
 * Registers all metric and imperial velocity units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to m/s)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to m/s):
 * - 1 m/s = 1 m/s (base)
 * - 1 km/h = 0.27778 m/s (1000/3600)
 * - 1 cm/s = 0.01 m/s
 * - 1 mm/s = 0.001 m/s
 * - 1 mph = 0.44704 m/s
 * - 1 ft/s = 0.3048 m/s
 * - 1 knot = 0.51444 m/s (1852/3600)
 */
final class VelocityProvider implements QuantityDefaultConfigProviderInterface
{
    /**
     * km/h to m/s: 1000 m / 3600 s = 0.27777... m/s.
     */
    private const KILOMETER_PER_HOUR_TO_METER_PER_SECOND = '0.27777777777778';

    /**
     * mph to m/s: 1609.344 m / 3600 s = 0.44704 m/s.
     */
    private const MILE_PER_HOUR_TO_METER_PER_SECOND = '0.44704';

    /**
     * ft/s to m/s: 0.3048 m/s.
     */
    private const FOOT_PER_SECOND_TO_METER_PER_SECOND = '0.3048';

    /**
     * knot to m/s: 1852 m / 3600 s = 0.51444... m/s.
     */
    private const KNOT_TO_METER_PER_SECOND = '0.51444444444444';

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
     * Centralized metric velocity unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to m/s)].
     *
     * @return array<array{MetricVelocityUnit, class-string, numeric-string}>
     */
    private function getMetricUnits(): array
    {
        return [
            [MetricVelocityUnit::MeterPerSecond, MeterPerSecond::class, '1'],
            [MetricVelocityUnit::KilometerPerHour, KilometerPerHour::class, self::KILOMETER_PER_HOUR_TO_METER_PER_SECOND],
            [MetricVelocityUnit::CentimeterPerSecond, CentimeterPerSecond::class, '0.01'],
            [MetricVelocityUnit::MillimeterPerSecond, MillimeterPerSecond::class, '0.001'],
        ];
    }

    /**
     * Centralized imperial velocity unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to m/s)].
     *
     * @return array<array{ImperialVelocityUnit, class-string, numeric-string}>
     */
    private function getImperialUnits(): array
    {
        return [
            [ImperialVelocityUnit::MilePerHour, MilePerHour::class, self::MILE_PER_HOUR_TO_METER_PER_SECOND],
            [ImperialVelocityUnit::FootPerSecond, FootPerSecond::class, self::FOOT_PER_SECOND_TO_METER_PER_SECOND],
            [ImperialVelocityUnit::Knot, Knot::class, self::KNOT_TO_METER_PER_SECOND],
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
        $formula = new DimensionalFormula(length: 1, time: -1);

        // Unit-specific classes → mid-level class (preserves system)
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, MetricVelocity::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ImperialVelocity::class);
        }

        // Mid-level classes → themselves
        $registry->register(MetricVelocity::class, $formula, MetricVelocity::class);
        $registry->register(ImperialVelocity::class, $formula, ImperialVelocity::class);

        // Generic
        $registry->register(Velocity::class, $formula, Velocity::class);
        $registry->registerGeneric($formula, Velocity::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        $formula = new DimensionalFormula(length: 1, time: -1);

        // Default unit for velocity dimension (SI: m/s)
        $registry->register($formula, MetricVelocityUnit::MeterPerSecond);

        // Imperial system default
        $registry->registerForSystem($formula, UnitSystem::Imperial, ImperialVelocityUnit::MilePerHour);
    }
}
