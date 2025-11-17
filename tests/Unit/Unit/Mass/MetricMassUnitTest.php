<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Unit\Mass;

use Andante\Measurement\Dimension\Mass;
use Andante\Measurement\Unit\Mass\MetricMassUnit;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;
use PHPUnit\Framework\TestCase;

final class MetricMassUnitTest extends TestCase
{
    public function testKilogramSymbol(): void
    {
        self::assertSame('kg', MetricMassUnit::Kilogram->symbol());
    }

    public function testGramSymbol(): void
    {
        self::assertSame('g', MetricMassUnit::Gram->symbol());
    }

    public function testMilligramSymbol(): void
    {
        self::assertSame('mg', MetricMassUnit::Milligram->symbol());
    }

    public function testMicrogramSymbolDefault(): void
    {
        self::assertSame('μg', MetricMassUnit::Microgram->symbol());
    }

    public function testMicrogramSymbolASCII(): void
    {
        self::assertSame('ug', MetricMassUnit::Microgram->symbol(SymbolNotation::ASCII));
    }

    public function testTonneSymbol(): void
    {
        self::assertSame('t', MetricMassUnit::Tonne->symbol());
    }

    public function testKilogramName(): void
    {
        self::assertSame('kilogram', MetricMassUnit::Kilogram->name());
    }

    public function testGramName(): void
    {
        self::assertSame('gram', MetricMassUnit::Gram->name());
    }

    public function testMilligramName(): void
    {
        self::assertSame('milligram', MetricMassUnit::Milligram->name());
    }

    public function testMicrogramName(): void
    {
        self::assertSame('microgram', MetricMassUnit::Microgram->name());
    }

    public function testTonneName(): void
    {
        self::assertSame('tonne', MetricMassUnit::Tonne->name());
    }

    public function testDimensionReturnsMassInstance(): void
    {
        $dimension = MetricMassUnit::Kilogram->dimension();

        self::assertInstanceOf(Mass::class, $dimension);
        self::assertSame(Mass::instance(), $dimension);
    }

    public function testAllCasesReturnSameDimensionInstance(): void
    {
        $kilogramDimension = MetricMassUnit::Kilogram->dimension();
        $gramDimension = MetricMassUnit::Gram->dimension();
        $milligramDimension = MetricMassUnit::Milligram->dimension();

        self::assertSame($kilogramDimension, $gramDimension);
        self::assertSame($kilogramDimension, $milligramDimension);
    }

    public function testSystemReturnsMetric(): void
    {
        self::assertSame(UnitSystem::Metric, MetricMassUnit::Kilogram->system());
        self::assertSame(UnitSystem::Metric, MetricMassUnit::Gram->system());
        self::assertSame(UnitSystem::Metric, MetricMassUnit::Milligram->system());
        self::assertSame(UnitSystem::Metric, MetricMassUnit::Tonne->system());
    }

    public function testEnumHasNineCases(): void
    {
        $cases = MetricMassUnit::cases();

        self::assertCount(9, $cases);
        self::assertContains(MetricMassUnit::Kilogram, $cases);
        self::assertContains(MetricMassUnit::Gram, $cases);
        self::assertContains(MetricMassUnit::Milligram, $cases);
        self::assertContains(MetricMassUnit::Microgram, $cases);
        self::assertContains(MetricMassUnit::Tonne, $cases);
        self::assertContains(MetricMassUnit::Hectogram, $cases);
        self::assertContains(MetricMassUnit::Decagram, $cases);
        self::assertContains(MetricMassUnit::Decigram, $cases);
        self::assertContains(MetricMassUnit::Centigram, $cases);
    }
}
