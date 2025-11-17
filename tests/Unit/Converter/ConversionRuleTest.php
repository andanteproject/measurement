<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Converter;

use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Math\NumberFactory;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the ConversionRule class.
 *
 * ConversionRule supports both simple multiplicative conversions
 * and affine conversions (with offset) like temperature.
 */
final class ConversionRuleTest extends TestCase
{
    public function testFactorOnlyRuleIsMultiplicative(): void
    {
        $rule = ConversionRule::factor(NumberFactory::create('1000'));

        self::assertTrue($rule->isMultiplicative());
    }

    public function testFactorWithOffsetRuleIsNotMultiplicative(): void
    {
        $rule = ConversionRule::factor(
            NumberFactory::create('1'),
            NumberFactory::create('273.15'),
        );

        self::assertFalse($rule->isMultiplicative());
    }

    public function testFactorWithZeroOffsetIsMultiplicative(): void
    {
        $rule = ConversionRule::factor(
            NumberFactory::create('1000'),
            NumberFactory::create('0'),
        );

        self::assertTrue($rule->isMultiplicative());
    }

    public function testFactorToBase(): void
    {
        // 1 kilometer = 1000 meters
        $rule = ConversionRule::factor(NumberFactory::create('1000'));

        $baseValue = $rule->toBase(NumberFactory::create('5'));

        self::assertSame('5000', $baseValue->value());
    }

    public function testFactorFromBase(): void
    {
        // 1 kilometer = 1000 meters
        $rule = ConversionRule::factor(NumberFactory::create('1000'));

        $value = $rule->fromBase(NumberFactory::create('5000'), scale: 10);

        self::assertEqualsWithDelta(5.0, (float) $value->value(), 0.0001);
    }

    public function testFactorWithOffsetToBaseCelsiusToKelvin(): void
    {
        // K = °C + 273.15 (factor=1, offset=273.15)
        $rule = ConversionRule::factor(
            NumberFactory::create('1'),
            NumberFactory::create('273.15'),
        );

        // 0°C = 273.15 K
        $kelvin = $rule->toBase(NumberFactory::create('0'));
        self::assertSame('273.15', $kelvin->value());

        // 100°C = 373.15 K
        $kelvin100 = $rule->toBase(NumberFactory::create('100'));
        self::assertSame('373.15', $kelvin100->value());
    }

    public function testFactorWithOffsetFromBaseKelvinToCelsius(): void
    {
        // K = °C + 273.15 (factor=1, offset=273.15)
        $rule = ConversionRule::factor(
            NumberFactory::create('1'),
            NumberFactory::create('273.15'),
        );

        // 273.15 K = 0°C
        $celsius = $rule->fromBase(NumberFactory::create('273.15'), scale: 10);
        self::assertEqualsWithDelta(0.0, (float) $celsius->value(), 0.0001);

        // 373.15 K = 100°C
        $celsius100 = $rule->fromBase(NumberFactory::create('373.15'), scale: 10);
        self::assertEqualsWithDelta(100.0, (float) $celsius100->value(), 0.0001);
    }

    public function testFactorWithOffsetToBaseFahrenheitToKelvin(): void
    {
        // K = °F × 5/9 + 255.372... (factor=5/9, offset=255.372...)
        $rule = ConversionRule::factor(
            NumberFactory::create('0.5555555555555555555555555555555556'),
            NumberFactory::create('255.3722222222222222222222222222222222'),
        );

        // 32°F = 273.15 K (freezing point)
        $kelvin = $rule->toBase(NumberFactory::create('32'));
        self::assertEqualsWithDelta(273.15, (float) $kelvin->value(), 0.01);

        // 212°F = 373.15 K (boiling point)
        $kelvinBoiling = $rule->toBase(NumberFactory::create('212'));
        self::assertEqualsWithDelta(373.15, (float) $kelvinBoiling->value(), 0.01);
    }

    public function testGetFactor(): void
    {
        $factor = NumberFactory::create('1000');
        $rule = ConversionRule::factor($factor);

        self::assertSame($factor->value(), $rule->getFactor()->value());
    }

    public function testGetOffset(): void
    {
        $factor = NumberFactory::create('1');
        $offset = NumberFactory::create('273.15');
        $rule = ConversionRule::factor($factor, $offset);

        self::assertSame($offset->value(), $rule->getOffset()->value());
    }

    public function testFactorOnlyRuleOffsetIsZero(): void
    {
        $rule = ConversionRule::factor(NumberFactory::create('1000'));

        self::assertTrue($rule->getOffset()->isZero());
    }
}
