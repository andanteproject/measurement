<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Unit\Area;

use Andante\Measurement\Dimension\Area;
use Andante\Measurement\Unit\Area\ImperialAreaUnit;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;
use PHPUnit\Framework\TestCase;

final class ImperialAreaUnitTest extends TestCase
{
    public function testSquareFootSymbol(): void
    {
        self::assertSame('ft²', ImperialAreaUnit::SquareFoot->symbol());
    }

    public function testSquareFootSymbolASCII(): void
    {
        self::assertSame('ft2', ImperialAreaUnit::SquareFoot->symbol(SymbolNotation::ASCII));
    }

    public function testSquareInchSymbol(): void
    {
        self::assertSame('in²', ImperialAreaUnit::SquareInch->symbol());
    }

    public function testSquareYardSymbol(): void
    {
        self::assertSame('yd²', ImperialAreaUnit::SquareYard->symbol());
    }

    public function testSquareMileSymbol(): void
    {
        self::assertSame('mi²', ImperialAreaUnit::SquareMile->symbol());
    }

    public function testAcreSymbol(): void
    {
        self::assertSame('ac', ImperialAreaUnit::Acre->symbol());
    }

    public function testSquareFootName(): void
    {
        self::assertSame('square foot', ImperialAreaUnit::SquareFoot->name());
    }

    public function testSquareInchName(): void
    {
        self::assertSame('square inch', ImperialAreaUnit::SquareInch->name());
    }

    public function testSquareYardName(): void
    {
        self::assertSame('square yard', ImperialAreaUnit::SquareYard->name());
    }

    public function testSquareMileName(): void
    {
        self::assertSame('square mile', ImperialAreaUnit::SquareMile->name());
    }

    public function testAcreName(): void
    {
        self::assertSame('acre', ImperialAreaUnit::Acre->name());
    }

    public function testDimensionReturnsAreaInstance(): void
    {
        $dimension = ImperialAreaUnit::SquareFoot->dimension();

        self::assertInstanceOf(Area::class, $dimension);
        self::assertSame(Area::instance(), $dimension);
    }

    public function testAllCasesReturnSameDimensionInstance(): void
    {
        $squareFootDimension = ImperialAreaUnit::SquareFoot->dimension();
        $acreDimension = ImperialAreaUnit::Acre->dimension();
        $squareMileDimension = ImperialAreaUnit::SquareMile->dimension();

        self::assertSame($squareFootDimension, $acreDimension);
        self::assertSame($squareFootDimension, $squareMileDimension);
    }

    public function testSystemReturnsImperial(): void
    {
        self::assertSame(UnitSystem::Imperial, ImperialAreaUnit::SquareFoot->system());
        self::assertSame(UnitSystem::Imperial, ImperialAreaUnit::SquareInch->system());
        self::assertSame(UnitSystem::Imperial, ImperialAreaUnit::SquareYard->system());
        self::assertSame(UnitSystem::Imperial, ImperialAreaUnit::SquareMile->system());
        self::assertSame(UnitSystem::Imperial, ImperialAreaUnit::Acre->system());
    }

    public function testEnumHasFiveCases(): void
    {
        $cases = ImperialAreaUnit::cases();

        self::assertCount(5, $cases);
        self::assertContains(ImperialAreaUnit::SquareFoot, $cases);
        self::assertContains(ImperialAreaUnit::SquareInch, $cases);
        self::assertContains(ImperialAreaUnit::SquareYard, $cases);
        self::assertContains(ImperialAreaUnit::SquareMile, $cases);
        self::assertContains(ImperialAreaUnit::Acre, $cases);
    }
}
