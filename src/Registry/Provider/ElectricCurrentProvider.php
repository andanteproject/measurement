<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\ElectricCurrent\ElectricCurrent;
use Andante\Measurement\Quantity\ElectricCurrent\SI\Ampere;
use Andante\Measurement\Quantity\ElectricCurrent\SI\Kiloampere;
use Andante\Measurement\Quantity\ElectricCurrent\SI\Microampere;
use Andante\Measurement\Quantity\ElectricCurrent\SI\Milliampere;
use Andante\Measurement\Quantity\ElectricCurrent\SI\Nanoampere;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\ElectricCurrent\ElectricCurrentUnit;

/**
 * Provides default configuration for Electric Current quantities.
 *
 * Registers all electric current units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to ampere)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to ampere):
 * - 1 A = 1 A (base SI unit)
 * - 1 kA = 1000 A
 * - 1 mA = 0.001 A
 * - 1 μA = 0.000001 A
 * - 1 nA = 0.000000001 A
 */
final class ElectricCurrentProvider implements QuantityDefaultConfigProviderInterface
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
     * Centralized electric current unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to ampere)].
     *
     * @return array<array{ElectricCurrentUnit, class-string, numeric-string}>
     */
    private function getUnits(): array
    {
        return [
            [ElectricCurrentUnit::Ampere, Ampere::class, '1'],
            [ElectricCurrentUnit::Kiloampere, Kiloampere::class, '1000'],
            [ElectricCurrentUnit::Milliampere, Milliampere::class, '0.001'],
            [ElectricCurrentUnit::Microampere, Microampere::class, '0.000001'],
            [ElectricCurrentUnit::Nanoampere, Nanoampere::class, '0.000000001'],
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
        // Electric current dimension: I¹
        $formula = new DimensionalFormula(
            electricCurrent: 1,
        );

        // Unit-specific classes → generic class
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ElectricCurrent::class);
        }

        // Generic
        $registry->register(ElectricCurrent::class, $formula, ElectricCurrent::class);
        $registry->registerGeneric($formula, ElectricCurrent::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // Electric current dimension: I¹
        $formula = new DimensionalFormula(
            electricCurrent: 1,
        );

        // Default unit for electric current dimension (ampere)
        $registry->register($formula, ElectricCurrentUnit::Ampere);
    }
}
