<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Illuminance;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Illuminance\Illuminance;
use Andante\Measurement\Quantity\Illuminance\Imperial\FootCandle;
use Andante\Measurement\Quantity\Illuminance\SI\Kilolux;
use Andante\Measurement\Quantity\Illuminance\SI\Lux;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Illuminance\IlluminanceUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for illuminance arithmetic operations.
 */
final class IlluminanceArithmeticTest extends TestCase
{
    protected function setUp(): void
    {
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
    }

    protected function tearDown(): void
    {
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
    }

    public function testAddSameUnit(): void
    {
        $lux1 = Lux::of(NumberFactory::create('200'));
        $lux2 = Lux::of(NumberFactory::create('300'));

        $result = $lux1->add($lux2);

        self::assertEqualsWithDelta(500.0, (float) $result->getValue()->value(), 0.001);
        self::assertSame(IlluminanceUnit::Lux, $result->getUnit());
    }

    public function testAddDifferentUnits(): void
    {
        $lux = Lux::of(NumberFactory::create('1000'));
        $kilolux = Kilolux::of(NumberFactory::create('1'));

        $result = $lux->add($kilolux);

        self::assertEqualsWithDelta(2000.0, (float) $result->getValue()->value(), 0.001);
        self::assertSame(IlluminanceUnit::Lux, $result->getUnit());
    }

    public function testAddImperialToMetric(): void
    {
        $lux = Lux::of(NumberFactory::create('100'));
        $footCandle = FootCandle::of(NumberFactory::create('10')); // ~107.64 lux

        $result = $lux->add($footCandle);

        self::assertEqualsWithDelta(207.64, (float) $result->getValue()->value(), 0.01);
        self::assertSame(IlluminanceUnit::Lux, $result->getUnit());
    }

    public function testSubtractSameUnit(): void
    {
        $lux1 = Lux::of(NumberFactory::create('500'));
        $lux2 = Lux::of(NumberFactory::create('200'));

        $result = $lux1->subtract($lux2);

        self::assertEqualsWithDelta(300.0, (float) $result->getValue()->value(), 0.001);
        self::assertSame(IlluminanceUnit::Lux, $result->getUnit());
    }

    public function testSubtractDifferentUnits(): void
    {
        $kilolux = Kilolux::of(NumberFactory::create('2'));
        $lux = Lux::of(NumberFactory::create('500'));

        $result = $kilolux->subtract($lux);

        self::assertEqualsWithDelta(1.5, (float) $result->getValue()->value(), 0.001);
        self::assertSame(IlluminanceUnit::Kilolux, $result->getUnit());
    }

    public function testMultiplyByScalar(): void
    {
        $lux = Lux::of(NumberFactory::create('250'));

        $result = $lux->multiplyBy(NumberFactory::create('4'));

        self::assertEqualsWithDelta(1000.0, (float) $result->getValue()->value(), 0.001);
        self::assertSame(IlluminanceUnit::Lux, $result->getUnit());
    }

    public function testDivideByScalar(): void
    {
        $lux = Lux::of(NumberFactory::create('1000'));

        $result = $lux->divideBy(NumberFactory::create('4'));

        self::assertEqualsWithDelta(250.0, (float) $result->getValue()->value(), 0.001);
        self::assertSame(IlluminanceUnit::Lux, $result->getUnit());
    }

    public function testNegate(): void
    {
        $lux = Lux::of(NumberFactory::create('500'));

        $result = $lux->negate();

        self::assertEqualsWithDelta(-500.0, (float) $result->getValue()->value(), 0.001);
        self::assertSame(IlluminanceUnit::Lux, $result->getUnit());
    }

    public function testAbs(): void
    {
        $lux = Illuminance::of(
            NumberFactory::create('-500'),
            IlluminanceUnit::Lux,
        );

        $result = $lux->abs();

        self::assertEqualsWithDelta(500.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testAdditionAcrossUnits(): void
    {
        // 1 klx + 500 lx = 1.5 klx
        $kilolux = Kilolux::of(NumberFactory::create('1'));
        $lux = Lux::of(NumberFactory::create('500'));

        $sum = $kilolux->add($lux);

        // Result is in klx (first operand's unit)
        self::assertEqualsWithDelta(1.5, (float) $sum->getValue()->value(), 0.001);
    }
}
