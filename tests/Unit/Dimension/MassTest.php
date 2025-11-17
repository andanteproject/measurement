<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Dimension;

use Andante\Measurement\Dimension\Mass;
use PHPUnit\Framework\TestCase;

final class MassTest extends TestCase
{
    public function testInstanceReturnsSingleton(): void
    {
        $instance1 = Mass::instance();
        $instance2 = Mass::instance();

        self::assertSame($instance1, $instance2);
    }

    public function testGetName(): void
    {
        self::assertSame('Mass', Mass::instance()->getName());
    }

    public function testGetSymbol(): void
    {
        self::assertSame('M', Mass::instance()->getSymbol());
    }

    public function testGetFormulaReturnsSameCachedInstance(): void
    {
        $formula1 = Mass::instance()->getFormula();
        $formula2 = Mass::instance()->getFormula();

        self::assertSame($formula1, $formula2);
    }

    public function testFormulaHasMassExponentOne(): void
    {
        $formula = Mass::instance()->getFormula();

        self::assertSame(0, $formula->length);
        self::assertSame(1, $formula->mass);
        self::assertSame(0, $formula->time);
        self::assertSame(0, $formula->electricCurrent);
        self::assertSame(0, $formula->temperature);
        self::assertSame(0, $formula->amountOfSubstance);
        self::assertSame(0, $formula->luminousIntensity);
    }

    public function testIsCompatibleWithSameDimension(): void
    {
        $mass1 = Mass::instance();
        $mass2 = Mass::instance();

        self::assertTrue($mass1->isCompatibleWith($mass2));
    }

    public function testIsDimensionlessReturnsFalse(): void
    {
        self::assertFalse(Mass::instance()->isDimensionless());
    }
}
