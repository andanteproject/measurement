<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Area\Area;
use Andante\Measurement\Quantity\Area\ImperialArea;
use Andante\Measurement\Quantity\Area\MetricArea;
use Andante\Measurement\Quantity\Length\Imperial\Foot;
use Andante\Measurement\Quantity\Length\Imperial\Inch;
use Andante\Measurement\Quantity\Length\Imperial\Mile;
use Andante\Measurement\Quantity\Length\Imperial\NauticalMile;
use Andante\Measurement\Quantity\Length\Imperial\Yard;
use Andante\Measurement\Quantity\Length\ImperialLength;
use Andante\Measurement\Quantity\Length\Length;
use Andante\Measurement\Quantity\Length\Metric\Centimeter;
use Andante\Measurement\Quantity\Length\Metric\Decameter;
use Andante\Measurement\Quantity\Length\Metric\Decimeter;
use Andante\Measurement\Quantity\Length\Metric\Hectometer;
use Andante\Measurement\Quantity\Length\Metric\Kilometer;
use Andante\Measurement\Quantity\Length\Metric\Meter;
use Andante\Measurement\Quantity\Length\Metric\Micrometer;
use Andante\Measurement\Quantity\Length\Metric\Millimeter;
use Andante\Measurement\Quantity\Length\Metric\Nanometer;
use Andante\Measurement\Quantity\Length\MetricLength;
use Andante\Measurement\Quantity\Volume\ImperialVolume;
use Andante\Measurement\Quantity\Volume\MetricVolume;
use Andante\Measurement\Quantity\Volume\Volume;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Length\ImperialLengthUnit;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Provides default configuration for Length quantities.
 *
 * Registers all metric and imperial length units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to Meter)
 * - Result quantity mappings for operations
 * - Default formula units
 */
final class LengthProvider implements QuantityDefaultConfigProviderInterface
{
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
     * Centralized metric unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor].
     *
     * @return array<array{MetricLengthUnit, class-string, numeric-string}>
     */
    private function getMetricUnits(): array
    {
        return [
            [MetricLengthUnit::Meter, Meter::class, '1'],
            [MetricLengthUnit::Kilometer, Kilometer::class, '1000'],
            [MetricLengthUnit::Hectometer, Hectometer::class, '100'],
            [MetricLengthUnit::Decameter, Decameter::class, '10'],
            [MetricLengthUnit::Decimeter, Decimeter::class, '0.1'],
            [MetricLengthUnit::Centimeter, Centimeter::class, '0.01'],
            [MetricLengthUnit::Millimeter, Millimeter::class, '0.001'],
            [MetricLengthUnit::Micrometer, Micrometer::class, '0.000001'],
            [MetricLengthUnit::Nanometer, Nanometer::class, '0.000000001'],
        ];
    }

    /**
     * Centralized imperial unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor].
     *
     * @return array<array{ImperialLengthUnit, class-string, numeric-string}>
     */
    private function getImperialUnits(): array
    {
        return [
            [ImperialLengthUnit::Inch, Inch::class, '0.0254'],
            [ImperialLengthUnit::Foot, Foot::class, '0.3048'],
            [ImperialLengthUnit::Yard, Yard::class, '0.9144'],
            [ImperialLengthUnit::Mile, Mile::class, '1609.344'],
            [ImperialLengthUnit::NauticalMile, NauticalMile::class, '1852'],
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
        $lengthFormula = new DimensionalFormula(length: 1);
        $areaFormula = new DimensionalFormula(length: 2);
        $volumeFormula = new DimensionalFormula(length: 3);

        // Length → Length (L¹) mappings
        // Unit-specific classes → mid-level class (preserves system)
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $lengthFormula, MetricLength::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $lengthFormula, ImperialLength::class);
        }

        // Mid-level classes → themselves
        $registry->register(MetricLength::class, $lengthFormula, MetricLength::class);
        $registry->register(ImperialLength::class, $lengthFormula, ImperialLength::class);

        // Generic
        $registry->register(Length::class, $lengthFormula, Length::class);
        $registry->registerGeneric($lengthFormula, Length::class);

        // Length × Length → Area (L²) mappings
        // Unit-specific classes → mid-level Area class (preserves system)
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $areaFormula, MetricArea::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $areaFormula, ImperialArea::class);
        }

        // Mid-level Length classes → mid-level Area classes
        $registry->register(MetricLength::class, $areaFormula, MetricArea::class);
        $registry->register(ImperialLength::class, $areaFormula, ImperialArea::class);

        // Generic Length → generic Area
        $registry->register(Length::class, $areaFormula, Area::class);

        // Length × Length × Length → Volume (L³) mappings
        // Unit-specific classes → mid-level Volume class (preserves system)
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $volumeFormula, MetricVolume::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $volumeFormula, ImperialVolume::class);
        }

        // Mid-level Length classes → mid-level Volume classes
        $registry->register(MetricLength::class, $volumeFormula, MetricVolume::class);
        $registry->register(ImperialLength::class, $volumeFormula, ImperialVolume::class);

        // Generic Length → generic Volume
        $registry->register(Length::class, $volumeFormula, Volume::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        $formula = new DimensionalFormula(length: 1);

        // L¹ → Meter (default unit for length dimension)
        $registry->register($formula, MetricLengthUnit::Meter);

        // L¹ → Foot for Imperial system
        $registry->registerForSystem($formula, UnitSystem::Imperial, ImperialLengthUnit::Foot);
    }
}
