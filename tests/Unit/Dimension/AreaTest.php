<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Dimension;

use Andante\Measurement\Dimension\Area;
use PHPUnit\Framework\TestCase;

final class AreaTest extends TestCase
{
    public function testInstanceReturnsSingleton(): void
    {
        $instance1 = Area::instance();
        $instance2 = Area::instance();

        self::assertSame($instance1, $instance2);
    }

    public function testGetName(): void
    {
        self::assertSame('Area', Area::instance()->getName());
    }

    public function testGetSymbol(): void
    {
        self::assertSame('L²', Area::instance()->getSymbol());
    }

    public function testGetFormulaReturnsSameCachedInstance(): void
    {
        $formula1 = Area::instance()->getFormula();
        $formula2 = Area::instance()->getFormula();

        self::assertSame($formula1, $formula2);
    }

    public function testFormulaHasLengthExponentTwo(): void
    {
        $formula = Area::instance()->getFormula();

        self::assertSame(2, $formula->length);
        self::assertSame(0, $formula->mass);
        self::assertSame(0, $formula->time);
        self::assertSame(0, $formula->electricCurrent);
        self::assertSame(0, $formula->temperature);
        self::assertSame(0, $formula->amountOfSubstance);
        self::assertSame(0, $formula->luminousIntensity);
    }

    public function testIsCompatibleWithSameDimension(): void
    {
        $area1 = Area::instance();
        $area2 = Area::instance();

        self::assertTrue($area1->isCompatibleWith($area2));
    }

    public function testIsDimensionlessReturnsFalse(): void
    {
        self::assertFalse(Area::instance()->isDimensionless());
    }
}
