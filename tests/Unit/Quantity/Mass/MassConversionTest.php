<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Mass;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Mass\Imperial\Ounce;
use Andante\Measurement\Quantity\Mass\Imperial\Pound;
use Andante\Measurement\Quantity\Mass\Imperial\Stone;
use Andante\Measurement\Quantity\Mass\Metric\Gram;
use Andante\Measurement\Quantity\Mass\Metric\Kilogram;
use Andante\Measurement\Quantity\Mass\Metric\Tonne;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Mass\ImperialMassUnit;
use Andante\Measurement\Unit\Mass\MetricMassUnit;
use PHPUnit\Framework\TestCase;

final class MassConversionTest extends TestCase
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

    public function testKilogramsToGrams(): void
    {
        $kilograms = Kilogram::of(NumberFactory::create(2));

        $grams = $kilograms->to(MetricMassUnit::Gram);

        self::assertEqualsWithDelta(2000.0, (float) $grams->getValue()->value(), 0.0001);
    }

    public function testGramsToKilograms(): void
    {
        $grams = Gram::of(NumberFactory::create(1500));

        $kilograms = $grams->to(MetricMassUnit::Kilogram);

        self::assertEqualsWithDelta(1.5, (float) $kilograms->getValue()->value(), 0.0001);
    }

    public function testGramsToMilligrams(): void
    {
        $grams = Gram::of(NumberFactory::create(5));

        $milligrams = $grams->to(MetricMassUnit::Milligram);

        self::assertEqualsWithDelta(5000.0, (float) $milligrams->getValue()->value(), 0.0001);
    }

    public function testTonnesToKilograms(): void
    {
        $tonnes = Tonne::of(NumberFactory::create(2));

        $kilograms = $tonnes->to(MetricMassUnit::Kilogram);

        self::assertEqualsWithDelta(2000.0, (float) $kilograms->getValue()->value(), 0.0001);
    }

    public function testPoundsToKilograms(): void
    {
        $pounds = Pound::of(NumberFactory::create(10));

        $kilograms = $pounds->to(MetricMassUnit::Kilogram);

        // 10 pounds = 4.5359237 kg
        self::assertEqualsWithDelta(4.5359237, (float) $kilograms->getValue()->value(), 0.0001);
    }

    public function testKilogramsToPounds(): void
    {
        $kilograms = Kilogram::of(NumberFactory::create(1));

        $pounds = $kilograms->to(ImperialMassUnit::Pound);

        // 1 kg = 2.20462 lb (approximately)
        self::assertEqualsWithDelta(2.20462, (float) $pounds->getValue()->value(), 0.001);
    }

    public function testOuncesToGrams(): void
    {
        $ounces = Ounce::of(NumberFactory::create(8));

        $grams = $ounces->to(MetricMassUnit::Gram);

        // 8 ounces = 226.796 grams (approximately)
        self::assertEqualsWithDelta(226.796, (float) $grams->getValue()->value(), 0.01);
    }

    public function testPoundsToOunces(): void
    {
        $pounds = Pound::of(NumberFactory::create(1));

        $ounces = $pounds->to(ImperialMassUnit::Ounce);

        // 1 pound = 16 ounces
        self::assertEqualsWithDelta(16.0, (float) $ounces->getValue()->value(), 0.001);
    }

    public function testStoneToPounds(): void
    {
        $stone = Stone::of(NumberFactory::create(1));

        $pounds = $stone->to(ImperialMassUnit::Pound);

        // 1 stone = 14 pounds
        self::assertEqualsWithDelta(14.0, (float) $pounds->getValue()->value(), 0.0001);
    }

    public function testStoneToKilograms(): void
    {
        $stone = Stone::of(NumberFactory::create(1));

        $kilograms = $stone->to(MetricMassUnit::Kilogram);

        // 1 stone = 6.35029318 kg
        self::assertEqualsWithDelta(6.35029318, (float) $kilograms->getValue()->value(), 0.0001);
    }
}
