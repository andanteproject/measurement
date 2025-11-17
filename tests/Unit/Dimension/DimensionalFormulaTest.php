<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Dimension;

use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DimensionalFormulaTest extends TestCase
{
    public function testRootSquareOfArea(): void
    {
        $area = new DimensionalFormula(length: 2);

        $length = $area->root(2);

        self::assertSame(1, $length->length);
        self::assertSame(0, $length->mass);
    }

    public function testRootCubeOfVolume(): void
    {
        $volume = new DimensionalFormula(length: 3);

        $length = $volume->root(3);

        self::assertSame(1, $length->length);
    }

    public function testRootSquareOfComplexFormula(): void
    {
        // L⁴M²T⁻² → L²M¹T⁻¹
        $formula = new DimensionalFormula(length: 4, mass: 2, time: -2);

        $result = $formula->root(2);

        self::assertSame(2, $result->length);
        self::assertSame(1, $result->mass);
        self::assertSame(-1, $result->time);
    }

    public function testRootOfDimensionless(): void
    {
        $dimensionless = DimensionalFormula::dimensionless();

        $result = $dimensionless->root(2);

        self::assertTrue($result->isDimensionless());
    }

    public function testRootThrowsExceptionForOddExponent(): void
    {
        $length = DimensionalFormula::length();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot take square root: length exponent 1 is not divisible by 2');

        $length->root(2);
    }

    public function testRootThrowsExceptionForZero(): void
    {
        $formula = new DimensionalFormula(length: 2);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot take zeroth root');

        $formula->root(0);
    }

    public function testRootCubeThrowsExceptionForNonDivisible(): void
    {
        $area = new DimensionalFormula(length: 2);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot take cube root: length exponent 2 is not divisible by 3');

        $area->root(3);
    }

    public function testRootFourth(): void
    {
        // L⁴ → L¹
        $l4 = new DimensionalFormula(length: 4);

        $result = $l4->root(4);

        self::assertSame(1, $result->length);
    }

    public function testPowerAndRootAreInverse(): void
    {
        $length = DimensionalFormula::length();

        $squared = $length->power(2);
        $backToLength = $squared->root(2);

        self::assertTrue($length->equals($backToLength));
    }

    public function testRootWithNegativeExponents(): void
    {
        // T⁻² → T⁻¹
        $formula = new DimensionalFormula(time: -2);

        $result = $formula->root(2);

        self::assertSame(-1, $result->time);
    }

    // Digital pseudo-dimension tests

    public function testDigitalFactory(): void
    {
        $digital = DimensionalFormula::digital();

        self::assertSame(1, $digital->digital);
        self::assertSame(0, $digital->length);
        self::assertSame(0, $digital->time);
    }

    public function testDigitalDividedByTimeGivesDataRate(): void
    {
        // Digital / Time = DataTransferRate [D¹T⁻¹]
        $digital = DimensionalFormula::digital();
        $time = DimensionalFormula::time();

        $dataRate = $digital->divide($time);

        self::assertSame(1, $dataRate->digital);
        self::assertSame(-1, $dataRate->time);
        self::assertSame(0, $dataRate->length);
    }

    public function testDataRateMultipliedByTimeGivesDigital(): void
    {
        // DataTransferRate × Time = Digital [D¹]
        $dataRate = new DimensionalFormula(digital: 1, time: -1);
        $time = DimensionalFormula::time();

        $digital = $dataRate->multiply($time);

        self::assertSame(1, $digital->digital);
        self::assertSame(0, $digital->time);
    }

    public function testDigitalToString(): void
    {
        $digital = DimensionalFormula::digital();
        self::assertSame('[D¹]', $digital->toString());

        $dataRate = new DimensionalFormula(digital: 1, time: -1);
        self::assertSame('[T⁻¹D¹]', $dataRate->toString());
    }

    public function testDigitalInToArray(): void
    {
        $digital = DimensionalFormula::digital();
        $array = $digital->toArray();

        self::assertArrayHasKey('digital', $array);
        self::assertSame(1, $array['digital']);
    }

    public function testDigitalEquals(): void
    {
        $d1 = DimensionalFormula::digital();
        $d2 = new DimensionalFormula(digital: 1);

        self::assertTrue($d1->equals($d2));

        $d3 = new DimensionalFormula(digital: 2);
        self::assertFalse($d1->equals($d3));
    }

    public function testDigitalIsDimensionless(): void
    {
        $digital = DimensionalFormula::digital();
        self::assertFalse($digital->isDimensionless());

        $dimensionless = DimensionalFormula::dimensionless();
        self::assertTrue($dimensionless->isDimensionless());
        self::assertSame(0, $dimensionless->digital);
    }

    public function testDigitalPower(): void
    {
        $digital = DimensionalFormula::digital();

        $squared = $digital->power(2);

        self::assertSame(2, $squared->digital);
    }

    public function testDigitalRoot(): void
    {
        $d2 = new DimensionalFormula(digital: 2);

        $d1 = $d2->root(2);

        self::assertSame(1, $d1->digital);
    }
}
