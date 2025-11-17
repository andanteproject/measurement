<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Unit\Mass;

use Andante\Measurement\Dimension\Mass;
use Andante\Measurement\Unit\Mass\ImperialMassUnit;
use Andante\Measurement\Unit\UnitSystem;
use PHPUnit\Framework\TestCase;

final class ImperialMassUnitTest extends TestCase
{
    public function testPoundSymbol(): void
    {
        self::assertSame('lb', ImperialMassUnit::Pound->symbol());
    }

    public function testOunceSymbol(): void
    {
        self::assertSame('oz', ImperialMassUnit::Ounce->symbol());
    }

    public function testStoneSymbol(): void
    {
        self::assertSame('st', ImperialMassUnit::Stone->symbol());
    }

    public function testShortTonSymbol(): void
    {
        self::assertSame('ton', ImperialMassUnit::ShortTon->symbol());
    }

    public function testLongTonSymbol(): void
    {
        self::assertSame('long ton', ImperialMassUnit::LongTon->symbol());
    }

    public function testPoundName(): void
    {
        self::assertSame('pound', ImperialMassUnit::Pound->name());
    }

    public function testOunceName(): void
    {
        self::assertSame('ounce', ImperialMassUnit::Ounce->name());
    }

    public function testStoneName(): void
    {
        self::assertSame('stone', ImperialMassUnit::Stone->name());
    }

    public function testShortTonName(): void
    {
        self::assertSame('short ton', ImperialMassUnit::ShortTon->name());
    }

    public function testLongTonName(): void
    {
        self::assertSame('long ton', ImperialMassUnit::LongTon->name());
    }

    public function testDimensionReturnsMassInstance(): void
    {
        $dimension = ImperialMassUnit::Pound->dimension();

        self::assertInstanceOf(Mass::class, $dimension);
        self::assertSame(Mass::instance(), $dimension);
    }

    public function testAllCasesReturnSameDimensionInstance(): void
    {
        $poundDimension = ImperialMassUnit::Pound->dimension();
        $ounceDimension = ImperialMassUnit::Ounce->dimension();
        $stoneDimension = ImperialMassUnit::Stone->dimension();

        self::assertSame($poundDimension, $ounceDimension);
        self::assertSame($poundDimension, $stoneDimension);
    }

    public function testSystemReturnsImperial(): void
    {
        self::assertSame(UnitSystem::Imperial, ImperialMassUnit::Pound->system());
        self::assertSame(UnitSystem::Imperial, ImperialMassUnit::Ounce->system());
        self::assertSame(UnitSystem::Imperial, ImperialMassUnit::Stone->system());
        self::assertSame(UnitSystem::Imperial, ImperialMassUnit::ShortTon->system());
        self::assertSame(UnitSystem::Imperial, ImperialMassUnit::LongTon->system());
    }

    public function testEnumHasFiveCases(): void
    {
        $cases = ImperialMassUnit::cases();

        self::assertCount(5, $cases);
        self::assertContains(ImperialMassUnit::Pound, $cases);
        self::assertContains(ImperialMassUnit::Ounce, $cases);
        self::assertContains(ImperialMassUnit::Stone, $cases);
        self::assertContains(ImperialMassUnit::ShortTon, $cases);
        self::assertContains(ImperialMassUnit::LongTon, $cases);
    }
}
