<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Area\Area;
use Andante\Measurement\Quantity\Area\Imperial\Acre;
use Andante\Measurement\Quantity\Area\Imperial\SquareFoot;
use Andante\Measurement\Quantity\Area\Imperial\SquareInch;
use Andante\Measurement\Quantity\Area\Imperial\SquareMile;
use Andante\Measurement\Quantity\Area\Imperial\SquareYard;
use Andante\Measurement\Quantity\Area\ImperialArea;
use Andante\Measurement\Quantity\Area\Metric\Are;
use Andante\Measurement\Quantity\Area\Metric\Hectare;
use Andante\Measurement\Quantity\Area\Metric\SquareCentimeter;
use Andante\Measurement\Quantity\Area\Metric\SquareDecimeter;
use Andante\Measurement\Quantity\Area\Metric\SquareKilometer;
use Andante\Measurement\Quantity\Area\Metric\SquareMeter;
use Andante\Measurement\Quantity\Area\Metric\SquareMillimeter;
use Andante\Measurement\Quantity\Area\MetricArea;
use Andante\Measurement\Quantity\Length\ImperialLength;
use Andante\Measurement\Quantity\Length\Length;
use Andante\Measurement\Quantity\Length\MetricLength;
use Andante\Measurement\Quantity\Volume\ImperialVolume;
use Andante\Measurement\Quantity\Volume\MetricVolume;
use Andante\Measurement\Quantity\Volume\Volume;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Area\ImperialAreaUnit;
use Andante\Measurement\Unit\Area\MetricAreaUnit;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Provides default configuration for Area quantities.
 *
 * Registers all metric and imperial area units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to Square Meter)
 * - Result quantity mappings for operations
 * - Default formula units
 */
final class AreaProvider implements QuantityDefaultConfigProviderInterface
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
     * All factors are relative to square meter (base unit).
     *
     * @return array<array{MetricAreaUnit, class-string, numeric-string}>
     */
    private function getMetricUnits(): array
    {
        return [
            [MetricAreaUnit::SquareMeter, SquareMeter::class, '1'],
            [MetricAreaUnit::SquareKilometer, SquareKilometer::class, '1000000'],         // 1 km² = 1,000,000 m²
            [MetricAreaUnit::SquareCentimeter, SquareCentimeter::class, '0.0001'],        // 1 cm² = 0.0001 m²
            [MetricAreaUnit::SquareMillimeter, SquareMillimeter::class, '0.000001'],      // 1 mm² = 0.000001 m²
            [MetricAreaUnit::SquareDecimeter, SquareDecimeter::class, '0.01'],            // 1 dm² = 0.01 m²
            [MetricAreaUnit::Hectare, Hectare::class, '10000'],                            // 1 ha = 10,000 m²
            [MetricAreaUnit::Are, Are::class, '100'],                                      // 1 a = 100 m²
        ];
    }

    /**
     * Centralized imperial unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor].
     *
     * All factors are relative to square meter (base unit).
     * - 1 square foot = 0.09290304 m² (exact, based on 1 ft = 0.3048 m)
     * - 1 square inch = 0.00064516 m² (exact)
     * - 1 square yard = 0.83612736 m² (exact, 9 ft²)
     * - 1 square mile = 2,589,988.110336 m² (exact)
     * - 1 acre = 4,046.8564224 m² (exact, 43560 ft²)
     *
     * @return array<array{ImperialAreaUnit, class-string, numeric-string}>
     */
    private function getImperialUnits(): array
    {
        return [
            [ImperialAreaUnit::SquareFoot, SquareFoot::class, '0.09290304'],
            [ImperialAreaUnit::SquareInch, SquareInch::class, '0.00064516'],
            [ImperialAreaUnit::SquareYard, SquareYard::class, '0.83612736'],
            [ImperialAreaUnit::SquareMile, SquareMile::class, '2589988.110336'],
            [ImperialAreaUnit::Acre, Acre::class, '4046.8564224'],
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

        // Area → Area (L²) mappings
        // Unit-specific classes → mid-level class (preserves system)
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $areaFormula, MetricArea::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $areaFormula, ImperialArea::class);
        }

        // Mid-level classes → themselves
        $registry->register(MetricArea::class, $areaFormula, MetricArea::class);
        $registry->register(ImperialArea::class, $areaFormula, ImperialArea::class);

        // Generic
        $registry->register(Area::class, $areaFormula, Area::class);
        $registry->registerGeneric($areaFormula, Area::class);

        // √Area → Length (L¹) mappings
        // Unit-specific classes → mid-level Length class (preserves system)
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $lengthFormula, MetricLength::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $lengthFormula, ImperialLength::class);
        }

        // Mid-level Area classes → mid-level Length classes
        $registry->register(MetricArea::class, $lengthFormula, MetricLength::class);
        $registry->register(ImperialArea::class, $lengthFormula, ImperialLength::class);

        // Generic Area → generic Length
        $registry->register(Area::class, $lengthFormula, Length::class);

        // Area × Length → Volume (L³) mappings
        // Unit-specific classes → mid-level Volume class (preserves system)
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $volumeFormula, MetricVolume::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $volumeFormula, ImperialVolume::class);
        }

        // Mid-level Area classes → mid-level Volume classes
        $registry->register(MetricArea::class, $volumeFormula, MetricVolume::class);
        $registry->register(ImperialArea::class, $volumeFormula, ImperialVolume::class);

        // Generic Area → generic Volume
        $registry->register(Area::class, $volumeFormula, Volume::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        $formula = new DimensionalFormula(length: 2);

        // L² → SquareMeter (default unit for area dimension)
        $registry->register($formula, MetricAreaUnit::SquareMeter);

        // L² → SquareFoot for Imperial system
        $registry->registerForSystem($formula, UnitSystem::Imperial, ImperialAreaUnit::SquareFoot);
    }
}
