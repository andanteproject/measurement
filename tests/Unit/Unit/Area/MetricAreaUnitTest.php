<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Unit\Area;

use Andante\Measurement\Dimension\Area;
use Andante\Measurement\Unit\Area\MetricAreaUnit;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;
use PHPUnit\Framework\TestCase;

final class MetricAreaUnitTest extends TestCase
{
    public function testSquareMeterSymbol(): void
    {
        self::assertSame('m²', MetricAreaUnit::SquareMeter->symbol());
    }

    public function testSquareMeterSymbolASCII(): void
    {
        self::assertSame('m2', MetricAreaUnit::SquareMeter->symbol(SymbolNotation::ASCII));
    }

    public function testSquareKilometerSymbol(): void
    {
        self::assertSame('km²', MetricAreaUnit::SquareKilometer->symbol());
    }

    public function testSquareKilometerSymbolASCII(): void
    {
        self::assertSame('km2', MetricAreaUnit::SquareKilometer->symbol(SymbolNotation::ASCII));
    }

    public function testSquareCentimeterSymbol(): void
    {
        self::assertSame('cm²', MetricAreaUnit::SquareCentimeter->symbol());
    }

    public function testSquareMillimeterSymbol(): void
    {
        self::assertSame('mm²', MetricAreaUnit::SquareMillimeter->symbol());
    }

    public function testSquareDecimeterSymbol(): void
    {
        self::assertSame('dm²', MetricAreaUnit::SquareDecimeter->symbol());
    }

    public function testHectareSymbol(): void
    {
        self::assertSame('ha', MetricAreaUnit::Hectare->symbol());
    }

    public function testAreSymbol(): void
    {
        self::assertSame('a', MetricAreaUnit::Are->symbol());
    }

    public function testSquareMeterName(): void
    {
        self::assertSame('square meter', MetricAreaUnit::SquareMeter->name());
    }

    public function testSquareKilometerName(): void
    {
        self::assertSame('square kilometer', MetricAreaUnit::SquareKilometer->name());
    }

    public function testHectareName(): void
    {
        self::assertSame('hectare', MetricAreaUnit::Hectare->name());
    }

    public function testAreName(): void
    {
        self::assertSame('are', MetricAreaUnit::Are->name());
    }

    public function testDimensionReturnsAreaInstance(): void
    {
        $dimension = MetricAreaUnit::SquareMeter->dimension();

        self::assertInstanceOf(Area::class, $dimension);
        self::assertSame(Area::instance(), $dimension);
    }

    public function testAllCasesReturnSameDimensionInstance(): void
    {
        $squareMeterDimension = MetricAreaUnit::SquareMeter->dimension();
        $hectareDimension = MetricAreaUnit::Hectare->dimension();
        $areDimension = MetricAreaUnit::Are->dimension();

        self::assertSame($squareMeterDimension, $hectareDimension);
        self::assertSame($squareMeterDimension, $areDimension);
    }

    public function testSystemReturnsMetric(): void
    {
        self::assertSame(UnitSystem::Metric, MetricAreaUnit::SquareMeter->system());
        self::assertSame(UnitSystem::Metric, MetricAreaUnit::SquareKilometer->system());
        self::assertSame(UnitSystem::Metric, MetricAreaUnit::SquareCentimeter->system());
        self::assertSame(UnitSystem::Metric, MetricAreaUnit::SquareMillimeter->system());
        self::assertSame(UnitSystem::Metric, MetricAreaUnit::SquareDecimeter->system());
        self::assertSame(UnitSystem::Metric, MetricAreaUnit::Hectare->system());
        self::assertSame(UnitSystem::Metric, MetricAreaUnit::Are->system());
    }

    public function testEnumHasSevenCases(): void
    {
        $cases = MetricAreaUnit::cases();

        self::assertCount(7, $cases);
        self::assertContains(MetricAreaUnit::SquareMeter, $cases);
        self::assertContains(MetricAreaUnit::SquareKilometer, $cases);
        self::assertContains(MetricAreaUnit::SquareCentimeter, $cases);
        self::assertContains(MetricAreaUnit::SquareMillimeter, $cases);
        self::assertContains(MetricAreaUnit::SquareDecimeter, $cases);
        self::assertContains(MetricAreaUnit::Hectare, $cases);
        self::assertContains(MetricAreaUnit::Are, $cases);
    }
}
