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
 * Tests for illuminance comparison operations.
 */
final class IlluminanceComparisonTest extends TestCase
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

    public function testEqualsSameUnit(): void
    {
        $lux1 = Lux::of(NumberFactory::create('500'));
        $lux2 = Lux::of(NumberFactory::create('500'));

        self::assertTrue($lux1->equals($lux2));
    }

    public function testEqualsDifferentUnits(): void
    {
        $lux = Lux::of(NumberFactory::create('1000'));
        $kilolux = Kilolux::of(NumberFactory::create('1'));

        self::assertTrue($lux->equals($kilolux));
    }

    public function testNotEquals(): void
    {
        $lux1 = Lux::of(NumberFactory::create('500'));
        $lux2 = Lux::of(NumberFactory::create('600'));

        self::assertFalse($lux1->equals($lux2));
    }

    public function testGreaterThanSameUnit(): void
    {
        $lux1 = Lux::of(NumberFactory::create('600'));
        $lux2 = Lux::of(NumberFactory::create('500'));

        self::assertTrue($lux1->isGreaterThan($lux2));
        self::assertFalse($lux2->isGreaterThan($lux1));
    }

    public function testGreaterThanDifferentUnits(): void
    {
        $kilolux = Kilolux::of(NumberFactory::create('1')); // 1000 lux
        $lux = Lux::of(NumberFactory::create('500'));

        self::assertTrue($kilolux->isGreaterThan($lux));
        self::assertFalse($lux->isGreaterThan($kilolux));
    }

    public function testGreaterThanOrEqual(): void
    {
        $lux1 = Lux::of(NumberFactory::create('500'));
        $lux2 = Lux::of(NumberFactory::create('500'));
        $lux3 = Lux::of(NumberFactory::create('400'));

        self::assertTrue($lux1->isGreaterThanOrEqual($lux2));
        self::assertTrue($lux1->isGreaterThanOrEqual($lux3));
        self::assertFalse($lux3->isGreaterThanOrEqual($lux1));
    }

    public function testLessThanSameUnit(): void
    {
        $lux1 = Lux::of(NumberFactory::create('400'));
        $lux2 = Lux::of(NumberFactory::create('500'));

        self::assertTrue($lux1->isLessThan($lux2));
        self::assertFalse($lux2->isLessThan($lux1));
    }

    public function testLessThanDifferentUnits(): void
    {
        $lux = Lux::of(NumberFactory::create('500'));
        $kilolux = Kilolux::of(NumberFactory::create('1')); // 1000 lux

        self::assertTrue($lux->isLessThan($kilolux));
        self::assertFalse($kilolux->isLessThan($lux));
    }

    public function testLessThanOrEqual(): void
    {
        $lux1 = Lux::of(NumberFactory::create('500'));
        $lux2 = Lux::of(NumberFactory::create('500'));
        $lux3 = Lux::of(NumberFactory::create('600'));

        self::assertTrue($lux1->isLessThanOrEqual($lux2));
        self::assertTrue($lux1->isLessThanOrEqual($lux3));
        self::assertFalse($lux3->isLessThanOrEqual($lux1));
    }

    public function testCompareImperialAndMetric(): void
    {
        // 1 foot-candle ≈ 10.7639 lux
        $footCandle = FootCandle::of(NumberFactory::create('10')); // ~107.64 lux
        $lux = Lux::of(NumberFactory::create('100'));

        self::assertTrue($footCandle->isGreaterThan($lux));
        self::assertTrue($lux->isLessThan($footCandle));
    }

    public function testIsZero(): void
    {
        $zero = Lux::of(NumberFactory::create('0'));
        $nonZero = Lux::of(NumberFactory::create('500'));

        self::assertTrue($zero->isZero());
        self::assertFalse($nonZero->isZero());
    }

    public function testIsPositive(): void
    {
        $positive = Lux::of(NumberFactory::create('500'));
        $zero = Lux::of(NumberFactory::create('0'));
        $negative = Illuminance::of(
            NumberFactory::create('-500'),
            IlluminanceUnit::Lux,
        );

        self::assertTrue($positive->isPositive());
        self::assertFalse($zero->isPositive());
        self::assertFalse($negative->isPositive());
    }

    public function testIsNegative(): void
    {
        $negative = Illuminance::of(
            NumberFactory::create('-500'),
            IlluminanceUnit::Lux,
        );
        $zero = Lux::of(NumberFactory::create('0'));
        $positive = Lux::of(NumberFactory::create('500'));

        self::assertTrue($negative->isNegative());
        self::assertFalse($zero->isNegative());
        self::assertFalse($positive->isNegative());
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 klx = 1000 lx
        $kilolux = Kilolux::of(NumberFactory::create('1'));
        $lux = Lux::of(NumberFactory::create('1000'));

        self::assertTrue($kilolux->equals($lux));
    }

    public function testCrossUnitComparison(): void
    {
        // 1 klx converted to lx should equal 1000 lx
        $klxToLx = Kilolux::of(NumberFactory::create('1'))->to(IlluminanceUnit::Lux);
        $direct = Lux::of(NumberFactory::create('1000'));

        self::assertEqualsWithDelta(
            (float) $klxToLx->getValue()->value(),
            (float) $direct->getValue()->value(),
            0.001,
        );
    }
}
