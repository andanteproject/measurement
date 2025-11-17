<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Temperature\Celsius;
use Andante\Measurement\Quantity\Temperature\Fahrenheit;
use Andante\Measurement\Quantity\Temperature\Kelvin;
use Andante\Measurement\Quantity\Temperature\Temperature;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Temperature\TemperatureUnit;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Provides default configuration for Temperature quantities.
 *
 * Registers all temperature units with their:
 * - Quantity class mappings
 * - Conversion rules (affine transformations relative to Kelvin)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Temperature conversions are affine (not just multiplicative):
 * - Kelvin (K): base unit, factor=1, offset=0
 * - Celsius (°C): K = °C + 273.15, factor=1, offset=273.15
 * - Fahrenheit (°F): K = (°F + 459.67) × 5/9, factor=5/9, offset=255.3722222...
 *
 * Note: The Fahrenheit conversion formula:
 * K = (°F + 459.67) × 5/9
 * K = °F × 5/9 + 459.67 × 5/9
 * K = °F × 5/9 + 255.3722...
 *
 * So factor = 5/9, offset = 459.67 × 5/9 = 2298.35/9
 */
final class TemperatureProvider implements QuantityDefaultConfigProviderInterface
{
    private static ?self $instance = null;

    /**
     * Celsius to Kelvin offset: 273.15.
     */
    private const CELSIUS_OFFSET = '273.15';

    /**
     * Fahrenheit to Kelvin factor: 5/9.
     */
    private const FAHRENHEIT_FACTOR = '0.5555555555555555555555555555555556';

    /**
     * Fahrenheit to Kelvin offset: 459.67 × 5/9 = 255.3722222...
     * Calculated as: (459.67 × 5) / 9 = 2298.35 / 9.
     */
    private const FAHRENHEIT_OFFSET = '255.3722222222222222222222222222222222';

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
     * Temperature unit configuration.
     * Each entry: [unit, quantityClass].
     *
     * @return array<array{TemperatureUnit, class-string}>
     */
    private function getUnits(): array
    {
        return [
            [TemperatureUnit::Kelvin, Kelvin::class],
            [TemperatureUnit::Celsius, Celsius::class],
            [TemperatureUnit::Fahrenheit, Fahrenheit::class],
        ];
    }

    public function registerUnits(UnitRegistry $registry): void
    {
        foreach ($this->getUnits() as [$unit, $quantityClass]) {
            $registry->register($unit, $quantityClass);
        }
    }

    public function registerConversionFactors(ConversionFactorRegistry $registry): void
    {
        // Kelvin is the base unit: factor=1, offset=0 (simple multiplicative)
        $registry->register(
            TemperatureUnit::Kelvin,
            ConversionRule::factor(NumberFactory::create('1')),
        );

        // Celsius: K = °C + 273.15 (affine: factor=1, offset=273.15)
        $registry->register(
            TemperatureUnit::Celsius,
            ConversionRule::factor(
                NumberFactory::create('1'),
                NumberFactory::create(self::CELSIUS_OFFSET),
            ),
        );

        // Fahrenheit: K = °F × 5/9 + 255.372... (affine: factor=5/9, offset=255.372...)
        $registry->register(
            TemperatureUnit::Fahrenheit,
            ConversionRule::factor(
                NumberFactory::create(self::FAHRENHEIT_FACTOR),
                NumberFactory::create(self::FAHRENHEIT_OFFSET),
            ),
        );
    }

    public function registerResultMappings(ResultQuantityRegistry $registry): void
    {
        $formula = new DimensionalFormula(temperature: 1);

        // Unit-specific classes → generic Temperature class
        foreach ($this->getUnits() as [$unit, $quantityClass]) {
            $registry->register($quantityClass, $formula, Temperature::class);
        }

        // Generic
        $registry->register(Temperature::class, $formula, Temperature::class);
        $registry->registerGeneric($formula, Temperature::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        $formula = new DimensionalFormula(temperature: 1);

        // Θ¹ → Kelvin (default unit for temperature dimension)
        $registry->register($formula, TemperatureUnit::Kelvin);

        // Θ¹ → Fahrenheit for Imperial system
        $registry->registerForSystem($formula, UnitSystem::Imperial, TemperatureUnit::Fahrenheit);
    }
}
