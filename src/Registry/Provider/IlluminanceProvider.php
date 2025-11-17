<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Illuminance\Illuminance;
use Andante\Measurement\Quantity\Illuminance\Imperial\FootCandle;
use Andante\Measurement\Quantity\Illuminance\SI\Kilolux;
use Andante\Measurement\Quantity\Illuminance\SI\Lux;
use Andante\Measurement\Quantity\Illuminance\SI\Millilux;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Illuminance\IlluminanceUnit;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Provides default configuration for Illuminance quantities.
 *
 * Registers all illuminance units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to lux)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to lux):
 * - 1 lx = 1 lx (base SI derived unit)
 * - 1 klx = 1000 lx
 * - 1 mlx = 0.001 lx
 * - 1 fc = 10.7639104167097 lx (1 lm/ft² = 1 lm / 0.09290304 m²)
 */
final class IlluminanceProvider implements QuantityDefaultConfigProviderInterface
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
     * Centralized illuminance unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to lux)].
     *
     * @return array<array{IlluminanceUnit, class-string, numeric-string}>
     */
    private function getUnits(): array
    {
        return [
            [IlluminanceUnit::Lux, Lux::class, '1'],
            [IlluminanceUnit::Kilolux, Kilolux::class, '1000'],
            [IlluminanceUnit::Millilux, Millilux::class, '0.001'],
            // 1 fc = 1 lm/ft² = 1 lm / (0.3048 m)² = 1 lm / 0.09290304 m² = 10.7639104167097 lx
            [IlluminanceUnit::FootCandle, FootCandle::class, '10.7639104167097'],
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
        // Illuminance dimension: L⁻²J¹
        $formula = new DimensionalFormula(
            length: -2,
            luminousIntensity: 1,
        );

        // Unit-specific classes → generic class
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, Illuminance::class);
        }

        // Generic
        $registry->register(Illuminance::class, $formula, Illuminance::class);
        $registry->registerGeneric($formula, Illuminance::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // Illuminance dimension: L⁻²J¹
        $formula = new DimensionalFormula(
            length: -2,
            luminousIntensity: 1,
        );

        // Default unit for illuminance dimension (lux)
        $registry->register($formula, IlluminanceUnit::Lux);

        // Imperial system default (foot-candle)
        $registry->registerForSystem($formula, UnitSystem::Imperial, IlluminanceUnit::FootCandle);
    }
}
