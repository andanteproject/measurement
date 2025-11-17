<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\MagneticFlux\CGS\Maxwell;
use Andante\Measurement\Quantity\MagneticFlux\MagneticFlux;
use Andante\Measurement\Quantity\MagneticFlux\SI\Microweber;
use Andante\Measurement\Quantity\MagneticFlux\SI\Milliweber;
use Andante\Measurement\Quantity\MagneticFlux\SI\Weber;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\MagneticFlux\MagneticFluxUnit;

/**
 * Provides default configuration for Magnetic Flux quantities.
 *
 * Registers all magnetic flux units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to weber)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to weber):
 * - 1 Wb = 1 Wb (base SI derived unit)
 * - 1 mWb = 0.001 Wb
 * - 1 μWb = 0.000001 Wb
 * - 1 Mx = 0.00000001 Wb (10⁻⁸ Wb)
 */
final class MagneticFluxProvider implements QuantityDefaultConfigProviderInterface
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
     * Centralized magnetic flux unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to weber)].
     *
     * @return array<array{MagneticFluxUnit, class-string, numeric-string}>
     */
    private function getUnits(): array
    {
        return [
            [MagneticFluxUnit::Weber, Weber::class, '1'],
            [MagneticFluxUnit::Milliweber, Milliweber::class, '0.001'],
            [MagneticFluxUnit::Microweber, Microweber::class, '0.000001'],
            [MagneticFluxUnit::Maxwell, Maxwell::class, '0.00000001'],
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
        // Magnetic flux dimension: L²M¹T⁻²I⁻¹
        $formula = new DimensionalFormula(
            length: 2,
            mass: 1,
            time: -2,
            electricCurrent: -1,
        );

        // Unit-specific classes → generic class
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, MagneticFlux::class);
        }

        // Generic
        $registry->register(MagneticFlux::class, $formula, MagneticFlux::class);
        $registry->registerGeneric($formula, MagneticFlux::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // Magnetic flux dimension: L²M¹T⁻²I⁻¹
        $formula = new DimensionalFormula(
            length: 2,
            mass: 1,
            time: -2,
            electricCurrent: -1,
        );

        // Default unit for magnetic flux dimension (weber)
        $registry->register($formula, MagneticFluxUnit::Weber);
    }
}
