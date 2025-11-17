<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Illuminance;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Illuminance\Illuminance;
use Andante\Measurement\Quantity\Illuminance\Imperial\FootCandle;
use Andante\Measurement\Quantity\Illuminance\SI\Kilolux;
use Andante\Measurement\Quantity\Illuminance\SI\Lux;
use Andante\Measurement\Quantity\Illuminance\SI\Millilux;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Illuminance\IlluminanceUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for illuminance conversions.
 *
 * Illuminance [L⁻²J¹] represents the luminous flux incident on a surface per unit area.
 * Base unit: lux (lx), SI derived unit = lm/m²
 *
 * Common conversions:
 * - 1 klx = 1000 lx
 * - 1 lx = 1000 mlx
 * - 1 fc ≈ 10.7639 lx (1 lm/ft²)
 */
final class IlluminanceConversionTest extends TestCase
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

    // ========== SI Prefix Tests ==========

    public function testLuxToLux(): void
    {
        $lux = Lux::of(NumberFactory::create('500'));
        $result = $lux->to(IlluminanceUnit::Lux);

        self::assertEqualsWithDelta(500.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testLuxToKilolux(): void
    {
        // 1000 lx = 1 klx
        $lux = Lux::of(NumberFactory::create('1000'));
        $kilolux = $lux->to(IlluminanceUnit::Kilolux);

        self::assertEqualsWithDelta(1.0, (float) $kilolux->getValue()->value(), 0.001);
    }

    public function testKiloluxToLux(): void
    {
        // 1 klx = 1000 lx
        $kilolux = Kilolux::of(NumberFactory::create('1'));
        $lux = $kilolux->to(IlluminanceUnit::Lux);

        self::assertEqualsWithDelta(1000.0, (float) $lux->getValue()->value(), 0.001);
    }

    public function testLuxToMillilux(): void
    {
        // 1 lx = 1000 mlx
        $lux = Lux::of(NumberFactory::create('1'));
        $millilux = $lux->to(IlluminanceUnit::Millilux);

        self::assertEqualsWithDelta(1000.0, (float) $millilux->getValue()->value(), 0.001);
    }

    public function testMilliluxToLux(): void
    {
        // 1000 mlx = 1 lx
        $millilux = Millilux::of(NumberFactory::create('1000'));
        $lux = $millilux->to(IlluminanceUnit::Lux);

        self::assertEqualsWithDelta(1.0, (float) $lux->getValue()->value(), 0.001);
    }

    public function testKiloluxToMillilux(): void
    {
        // 1 klx = 1,000,000 mlx
        $kilolux = Kilolux::of(NumberFactory::create('1'));
        $millilux = $kilolux->to(IlluminanceUnit::Millilux);

        self::assertEqualsWithDelta(1000000.0, (float) $millilux->getValue()->value(), 0.001);
    }

    public function testMilliluxToKilolux(): void
    {
        // 1,000,000 mlx = 1 klx
        $millilux = Millilux::of(NumberFactory::create('1000000'));
        $kilolux = $millilux->to(IlluminanceUnit::Kilolux);

        self::assertEqualsWithDelta(1.0, (float) $kilolux->getValue()->value(), 0.001);
    }

    // ========== Imperial Conversions ==========

    public function testFootCandleToLux(): void
    {
        // 1 fc ≈ 10.7639 lx
        $footCandle = FootCandle::of(NumberFactory::create('1'));
        $lux = $footCandle->to(IlluminanceUnit::Lux);

        self::assertEqualsWithDelta(10.7639, (float) $lux->getValue()->value(), 0.001);
    }

    public function testLuxToFootCandle(): void
    {
        // 10.7639 lx ≈ 1 fc
        $lux = Lux::of(NumberFactory::create('10.7639104167097'));
        $footCandle = $lux->to(IlluminanceUnit::FootCandle);

        self::assertEqualsWithDelta(1.0, (float) $footCandle->getValue()->value(), 0.001);
    }

    public function testMultipleFootCandlesToLux(): void
    {
        // 50 fc ≈ 538.2 lx
        $footCandle = FootCandle::of(NumberFactory::create('50'));
        $lux = $footCandle->to(IlluminanceUnit::Lux);

        self::assertEqualsWithDelta(538.19, (float) $lux->getValue()->value(), 0.1);
    }

    public function testMultipleLuxToFootCandle(): void
    {
        // 100 lx ≈ 9.29 fc
        $lux = Lux::of(NumberFactory::create('100'));
        $footCandle = $lux->to(IlluminanceUnit::FootCandle);

        self::assertEqualsWithDelta(9.29, (float) $footCandle->getValue()->value(), 0.01);
    }

    // ========== Real-World Scenario Tests ==========

    public function testOfficeWorkplaceLighting(): void
    {
        // Typical office: 300-500 lx
        $office = Lux::of(NumberFactory::create('400'));
        $kilolux = $office->to(IlluminanceUnit::Kilolux);

        self::assertEqualsWithDelta(0.4, (float) $kilolux->getValue()->value(), 0.001);
    }

    public function testOutdoorSunlight(): void
    {
        // Direct sunlight: ~100,000 lx = 100 klx
        $sunlight = Kilolux::of(NumberFactory::create('100'));
        $lux = $sunlight->to(IlluminanceUnit::Lux);

        self::assertEqualsWithDelta(100000.0, (float) $lux->getValue()->value(), 0.001);
    }

    public function testOvercastDay(): void
    {
        // Overcast day: ~1,000 lx = 1 klx
        $overcast = Lux::of(NumberFactory::create('1000'));
        $kilolux = $overcast->to(IlluminanceUnit::Kilolux);

        self::assertEqualsWithDelta(1.0, (float) $kilolux->getValue()->value(), 0.001);
    }

    public function testMoonlight(): void
    {
        // Full moonlight: ~0.3 lx = 300 mlx
        $moonlight = Lux::of(NumberFactory::create('0.3'));
        $millilux = $moonlight->to(IlluminanceUnit::Millilux);

        self::assertEqualsWithDelta(300.0, (float) $millilux->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericIlluminanceWithLux(): void
    {
        $illuminance = Illuminance::of(
            NumberFactory::create('500'),
            IlluminanceUnit::Lux,
        );

        self::assertEquals('500', $illuminance->getValue()->value());
        self::assertSame(IlluminanceUnit::Lux, $illuminance->getUnit());
    }

    public function testGenericIlluminanceWithKilolux(): void
    {
        $illuminance = Illuminance::of(
            NumberFactory::create('5'),
            IlluminanceUnit::Kilolux,
        );

        self::assertEquals('5', $illuminance->getValue()->value());
        self::assertSame(IlluminanceUnit::Kilolux, $illuminance->getUnit());
    }

    public function testGenericIlluminanceWithFootCandle(): void
    {
        $illuminance = Illuminance::of(
            NumberFactory::create('50'),
            IlluminanceUnit::FootCandle,
        );

        self::assertEquals('50', $illuminance->getValue()->value());
        self::assertSame(IlluminanceUnit::FootCandle, $illuminance->getUnit());
    }

    public function testGenericIlluminanceConversion(): void
    {
        $illuminance = Illuminance::of(
            NumberFactory::create('2.5'),
            IlluminanceUnit::Kilolux,
        );

        $converted = $illuminance->to(IlluminanceUnit::Lux);
        self::assertEqualsWithDelta(2500.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Round-Trip Tests ==========

    public function testLuxRoundTrip(): void
    {
        $original = Lux::of(NumberFactory::create('500'));
        $toMillilux = $original->to(IlluminanceUnit::Millilux);

        $mlxQuantity = Millilux::of($toMillilux->getValue());
        $backToLux = $mlxQuantity->to(IlluminanceUnit::Lux);

        self::assertEqualsWithDelta(500.0, (float) $backToLux->getValue()->value(), 0.001);
    }

    public function testKiloluxRoundTrip(): void
    {
        $original = Kilolux::of(NumberFactory::create('10'));
        $toLux = $original->to(IlluminanceUnit::Lux);

        $lxQuantity = Lux::of($toLux->getValue());
        $backToKilolux = $lxQuantity->to(IlluminanceUnit::Kilolux);

        self::assertEqualsWithDelta(10.0, (float) $backToKilolux->getValue()->value(), 0.001);
    }

    public function testImperialRoundTrip(): void
    {
        $original = FootCandle::of(NumberFactory::create('100'));
        $toLux = $original->to(IlluminanceUnit::Lux);

        $lxQuantity = Lux::of($toLux->getValue());
        $backToFootCandle = $lxQuantity->to(IlluminanceUnit::FootCandle);

        self::assertEqualsWithDelta(100.0, (float) $backToFootCandle->getValue()->value(), 0.001);
    }
}
