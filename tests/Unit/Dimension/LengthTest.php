<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Dimension;

use Andante\Measurement\Dimension\Length;
use PHPUnit\Framework\TestCase;

final class LengthTest extends TestCase
{
    public function testInstanceReturnsSingleton(): void
    {
        $instance1 = Length::instance();
        $instance2 = Length::instance();

        self::assertSame($instance1, $instance2);
    }

    public function testGetName(): void
    {
        self::assertSame('Length', Length::instance()->getName());
    }

    public function testGetSymbol(): void
    {
        self::assertSame('L', Length::instance()->getSymbol());
    }

    public function testGetFormulaReturnsSameCachedInstance(): void
    {
        $formula1 = Length::instance()->getFormula();
        $formula2 = Length::instance()->getFormula();

        self::assertSame($formula1, $formula2);
    }

    public function testFormulaHasLengthExponentOne(): void
    {
        $formula = Length::instance()->getFormula();

        self::assertSame(1, $formula->length);
        self::assertSame(0, $formula->mass);
        self::assertSame(0, $formula->time);
        self::assertSame(0, $formula->electricCurrent);
        self::assertSame(0, $formula->temperature);
        self::assertSame(0, $formula->amountOfSubstance);
        self::assertSame(0, $formula->luminousIntensity);
    }

    public function testIsCompatibleWithSameDimension(): void
    {
        $length1 = Length::instance();
        $length2 = Length::instance();

        self::assertTrue($length1->isCompatibleWith($length2));
    }

    public function testIsDimensionlessReturnsFalse(): void
    {
        self::assertFalse(Length::instance()->isDimensionless());
    }
}
