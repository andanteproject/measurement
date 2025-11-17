<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Temperature;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Temperature\Celsius;
use Andante\Measurement\Quantity\Temperature\Fahrenheit;
use Andante\Measurement\Quantity\Temperature\Kelvin;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Temperature\TemperatureUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for temperature conversions.
 *
 * Temperature conversions are affine (not just multiplicative):
 * - Kelvin (K): SI base unit
 * - Celsius (°C): K = °C + 273.15
 * - Fahrenheit (°F): K = (°F + 459.67) × 5/9
 */
final class TemperatureConversionTest extends TestCase
{
    protected function setUp(): void
    {
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
    }

    protected function tearDown(): void
    {
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
    }

    public function testCelsiusToKelvin(): void
    {
        // 0°C = 273.15 K
        $celsius = Celsius::of(NumberFactory::create('0'));
        $kelvin = $celsius->to(TemperatureUnit::Kelvin);

        self::assertEqualsWithDelta(273.15, (float) $kelvin->getValue()->value(), 0.01);
    }

    public function testKelvinToCelsius(): void
    {
        // 273.15 K = 0°C
        $kelvin = Kelvin::of(NumberFactory::create('273.15'));
        $celsius = $kelvin->to(TemperatureUnit::Celsius);

        self::assertEqualsWithDelta(0.0, (float) $celsius->getValue()->value(), 0.01);
    }

    public function testBoilingPointCelsiusToKelvin(): void
    {
        // 100°C = 373.15 K
        $celsius = Celsius::of(NumberFactory::create('100'));
        $kelvin = $celsius->to(TemperatureUnit::Kelvin);

        self::assertEqualsWithDelta(373.15, (float) $kelvin->getValue()->value(), 0.01);
    }

    public function testFreezingPointFahrenheitToKelvin(): void
    {
        // 32°F = 273.15 K (freezing point of water)
        $fahrenheit = Fahrenheit::of(NumberFactory::create('32'));
        $kelvin = $fahrenheit->to(TemperatureUnit::Kelvin);

        self::assertEqualsWithDelta(273.15, (float) $kelvin->getValue()->value(), 0.01);
    }

    public function testBoilingPointFahrenheitToKelvin(): void
    {
        // 212°F = 373.15 K (boiling point of water)
        $fahrenheit = Fahrenheit::of(NumberFactory::create('212'));
        $kelvin = $fahrenheit->to(TemperatureUnit::Kelvin);

        self::assertEqualsWithDelta(373.15, (float) $kelvin->getValue()->value(), 0.01);
    }

    public function testKelvinToFahrenheit(): void
    {
        // 273.15 K = 32°F (freezing point)
        $kelvin = Kelvin::of(NumberFactory::create('273.15'));
        $fahrenheit = $kelvin->to(TemperatureUnit::Fahrenheit);

        self::assertEqualsWithDelta(32.0, (float) $fahrenheit->getValue()->value(), 0.01);
    }

    public function testCelsiusToFahrenheit(): void
    {
        // 0°C = 32°F
        $celsius = Celsius::of(NumberFactory::create('0'));
        $fahrenheit = $celsius->to(TemperatureUnit::Fahrenheit);

        self::assertEqualsWithDelta(32.0, (float) $fahrenheit->getValue()->value(), 0.01);
    }

    public function testFahrenheitToCelsius(): void
    {
        // 32°F = 0°C
        $fahrenheit = Fahrenheit::of(NumberFactory::create('32'));
        $celsius = $fahrenheit->to(TemperatureUnit::Celsius);

        self::assertEqualsWithDelta(0.0, (float) $celsius->getValue()->value(), 0.01);
    }

    public function testRoomTemperatureCelsiusToFahrenheit(): void
    {
        // 25°C = 77°F (typical room temperature)
        $celsius = Celsius::of(NumberFactory::create('25'));
        $fahrenheit = $celsius->to(TemperatureUnit::Fahrenheit);

        self::assertEqualsWithDelta(77.0, (float) $fahrenheit->getValue()->value(), 0.01);
    }

    public function testRoomTemperatureFahrenheitToCelsius(): void
    {
        // 77°F = 25°C
        $fahrenheit = Fahrenheit::of(NumberFactory::create('77'));
        $celsius = $fahrenheit->to(TemperatureUnit::Celsius);

        self::assertEqualsWithDelta(25.0, (float) $celsius->getValue()->value(), 0.01);
    }

    public function testAbsoluteZero(): void
    {
        // 0 K = -273.15°C = -459.67°F
        $kelvin = Kelvin::of(NumberFactory::create('0'));

        $celsius = $kelvin->to(TemperatureUnit::Celsius);
        self::assertEqualsWithDelta(-273.15, (float) $celsius->getValue()->value(), 0.01);

        $fahrenheit = $kelvin->to(TemperatureUnit::Fahrenheit);
        self::assertEqualsWithDelta(-459.67, (float) $fahrenheit->getValue()->value(), 0.01);
    }

    public function testNegativeTemperatures(): void
    {
        // -40°C = -40°F (the two scales meet at this point!)
        $celsius = Celsius::of(NumberFactory::create('-40'));
        $fahrenheit = $celsius->to(TemperatureUnit::Fahrenheit);

        self::assertEqualsWithDelta(-40.0, (float) $fahrenheit->getValue()->value(), 0.01);

        // And vice versa
        $fahrenheit2 = Fahrenheit::of(NumberFactory::create('-40'));
        $celsius2 = $fahrenheit2->to(TemperatureUnit::Celsius);

        self::assertEqualsWithDelta(-40.0, (float) $celsius2->getValue()->value(), 0.01);
    }

    public function testBodyTemperature(): void
    {
        // 98.6°F = 37°C (human body temperature)
        $fahrenheit = Fahrenheit::of(NumberFactory::create('98.6'));
        $celsius = $fahrenheit->to(TemperatureUnit::Celsius);

        self::assertEqualsWithDelta(37.0, (float) $celsius->getValue()->value(), 0.01);
    }

    public function testCelsiusToKelvinRoundTrip(): void
    {
        $original = Celsius::of(NumberFactory::create('25.5'));
        $kelvinQuantity = $original->to(TemperatureUnit::Kelvin);

        // Convert back using Kelvin class
        $kelvin = Kelvin::of($kelvinQuantity->getValue());
        $backToCelsius = $kelvin->to(TemperatureUnit::Celsius);

        self::assertEqualsWithDelta(25.5, (float) $backToCelsius->getValue()->value(), 0.001);
    }

    public function testFahrenheitToKelvinRoundTrip(): void
    {
        $original = Fahrenheit::of(NumberFactory::create('72'));
        $kelvinQuantity = $original->to(TemperatureUnit::Kelvin);

        // Convert back using Kelvin class
        $kelvin = Kelvin::of($kelvinQuantity->getValue());
        $backToFahrenheit = $kelvin->to(TemperatureUnit::Fahrenheit);

        self::assertEqualsWithDelta(72.0, (float) $backToFahrenheit->getValue()->value(), 0.001);
    }
}
