<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Velocity;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Velocity\Imperial\FootPerSecond;
use Andante\Measurement\Quantity\Velocity\Imperial\Knot;
use Andante\Measurement\Quantity\Velocity\Imperial\MilePerHour;
use Andante\Measurement\Quantity\Velocity\ImperialVelocity;
use Andante\Measurement\Quantity\Velocity\Metric\KilometerPerHour;
use Andante\Measurement\Quantity\Velocity\Metric\MeterPerSecond;
use Andante\Measurement\Quantity\Velocity\MetricVelocity;
use Andante\Measurement\Quantity\Velocity\Velocity;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Velocity\ImperialVelocityUnit;
use Andante\Measurement\Unit\Velocity\MetricVelocityUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for velocity conversions.
 *
 * Velocity [L¹T⁻¹] represents rate of change of position.
 * Base unit: m/s (meter per second)
 *
 * Common conversions:
 * - 1 m/s = 3.6 km/h
 * - 1 km/h = 0.27778 m/s
 * - 1 mph = 0.44704 m/s = 1.60934 km/h
 * - 1 ft/s = 0.3048 m/s
 * - 1 knot = 0.51444 m/s = 1.852 km/h
 */
final class VelocityConversionTest extends TestCase
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

    // ========== Metric Unit Tests ==========

    public function testMeterPerSecondToKilometerPerHour(): void
    {
        // 1 m/s = 3.6 km/h
        $mps = MeterPerSecond::of(NumberFactory::create('1'));
        $kmh = $mps->to(MetricVelocityUnit::KilometerPerHour);

        self::assertEqualsWithDelta(3.6, (float) $kmh->getValue()->value(), 0.01);
    }

    public function testKilometerPerHourToMeterPerSecond(): void
    {
        // 3.6 km/h = 1 m/s
        $kmh = KilometerPerHour::of(NumberFactory::create('3.6'));
        $mps = $kmh->to(MetricVelocityUnit::MeterPerSecond);

        self::assertEqualsWithDelta(1.0, (float) $mps->getValue()->value(), 0.01);
    }

    public function testMeterPerSecondToCentimeterPerSecond(): void
    {
        // 1 m/s = 100 cm/s
        $mps = MeterPerSecond::of(NumberFactory::create('1'));
        $cmps = $mps->to(MetricVelocityUnit::CentimeterPerSecond);

        self::assertEqualsWithDelta(100.0, (float) $cmps->getValue()->value(), 0.001);
    }

    public function testMeterPerSecondToMillimeterPerSecond(): void
    {
        // 1 m/s = 1000 mm/s
        $mps = MeterPerSecond::of(NumberFactory::create('1'));
        $mmps = $mps->to(MetricVelocityUnit::MillimeterPerSecond);

        self::assertEqualsWithDelta(1000.0, (float) $mmps->getValue()->value(), 0.001);
    }

    public function testHundredKilometerPerHourToMeterPerSecond(): void
    {
        // 100 km/h ≈ 27.78 m/s (common highway speed)
        $kmh = KilometerPerHour::of(NumberFactory::create('100'));
        $mps = $kmh->to(MetricVelocityUnit::MeterPerSecond);

        self::assertEqualsWithDelta(27.78, (float) $mps->getValue()->value(), 0.01);
    }

    // ========== Imperial Unit Tests ==========

    public function testMilePerHourToFootPerSecond(): void
    {
        // 1 mph = 1.46667 ft/s
        $mph = MilePerHour::of(NumberFactory::create('1'));
        $fps = $mph->to(ImperialVelocityUnit::FootPerSecond);

        self::assertEqualsWithDelta(1.46667, (float) $fps->getValue()->value(), 0.01);
    }

    public function testFootPerSecondToMilePerHour(): void
    {
        // 1 ft/s = 0.68182 mph
        $fps = FootPerSecond::of(NumberFactory::create('1'));
        $mph = $fps->to(ImperialVelocityUnit::MilePerHour);

        self::assertEqualsWithDelta(0.68182, (float) $mph->getValue()->value(), 0.001);
    }

    public function testKnotToMilePerHour(): void
    {
        // 1 knot = 1.15078 mph
        $knot = Knot::of(NumberFactory::create('1'));
        $mph = $knot->to(ImperialVelocityUnit::MilePerHour);

        self::assertEqualsWithDelta(1.15078, (float) $mph->getValue()->value(), 0.001);
    }

    // ========== Cross-System Conversions ==========

    public function testMeterPerSecondToMilePerHour(): void
    {
        // 1 m/s = 2.23694 mph
        $mps = MeterPerSecond::of(NumberFactory::create('1'));
        $mph = $mps->to(ImperialVelocityUnit::MilePerHour);

        self::assertEqualsWithDelta(2.23694, (float) $mph->getValue()->value(), 0.001);
    }

    public function testMilePerHourToMeterPerSecond(): void
    {
        // 1 mph = 0.44704 m/s
        $mph = MilePerHour::of(NumberFactory::create('1'));
        $mps = $mph->to(MetricVelocityUnit::MeterPerSecond);

        self::assertEqualsWithDelta(0.44704, (float) $mps->getValue()->value(), 0.00001);
    }

    public function testMilePerHourToKilometerPerHour(): void
    {
        // 1 mph = 1.60934 km/h
        $mph = MilePerHour::of(NumberFactory::create('1'));
        $kmh = $mph->to(MetricVelocityUnit::KilometerPerHour);

        self::assertEqualsWithDelta(1.60934, (float) $kmh->getValue()->value(), 0.001);
    }

    public function testKilometerPerHourToMilePerHour(): void
    {
        // 100 km/h ≈ 62.14 mph
        $kmh = KilometerPerHour::of(NumberFactory::create('100'));
        $mph = $kmh->to(ImperialVelocityUnit::MilePerHour);

        self::assertEqualsWithDelta(62.14, (float) $mph->getValue()->value(), 0.01);
    }

    public function testKnotToKilometerPerHour(): void
    {
        // 1 knot = 1.852 km/h (exact definition)
        $knot = Knot::of(NumberFactory::create('1'));
        $kmh = $knot->to(MetricVelocityUnit::KilometerPerHour);

        self::assertEqualsWithDelta(1.852, (float) $kmh->getValue()->value(), 0.001);
    }

    public function testKnotToMeterPerSecond(): void
    {
        // 1 knot = 0.51444 m/s
        $knot = Knot::of(NumberFactory::create('1'));
        $mps = $knot->to(MetricVelocityUnit::MeterPerSecond);

        self::assertEqualsWithDelta(0.51444, (float) $mps->getValue()->value(), 0.00001);
    }

    public function testFootPerSecondToMeterPerSecond(): void
    {
        // 1 ft/s = 0.3048 m/s
        $fps = FootPerSecond::of(NumberFactory::create('1'));
        $mps = $fps->to(MetricVelocityUnit::MeterPerSecond);

        self::assertEqualsWithDelta(0.3048, (float) $mps->getValue()->value(), 0.0001);
    }

    // ========== Real-World Scenario Tests ==========

    public function testTypicalHighwaySpeed(): void
    {
        // 120 km/h highway speed ≈ 74.56 mph
        $kmh = KilometerPerHour::of(NumberFactory::create('120'));
        $mph = $kmh->to(ImperialVelocityUnit::MilePerHour);

        self::assertEqualsWithDelta(74.56, (float) $mph->getValue()->value(), 0.1);
    }

    public function testSpeedOfSound(): void
    {
        // Speed of sound at sea level ≈ 343 m/s ≈ 1235 km/h
        $mps = MeterPerSecond::of(NumberFactory::create('343'));
        $kmh = $mps->to(MetricVelocityUnit::KilometerPerHour);

        self::assertEqualsWithDelta(1234.8, (float) $kmh->getValue()->value(), 0.1);
    }

    public function testUsainBoltTopSpeed(): void
    {
        // Usain Bolt's top speed ≈ 12.27 m/s ≈ 44.17 km/h ≈ 27.44 mph
        $mps = MeterPerSecond::of(NumberFactory::create('12.27'));

        $kmh = $mps->to(MetricVelocityUnit::KilometerPerHour);
        self::assertEqualsWithDelta(44.17, (float) $kmh->getValue()->value(), 0.1);

        $mph = $mps->to(ImperialVelocityUnit::MilePerHour);
        self::assertEqualsWithDelta(27.44, (float) $mph->getValue()->value(), 0.1);
    }

    public function testCruiseShipSpeed(): void
    {
        // Typical cruise ship speed ≈ 20 knots ≈ 37 km/h ≈ 23 mph
        $knot = Knot::of(NumberFactory::create('20'));

        $kmh = $knot->to(MetricVelocityUnit::KilometerPerHour);
        self::assertEqualsWithDelta(37.04, (float) $kmh->getValue()->value(), 0.1);

        $mph = $knot->to(ImperialVelocityUnit::MilePerHour);
        self::assertEqualsWithDelta(23.02, (float) $mph->getValue()->value(), 0.1);
    }

    // ========== Mid-Level Class Tests ==========

    public function testMetricVelocityCreation(): void
    {
        $velocity = MetricVelocity::of(
            NumberFactory::create('100'),
            MetricVelocityUnit::KilometerPerHour,
        );

        self::assertEquals('100', $velocity->getValue()->value());
        self::assertSame(MetricVelocityUnit::KilometerPerHour, $velocity->getUnit());
    }

    public function testImperialVelocityCreation(): void
    {
        $velocity = ImperialVelocity::of(
            NumberFactory::create('60'),
            ImperialVelocityUnit::MilePerHour,
        );

        self::assertEquals('60', $velocity->getValue()->value());
        self::assertSame(ImperialVelocityUnit::MilePerHour, $velocity->getUnit());
    }

    public function testMetricVelocityConversion(): void
    {
        $velocity = MetricVelocity::of(
            NumberFactory::create('36'),
            MetricVelocityUnit::KilometerPerHour,
        );

        $converted = $velocity->to(MetricVelocityUnit::MeterPerSecond);
        self::assertEqualsWithDelta(10.0, (float) $converted->getValue()->value(), 0.01);
    }

    // ========== Generic Class Tests ==========

    public function testGenericVelocityWithMetricUnit(): void
    {
        $velocity = Velocity::of(
            NumberFactory::create('50'),
            MetricVelocityUnit::KilometerPerHour,
        );

        self::assertEquals('50', $velocity->getValue()->value());
        self::assertSame(MetricVelocityUnit::KilometerPerHour, $velocity->getUnit());
    }

    public function testGenericVelocityWithImperialUnit(): void
    {
        $velocity = Velocity::of(
            NumberFactory::create('30'),
            ImperialVelocityUnit::MilePerHour,
        );

        self::assertEquals('30', $velocity->getValue()->value());
        self::assertSame(ImperialVelocityUnit::MilePerHour, $velocity->getUnit());
    }

    public function testGenericVelocityConversion(): void
    {
        $velocity = Velocity::of(
            NumberFactory::create('100'),
            MetricVelocityUnit::KilometerPerHour,
        );

        $converted = $velocity->to(ImperialVelocityUnit::MilePerHour);
        self::assertEqualsWithDelta(62.14, (float) $converted->getValue()->value(), 0.01);
    }

    // ========== Round-Trip Tests ==========

    public function testMetricRoundTrip(): void
    {
        $original = KilometerPerHour::of(NumberFactory::create('100'));
        $toMps = $original->to(MetricVelocityUnit::MeterPerSecond);

        $mpsValue = MeterPerSecond::of($toMps->getValue());
        $backToKmh = $mpsValue->to(MetricVelocityUnit::KilometerPerHour);

        self::assertEqualsWithDelta(100.0, (float) $backToKmh->getValue()->value(), 0.001);
    }

    public function testCrossSystemRoundTrip(): void
    {
        $original = MilePerHour::of(NumberFactory::create('60'));
        $toKmh = $original->to(MetricVelocityUnit::KilometerPerHour);

        $kmhQuantity = KilometerPerHour::of($toKmh->getValue());
        $backToMph = $kmhQuantity->to(ImperialVelocityUnit::MilePerHour);

        self::assertEqualsWithDelta(60.0, (float) $backToMph->getValue()->value(), 0.01);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $v1 = KilometerPerHour::of(NumberFactory::create('80'));
        $v2 = KilometerPerHour::of(NumberFactory::create('20'));

        $sum = $v1->add($v2);

        self::assertEqualsWithDelta(100.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $v1 = KilometerPerHour::of(NumberFactory::create('100'));
        $v2 = KilometerPerHour::of(NumberFactory::create('40'));

        $diff = $v1->subtract($v2);

        self::assertEqualsWithDelta(60.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $velocity = KilometerPerHour::of(NumberFactory::create('50'));
        $result = $velocity->multiplyBy(NumberFactory::create('2'));

        self::assertEqualsWithDelta(100.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $velocity = KilometerPerHour::of(NumberFactory::create('100'));
        $result = $velocity->divideBy(NumberFactory::create('2'));

        self::assertEqualsWithDelta(50.0, (float) $result->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $v1 = KilometerPerHour::of(NumberFactory::create('100'));
        $v2 = KilometerPerHour::of(NumberFactory::create('80'));

        self::assertTrue($v1->isGreaterThan($v2));
        self::assertFalse($v1->isLessThan($v2));
        self::assertFalse($v1->equals($v2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 100 km/h should be greater than 60 mph (≈ 96.5 km/h)
        $kmh = KilometerPerHour::of(NumberFactory::create('100'));
        $mph = MilePerHour::of(NumberFactory::create('60'));

        self::assertTrue($kmh->isGreaterThan($mph));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 m/s = 3.6 km/h
        $mps = MeterPerSecond::of(NumberFactory::create('1'));
        $kmh = KilometerPerHour::of(NumberFactory::create('3.6'));

        self::assertTrue($mps->equals($kmh));
    }
}
