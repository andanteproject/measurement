<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Illuminance;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Illuminance\Imperial\FootCandle;
use Andante\Measurement\Quantity\Illuminance\SI\Kilolux;
use Andante\Measurement\Quantity\Illuminance\SI\Lux;
use Andante\Measurement\Quantity\Illuminance\SI\Millilux;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Illuminance\IlluminanceUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for illuminance auto-scale functionality.
 */
final class IlluminanceAutoScaleTest extends TestCase
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

    public function testAutoScaleFromLuxToKilolux(): void
    {
        // 5000 lx should auto-scale to 5 klx
        $lux = Lux::of(NumberFactory::create('5000'));

        $scaled = $lux->autoScale();

        self::assertSame(IlluminanceUnit::Kilolux, $scaled->getUnit());
        self::assertEqualsWithDelta(5.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromLuxToMillilux(): void
    {
        // 0.005 lx should auto-scale to 5 mlx
        $lux = Lux::of(NumberFactory::create('0.005'));

        $scaled = $lux->autoScale();

        self::assertSame(IlluminanceUnit::Millilux, $scaled->getUnit());
        self::assertEqualsWithDelta(5.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleKeepsLux(): void
    {
        // 500 lx should stay as lx
        $lux = Lux::of(NumberFactory::create('500'));

        $scaled = $lux->autoScale();

        self::assertSame(IlluminanceUnit::Lux, $scaled->getUnit());
        self::assertEqualsWithDelta(500.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromKilolux(): void
    {
        // 0.5 klx should auto-scale to 500 lx
        $kilolux = Kilolux::of(NumberFactory::create('0.5'));

        $scaled = $kilolux->autoScale();

        self::assertSame(IlluminanceUnit::Lux, $scaled->getUnit());
        self::assertEqualsWithDelta(500.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromMillilux(): void
    {
        // 5000 mlx should auto-scale to 5 lx
        $millilux = Millilux::of(NumberFactory::create('5000'));

        $scaled = $millilux->autoScale();

        self::assertSame(IlluminanceUnit::Lux, $scaled->getUnit());
        self::assertEqualsWithDelta(5.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromFootCandle(): void
    {
        // Foot-candle is Imperial, so it stays in Imperial system
        // But since there's only one Imperial unit, it stays as foot-candle
        $footCandle = FootCandle::of(NumberFactory::create('100'));

        $scaled = $footCandle->autoScale();

        self::assertSame(IlluminanceUnit::FootCandle, $scaled->getUnit());
        self::assertEqualsWithDelta(100.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleVeryLargeValue(): void
    {
        // 100000 lx should auto-scale to 100 klx
        $lux = Lux::of(NumberFactory::create('100000'));

        $scaled = $lux->autoScale();

        self::assertSame(IlluminanceUnit::Kilolux, $scaled->getUnit());
        self::assertEqualsWithDelta(100.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleVerySmallValue(): void
    {
        // 0.0001 lx should auto-scale to 0.1 mlx
        $lux = Lux::of(NumberFactory::create('0.0001'));

        $scaled = $lux->autoScale();

        self::assertSame(IlluminanceUnit::Millilux, $scaled->getUnit());
        self::assertEqualsWithDelta(0.1, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromMilliluxToKilolux(): void
    {
        // 1000000 mlx = 1 klx
        $millilux = Millilux::of(NumberFactory::create('1000000'));

        $scaled = $millilux->autoScale();

        self::assertSame(IlluminanceUnit::Kilolux, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromKiloluxToMillilux(): void
    {
        // 0.000001 klx = 1 mlx
        $kilolux = Kilolux::of(NumberFactory::create('0.000001'));

        $scaled = $kilolux->autoScale();

        self::assertSame(IlluminanceUnit::Millilux, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }
}
