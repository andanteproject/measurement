<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\ElectricCapacitance\ElectricCapacitance;
use Andante\Measurement\Quantity\ElectricCapacitance\SI\Farad;
use Andante\Measurement\Quantity\ElectricCapacitance\SI\Microfarad;
use Andante\Measurement\Quantity\ElectricCapacitance\SI\Millifarad;
use Andante\Measurement\Quantity\ElectricCapacitance\SI\Nanofarad;
use Andante\Measurement\Quantity\ElectricCapacitance\SI\Picofarad;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\ElectricCapacitance\ElectricCapacitanceUnit;

/**
 * Provides default configuration for Electric Capacitance quantities.
 *
 * Registers all electric capacitance units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to farad)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to farad):
 * - 1 F = 1 F (base SI derived unit)
 * - 1 mF = 0.001 F
 * - 1 μF = 0.000001 F
 * - 1 nF = 0.000000001 F
 * - 1 pF = 0.000000000001 F
 */
final class ElectricCapacitanceProvider implements QuantityDefaultConfigProviderInterface
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
     * Centralized electric capacitance unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to farad)].
     *
     * @return array<array{ElectricCapacitanceUnit, class-string, numeric-string}>
     */
    private function getUnits(): array
    {
        return [
            [ElectricCapacitanceUnit::Farad, Farad::class, '1'],
            [ElectricCapacitanceUnit::Millifarad, Millifarad::class, '0.001'],
            [ElectricCapacitanceUnit::Microfarad, Microfarad::class, '0.000001'],
            [ElectricCapacitanceUnit::Nanofarad, Nanofarad::class, '0.000000001'],
            [ElectricCapacitanceUnit::Picofarad, Picofarad::class, '0.000000000001'],
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
        // Electric capacitance dimension: L⁻²M⁻¹T⁴I²
        $formula = new DimensionalFormula(
            length: -2,
            mass: -1,
            time: 4,
            electricCurrent: 2,
        );

        // Unit-specific classes → generic class
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ElectricCapacitance::class);
        }

        // Generic
        $registry->register(ElectricCapacitance::class, $formula, ElectricCapacitance::class);
        $registry->registerGeneric($formula, ElectricCapacitance::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // Electric capacitance dimension: L⁻²M⁻¹T⁴I²
        $formula = new DimensionalFormula(
            length: -2,
            mass: -1,
            time: 4,
            electricCurrent: 2,
        );

        // Default unit for electric capacitance dimension (farad)
        $registry->register($formula, ElectricCapacitanceUnit::Farad);
    }
}
