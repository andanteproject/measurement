<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Area;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Area\Imperial\Acre;
use Andante\Measurement\Quantity\Area\Imperial\SquareFoot;
use Andante\Measurement\Quantity\Area\Imperial\SquareYard;
use Andante\Measurement\Quantity\Area\Metric\Are;
use Andante\Measurement\Quantity\Area\Metric\Hectare;
use Andante\Measurement\Quantity\Area\Metric\SquareKilometer;
use Andante\Measurement\Quantity\Area\Metric\SquareMeter;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Area\ImperialAreaUnit;
use Andante\Measurement\Unit\Area\MetricAreaUnit;
use PHPUnit\Framework\TestCase;

final class AreaConversionTest extends TestCase
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

    public function testSquareMetersToSquareKilometers(): void
    {
        $squareMeters = SquareMeter::of(NumberFactory::create(1000000));

        $squareKilometers = $squareMeters->to(MetricAreaUnit::SquareKilometer);

        self::assertEqualsWithDelta(1.0, (float) $squareKilometers->getValue()->value(), 0.0001);
    }

    public function testSquareKilometersToSquareMeters(): void
    {
        $squareKilometers = SquareKilometer::of(NumberFactory::create(2));

        $squareMeters = $squareKilometers->to(MetricAreaUnit::SquareMeter);

        self::assertEqualsWithDelta(2000000.0, (float) $squareMeters->getValue()->value(), 0.0001);
    }

    public function testSquareMetersToSquareCentimeters(): void
    {
        $squareMeters = SquareMeter::of(NumberFactory::create(5));

        $squareCentimeters = $squareMeters->to(MetricAreaUnit::SquareCentimeter);

        self::assertEqualsWithDelta(50000.0, (float) $squareCentimeters->getValue()->value(), 0.0001);
    }

    public function testSquareMetersToSquareMillimeters(): void
    {
        $squareMeters = SquareMeter::of(NumberFactory::create(1));

        $squareMillimeters = $squareMeters->to(MetricAreaUnit::SquareMillimeter);

        self::assertEqualsWithDelta(1000000.0, (float) $squareMillimeters->getValue()->value(), 0.0001);
    }

    public function testHectareToSquareMeters(): void
    {
        $hectares = Hectare::of(NumberFactory::create(1));

        $squareMeters = $hectares->to(MetricAreaUnit::SquareMeter);

        self::assertEqualsWithDelta(10000.0, (float) $squareMeters->getValue()->value(), 0.0001);
    }

    public function testSquareMetersToHectare(): void
    {
        $squareMeters = SquareMeter::of(NumberFactory::create(50000));

        $hectares = $squareMeters->to(MetricAreaUnit::Hectare);

        self::assertEqualsWithDelta(5.0, (float) $hectares->getValue()->value(), 0.0001);
    }

    public function testAreToSquareMeters(): void
    {
        $ares = Are::of(NumberFactory::create(10));

        $squareMeters = $ares->to(MetricAreaUnit::SquareMeter);

        self::assertEqualsWithDelta(1000.0, (float) $squareMeters->getValue()->value(), 0.0001);
    }

    public function testHectareToAre(): void
    {
        $hectares = Hectare::of(NumberFactory::create(1));

        $ares = $hectares->to(MetricAreaUnit::Are);

        self::assertEqualsWithDelta(100.0, (float) $ares->getValue()->value(), 0.0001);
    }

    public function testSquareFeetToSquareMeters(): void
    {
        $squareFeet = SquareFoot::of(NumberFactory::create(100));

        $squareMeters = $squareFeet->to(MetricAreaUnit::SquareMeter);

        // 100 ft² = 9.290304 m²
        self::assertEqualsWithDelta(9.290304, (float) $squareMeters->getValue()->value(), 0.0001);
    }

    public function testSquareMetersToSquareFeet(): void
    {
        $squareMeters = SquareMeter::of(NumberFactory::create(1));

        $squareFeet = $squareMeters->to(ImperialAreaUnit::SquareFoot);

        // 1 m² = 10.7639 ft² (approximately)
        self::assertEqualsWithDelta(10.7639, (float) $squareFeet->getValue()->value(), 0.001);
    }

    public function testSquareYardToSquareFeet(): void
    {
        $squareYards = SquareYard::of(NumberFactory::create(1));

        $squareFeet = $squareYards->to(ImperialAreaUnit::SquareFoot);

        // 1 yd² = 9 ft²
        self::assertEqualsWithDelta(9.0, (float) $squareFeet->getValue()->value(), 0.0001);
    }

    public function testSquareFeetToSquareInches(): void
    {
        $squareFeet = SquareFoot::of(NumberFactory::create(1));

        $squareInches = $squareFeet->to(ImperialAreaUnit::SquareInch);

        // 1 ft² = 144 in²
        self::assertEqualsWithDelta(144.0, (float) $squareInches->getValue()->value(), 0.001);
    }

    public function testAcreToSquareFeet(): void
    {
        $acres = Acre::of(NumberFactory::create(1));

        $squareFeet = $acres->to(ImperialAreaUnit::SquareFoot);

        // 1 acre = 43,560 ft²
        self::assertEqualsWithDelta(43560.0, (float) $squareFeet->getValue()->value(), 0.1);
    }

    public function testAcreToSquareMeters(): void
    {
        $acres = Acre::of(NumberFactory::create(1));

        $squareMeters = $acres->to(MetricAreaUnit::SquareMeter);

        // 1 acre = 4046.8564224 m²
        self::assertEqualsWithDelta(4046.8564224, (float) $squareMeters->getValue()->value(), 0.001);
    }

    public function testAcreToHectare(): void
    {
        $acres = Acre::of(NumberFactory::create(1));

        $hectares = $acres->to(MetricAreaUnit::Hectare);

        // 1 acre = 0.40469 hectares (approximately)
        self::assertEqualsWithDelta(0.40469, (float) $hectares->getValue()->value(), 0.0001);
    }

    public function testHectareToAcre(): void
    {
        $hectares = Hectare::of(NumberFactory::create(1));

        $acres = $hectares->to(ImperialAreaUnit::Acre);

        // 1 hectare = 2.47105 acres (approximately)
        self::assertEqualsWithDelta(2.47105, (float) $acres->getValue()->value(), 0.001);
    }
}
