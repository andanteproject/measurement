<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\ElectricPotential\ElectricPotential;
use Andante\Measurement\Quantity\ElectricPotential\SI\Kilovolt;
use Andante\Measurement\Quantity\ElectricPotential\SI\Megavolt;
use Andante\Measurement\Quantity\ElectricPotential\SI\Microvolt;
use Andante\Measurement\Quantity\ElectricPotential\SI\Millivolt;
use Andante\Measurement\Quantity\ElectricPotential\SI\Volt;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\ElectricPotential\ElectricPotentialUnit;

/**
 * Provides default configuration for Electric Potential quantities.
 *
 * Registers all electric potential units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to volt)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to volt):
 * - 1 V = 1 V (base SI derived unit)
 * - 1 MV = 1,000,000 V
 * - 1 kV = 1000 V
 * - 1 mV = 0.001 V
 * - 1 μV = 0.000001 V
 */
final class ElectricPotentialProvider implements QuantityDefaultConfigProviderInterface
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
     * Centralized electric potential unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to volt)].
     *
     * @return array<array{ElectricPotentialUnit, class-string, numeric-string}>
     */
    private function getUnits(): array
    {
        return [
            [ElectricPotentialUnit::Volt, Volt::class, '1'],
            [ElectricPotentialUnit::Megavolt, Megavolt::class, '1000000'],
            [ElectricPotentialUnit::Kilovolt, Kilovolt::class, '1000'],
            [ElectricPotentialUnit::Millivolt, Millivolt::class, '0.001'],
            [ElectricPotentialUnit::Microvolt, Microvolt::class, '0.000001'],
        ];
    }

    public function registerUnits(UnitRegistry $registry): void
    {
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
    }

    public function registerConversionFactors(ConversionFactorRegistry $registry): void
    {
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
    }

    public function registerResultMappings(ResultQuantityRegistry $registry): void
    {
        // Electric potential dimension: L²M¹T⁻³I⁻¹
        $formula = new DimensionalFormula(
            length: 2,
            mass: 1,
            time: -3,
            electricCurrent: -1,
        );

        // Unit-specific classes → generic class
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ElectricPotential::class);
        }

        // Generic
        $registry->register(ElectricPotential::class, $formula, ElectricPotential::class);
        $registry->registerGeneric($formula, ElectricPotential::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // Electric potential dimension: L²M¹T⁻³I⁻¹
        $formula = new DimensionalFormula(
            length: 2,
            mass: 1,
            time: -3,
            electricCurrent: -1,
        );

        // Default unit for electric potential dimension (volt)
        $registry->register($formula, ElectricPotentialUnit::Volt);
    }
}
