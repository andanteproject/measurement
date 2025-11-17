<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\ElectricResistance\ElectricResistance;
use Andante\Measurement\Quantity\ElectricResistance\SI\Kiloohm;
use Andante\Measurement\Quantity\ElectricResistance\SI\Megaohm;
use Andante\Measurement\Quantity\ElectricResistance\SI\Microohm;
use Andante\Measurement\Quantity\ElectricResistance\SI\Milliohm;
use Andante\Measurement\Quantity\ElectricResistance\SI\Ohm;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\ElectricResistance\ElectricResistanceUnit;

/**
 * Provides default configuration for Electric Resistance quantities.
 *
 * Registers all electric resistance units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to ohm)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to ohm):
 * - 1 Ω = 1 Ω (base SI derived unit)
 * - 1 MΩ = 1,000,000 Ω
 * - 1 kΩ = 1000 Ω
 * - 1 mΩ = 0.001 Ω
 * - 1 μΩ = 0.000001 Ω
 */
final class ElectricResistanceProvider implements QuantityDefaultConfigProviderInterface
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
     * Centralized electric resistance unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to ohm)].
     *
     * @return array<array{ElectricResistanceUnit, class-string, numeric-string}>
     */
    private function getUnits(): array
    {
        return [
            [ElectricResistanceUnit::Ohm, Ohm::class, '1'],
            [ElectricResistanceUnit::Megaohm, Megaohm::class, '1000000'],
            [ElectricResistanceUnit::Kiloohm, Kiloohm::class, '1000'],
            [ElectricResistanceUnit::Milliohm, Milliohm::class, '0.001'],
            [ElectricResistanceUnit::Microohm, Microohm::class, '0.000001'],
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
        // Electric resistance dimension: L²M¹T⁻³I⁻²
        $formula = new DimensionalFormula(
            length: 2,
            mass: 1,
            time: -3,
            electricCurrent: -2,
        );

        // Unit-specific classes → generic class
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ElectricResistance::class);
        }

        // Generic
        $registry->register(ElectricResistance::class, $formula, ElectricResistance::class);
        $registry->registerGeneric($formula, ElectricResistance::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // Electric resistance dimension: L²M¹T⁻³I⁻²
        $formula = new DimensionalFormula(
            length: 2,
            mass: 1,
            time: -3,
            electricCurrent: -2,
        );

        // Default unit for electric resistance dimension (ohm)
        $registry->register($formula, ElectricResistanceUnit::Ohm);
    }
}
