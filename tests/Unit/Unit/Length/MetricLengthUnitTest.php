<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Unit\Length;

use Andante\Measurement\Dimension\Length;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use Andante\Measurement\Unit\UnitSystem;
use PHPUnit\Framework\TestCase;

final class MetricLengthUnitTest extends TestCase
{
    public function testMeterSymbol(): void
    {
        self::assertSame('m', MetricLengthUnit::Meter->symbol());
    }

    public function testKilometerSymbol(): void
    {
        self::assertSame('km', MetricLengthUnit::Kilometer->symbol());
    }

    public function testCentimeterSymbol(): void
    {
        self::assertSame('cm', MetricLengthUnit::Centimeter->symbol());
    }

    public function testMillimeterSymbol(): void
    {
        self::assertSame('mm', MetricLengthUnit::Millimeter->symbol());
    }

    public function testMeterName(): void
    {
        self::assertSame('meter', MetricLengthUnit::Meter->name());
    }

    public function testKilometerName(): void
    {
        self::assertSame('kilometer', MetricLengthUnit::Kilometer->name());
    }

    public function testCentimeterName(): void
    {
        self::assertSame('centimeter', MetricLengthUnit::Centimeter->name());
    }

    public function testMillimeterName(): void
    {
        self::assertSame('millimeter', MetricLengthUnit::Millimeter->name());
    }

    public function testDimensionReturnsLengthInstance(): void
    {
        $dimension = MetricLengthUnit::Meter->dimension();

        self::assertInstanceOf(Length::class, $dimension);
        self::assertSame(Length::instance(), $dimension);
    }

    public function testAllCasesReturnSameDimensionInstance(): void
    {
        $meterDimension = MetricLengthUnit::Meter->dimension();
        $kilometerDimension = MetricLengthUnit::Kilometer->dimension();
        $centimeterDimension = MetricLengthUnit::Centimeter->dimension();

        self::assertSame($meterDimension, $kilometerDimension);
        self::assertSame($meterDimension, $centimeterDimension);
    }

    public function testSystemReturnsMetric(): void
    {
        self::assertSame(UnitSystem::Metric, MetricLengthUnit::Meter->system());
        self::assertSame(UnitSystem::Metric, MetricLengthUnit::Kilometer->system());
        self::assertSame(UnitSystem::Metric, MetricLengthUnit::Centimeter->system());
        self::assertSame(UnitSystem::Metric, MetricLengthUnit::Millimeter->system());
    }

    public function testEnumHasNineCases(): void
    {
        $cases = MetricLengthUnit::cases();

        self::assertCount(9, $cases);
        self::assertContains(MetricLengthUnit::Meter, $cases);
        self::assertContains(MetricLengthUnit::Kilometer, $cases);
        self::assertContains(MetricLengthUnit::Hectometer, $cases);
        self::assertContains(MetricLengthUnit::Decameter, $cases);
        self::assertContains(MetricLengthUnit::Decimeter, $cases);
        self::assertContains(MetricLengthUnit::Centimeter, $cases);
        self::assertContains(MetricLengthUnit::Millimeter, $cases);
        self::assertContains(MetricLengthUnit::Micrometer, $cases);
        self::assertContains(MetricLengthUnit::Nanometer, $cases);
    }
}
