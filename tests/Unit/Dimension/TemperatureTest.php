<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Dimension;

use Andante\Measurement\Dimension\Temperature;
use PHPUnit\Framework\TestCase;

final class TemperatureTest extends TestCase
{
    public function testInstanceReturnsSingleton(): void
    {
        $instance1 = Temperature::instance();
        $instance2 = Temperature::instance();

        self::assertSame($instance1, $instance2);
    }

    public function testGetName(): void
    {
        self::assertSame('Temperature', Temperature::instance()->getName());
    }

    public function testGetSymbol(): void
    {
        self::assertSame('Θ', Temperature::instance()->getSymbol());
    }

    public function testGetFormulaReturnsSameCachedInstance(): void
    {
        $formula1 = Temperature::instance()->getFormula();
        $formula2 = Temperature::instance()->getFormula();

        self::assertSame($formula1, $formula2);
    }

    public function testFormulaHasTemperatureExponentOne(): void
    {
        $formula = Temperature::instance()->getFormula();

        self::assertSame(0, $formula->length);
        self::assertSame(0, $formula->mass);
        self::assertSame(0, $formula->time);
        self::assertSame(0, $formula->electricCurrent);
        self::assertSame(1, $formula->temperature);
        self::assertSame(0, $formula->amountOfSubstance);
        self::assertSame(0, $formula->luminousIntensity);
        self::assertSame(0, $formula->digital);
    }

    public function testIsCompatibleWithSameDimension(): void
    {
        $temp1 = Temperature::instance();
        $temp2 = Temperature::instance();

        self::assertTrue($temp1->isCompatibleWith($temp2));
    }

    public function testIsDimensionlessReturnsFalse(): void
    {
        self::assertFalse(Temperature::instance()->isDimensionless());
    }
}
