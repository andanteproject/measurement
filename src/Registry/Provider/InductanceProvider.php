<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Inductance\Inductance;
use Andante\Measurement\Quantity\Inductance\SI\Henry;
use Andante\Measurement\Quantity\Inductance\SI\Microhenry;
use Andante\Measurement\Quantity\Inductance\SI\Millihenry;
use Andante\Measurement\Quantity\Inductance\SI\Nanohenry;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Inductance\InductanceUnit;

/**
 * Provides default configuration for Inductance quantities.
 *
 * Registers all inductance units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to henry)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to henry):
 * - 1 H = 1 H (base SI derived unit)
 * - 1 mH = 0.001 H
 * - 1 μH = 0.000001 H
 * - 1 nH = 0.000000001 H
 */
final class InductanceProvider implements QuantityDefaultConfigProviderInterface
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
     * Centralized inductance unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to henry)].
     *
     * @return array<array{InductanceUnit, class-string, numeric-string}>
     */
    private function getUnits(): array
    {
        return [
            [InductanceUnit::Henry, Henry::class, '1'],
            [InductanceUnit::Millihenry, Millihenry::class, '0.001'],
            [InductanceUnit::Microhenry, Microhenry::class, '0.000001'],
            [InductanceUnit::Nanohenry, Nanohenry::class, '0.000000001'],
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
        // Inductance dimension: L²M¹T⁻²I⁻²
        $formula = new DimensionalFormula(
            length: 2,
            mass: 1,
            time: -2,
            electricCurrent: -2,
        );

        // Unit-specific classes → generic class
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, Inductance::class);
        }

        // Generic
        $registry->register(Inductance::class, $formula, Inductance::class);
        $registry->registerGeneric($formula, Inductance::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // Inductance dimension: L²M¹T⁻²I⁻²
        $formula = new DimensionalFormula(
            length: 2,
            mass: 1,
            time: -2,
            electricCurrent: -2,
        );

        // Default unit for inductance dimension (henry)
        $registry->register($formula, InductanceUnit::Henry);
    }
}
