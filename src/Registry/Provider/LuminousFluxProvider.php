<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\LuminousFlux\LuminousFlux;
use Andante\Measurement\Quantity\LuminousFlux\SI\Kilolumen;
use Andante\Measurement\Quantity\LuminousFlux\SI\Lumen;
use Andante\Measurement\Quantity\LuminousFlux\SI\Millilumen;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\LuminousFlux\LuminousFluxUnit;

/**
 * Provides default configuration for Luminous Flux quantities.
 *
 * Registers all luminous flux units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to lumen)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to lumen):
 * - 1 lm = 1 lm (base SI derived unit)
 * - 1 klm = 1000 lm
 * - 1 mlm = 0.001 lm
 */
final class LuminousFluxProvider implements QuantityDefaultConfigProviderInterface
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
     * Centralized luminous flux unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to lumen)].
     *
     * @return array<array{LuminousFluxUnit, class-string, numeric-string}>
     */
    private function getUnits(): array
    {
        return [
            [LuminousFluxUnit::Lumen, Lumen::class, '1'],
            [LuminousFluxUnit::Kilolumen, Kilolumen::class, '1000'],
            [LuminousFluxUnit::Millilumen, Millilumen::class, '0.001'],
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
        // Luminous flux dimension: J¹ (same base formula as luminous intensity)
        // We use a distinct dimension class to differentiate them
        $formula = new DimensionalFormula(
            luminousIntensity: 1,
        );

        // Unit-specific classes → generic class
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, LuminousFlux::class);
        }

        // Generic
        $registry->register(LuminousFlux::class, $formula, LuminousFlux::class);
        // Note: We don't registerGeneric here because LuminousIntensity already
        // registers for the J¹ formula. The specific quantity class determines
        // which quantity type is returned.
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // Luminous flux dimension: J¹
        // Note: We don't register a default unit for the formula since
        // LuminousIntensity already registers candela for J¹.
        // The unit is determined by the quantity class, not the formula.
    }
}
