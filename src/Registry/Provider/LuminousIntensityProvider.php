<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\LuminousIntensity\LuminousIntensity;
use Andante\Measurement\Quantity\LuminousIntensity\SI\Candela;
use Andante\Measurement\Quantity\LuminousIntensity\SI\Kilocandela;
use Andante\Measurement\Quantity\LuminousIntensity\SI\Microcandela;
use Andante\Measurement\Quantity\LuminousIntensity\SI\Millicandela;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\LuminousIntensity\LuminousIntensityUnit;

/**
 * Provides default configuration for Luminous Intensity quantities.
 *
 * Registers all luminous intensity units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to candela)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to candela):
 * - 1 cd = 1 cd (base SI unit)
 * - 1 kcd = 1000 cd
 * - 1 mcd = 0.001 cd
 * - 1 μcd = 0.000001 cd
 */
final class LuminousIntensityProvider implements QuantityDefaultConfigProviderInterface
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
     * Centralized luminous intensity unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to candela)].
     *
     * @return array<array{LuminousIntensityUnit, class-string, numeric-string}>
     */
    private function getUnits(): array
    {
        return [
            [LuminousIntensityUnit::Candela, Candela::class, '1'],
            [LuminousIntensityUnit::Kilocandela, Kilocandela::class, '1000'],
            [LuminousIntensityUnit::Millicandela, Millicandela::class, '0.001'],
            [LuminousIntensityUnit::Microcandela, Microcandela::class, '0.000001'],
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
        // Luminous intensity dimension: J¹
        $formula = new DimensionalFormula(
            luminousIntensity: 1,
        );

        // Unit-specific classes → generic class
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, LuminousIntensity::class);
        }

        // Generic
        $registry->register(LuminousIntensity::class, $formula, LuminousIntensity::class);
        $registry->registerGeneric($formula, LuminousIntensity::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // Luminous intensity dimension: J¹
        $formula = new DimensionalFormula(
            luminousIntensity: 1,
        );

        // Default unit for luminous intensity dimension (candela)
        $registry->register($formula, LuminousIntensityUnit::Candela);
    }
}
