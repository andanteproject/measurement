<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Mass\Imperial\LongTon;
use Andante\Measurement\Quantity\Mass\Imperial\Ounce;
use Andante\Measurement\Quantity\Mass\Imperial\Pound;
use Andante\Measurement\Quantity\Mass\Imperial\ShortTon;
use Andante\Measurement\Quantity\Mass\Imperial\Stone;
use Andante\Measurement\Quantity\Mass\ImperialMass;
use Andante\Measurement\Quantity\Mass\Mass;
use Andante\Measurement\Quantity\Mass\Metric\Centigram;
use Andante\Measurement\Quantity\Mass\Metric\Decagram;
use Andante\Measurement\Quantity\Mass\Metric\Decigram;
use Andante\Measurement\Quantity\Mass\Metric\Gram;
use Andante\Measurement\Quantity\Mass\Metric\Hectogram;
use Andante\Measurement\Quantity\Mass\Metric\Kilogram;
use Andante\Measurement\Quantity\Mass\Metric\Microgram;
use Andante\Measurement\Quantity\Mass\Metric\Milligram;
use Andante\Measurement\Quantity\Mass\Metric\Tonne;
use Andante\Measurement\Quantity\Mass\MetricMass;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Mass\ImperialMassUnit;
use Andante\Measurement\Unit\Mass\MetricMassUnit;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Provides default configuration for Mass quantities.
 *
 * Registers all metric and imperial mass units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to Kilogram)
 * - Result quantity mappings for operations
 * - Default formula units
 */
final class MassProvider implements QuantityDefaultConfigProviderInterface
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
     * All factors are relative to kilogram (base unit).
     *
     * @return array<array{MetricMassUnit, class-string, numeric-string}>
     */
    private function getMetricUnits(): array
    {
        return [
            [MetricMassUnit::Kilogram, Kilogram::class, '1'],
            [MetricMassUnit::Gram, Gram::class, '0.001'],
            [MetricMassUnit::Milligram, Milligram::class, '0.000001'],
            [MetricMassUnit::Microgram, Microgram::class, '0.000000001'],
            [MetricMassUnit::Tonne, Tonne::class, '1000'],
            [MetricMassUnit::Hectogram, Hectogram::class, '0.1'],
            [MetricMassUnit::Decagram, Decagram::class, '0.01'],
            [MetricMassUnit::Decigram, Decigram::class, '0.0001'],
            [MetricMassUnit::Centigram, Centigram::class, '0.00001'],
        ];
    }

    /**
     * Centralized imperial unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor].
     *
     * All factors are relative to kilogram (base unit).
     * - 1 pound = 0.45359237 kg (exact)
     * - 1 ounce = 1/16 pound = 0.028349523125 kg
     * - 1 stone = 14 pounds = 6.35029318 kg
     * - 1 short ton = 2000 pounds = 907.18474 kg
     * - 1 long ton = 2240 pounds = 1016.0469088 kg
     *
     * @return array<array{ImperialMassUnit, class-string, numeric-string}>
     */
    private function getImperialUnits(): array
    {
        return [
            [ImperialMassUnit::Pound, Pound::class, '0.45359237'],
            [ImperialMassUnit::Ounce, Ounce::class, '0.028349523125'],
            [ImperialMassUnit::Stone, Stone::class, '6.35029318'],
            [ImperialMassUnit::ShortTon, ShortTon::class, '907.18474'],
            [ImperialMassUnit::LongTon, LongTon::class, '1016.0469088'],
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
        $formula = new DimensionalFormula(mass: 1);

        // Unit-specific classes → mid-level class (preserves system)
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, MetricMass::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ImperialMass::class);
        }

        // Mid-level classes → themselves
        $registry->register(MetricMass::class, $formula, MetricMass::class);
        $registry->register(ImperialMass::class, $formula, ImperialMass::class);

        // Generic
        $registry->register(Mass::class, $formula, Mass::class);
        $registry->registerGeneric($formula, Mass::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        $formula = new DimensionalFormula(mass: 1);

        // M¹ → Kilogram (default unit for mass dimension)
        $registry->register($formula, MetricMassUnit::Kilogram);

        // M¹ → Pound for Imperial system
        $registry->registerForSystem($formula, UnitSystem::Imperial, ImperialMassUnit::Pound);
    }
}
