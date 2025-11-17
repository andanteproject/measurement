<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Acceleration;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Acceleration\Acceleration;
use Andante\Measurement\Quantity\Acceleration\Imperial\FootPerSecondSquared;
use Andante\Measurement\Quantity\Acceleration\Imperial\InchPerSecondSquared;
use Andante\Measurement\Quantity\Acceleration\ImperialAcceleration;
use Andante\Measurement\Quantity\Acceleration\Metric\CentimeterPerSecondSquared;
use Andante\Measurement\Quantity\Acceleration\Metric\Gal;
use Andante\Measurement\Quantity\Acceleration\Metric\MeterPerSecondSquared;
use Andante\Measurement\Quantity\Acceleration\Metric\StandardGravity;
use Andante\Measurement\Quantity\Acceleration\MetricAcceleration;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Acceleration\ImperialAccelerationUnit;
use Andante\Measurement\Unit\Acceleration\MetricAccelerationUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for acceleration conversions.
 *
 * Acceleration [L¹T⁻²] represents rate of change of velocity.
 * Base unit: m/s² (meter per second squared)
 *
 * Common conversions:
 * - 1 m/s² = 100 cm/s² = 100 Gal
 * - 1 g = 9.80665 m/s² (exact by definition)
 * - 1 ft/s² = 0.3048 m/s²
 * - 1 in/s² = 0.0254 m/s²
 */
final class AccelerationConversionTest extends TestCase
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

    public function testMeterPerSecondSquaredToCentimeterPerSecondSquared(): void
    {
        // 1 m/s² = 100 cm/s²
        $mps2 = MeterPerSecondSquared::of(NumberFactory::create('1'));
        $cmps2 = $mps2->to(MetricAccelerationUnit::CentimeterPerSecondSquared);

        self::assertEqualsWithDelta(100.0, (float) $cmps2->getValue()->value(), 0.001);
    }

    public function testCentimeterPerSecondSquaredToMeterPerSecondSquared(): void
    {
        // 100 cm/s² = 1 m/s²
        $cmps2 = CentimeterPerSecondSquared::of(NumberFactory::create('100'));
        $mps2 = $cmps2->to(MetricAccelerationUnit::MeterPerSecondSquared);

        self::assertEqualsWithDelta(1.0, (float) $mps2->getValue()->value(), 0.001);
    }

    public function testMeterPerSecondSquaredToMillimeterPerSecondSquared(): void
    {
        // 1 m/s² = 1000 mm/s²
        $mps2 = MeterPerSecondSquared::of(NumberFactory::create('1'));
        $mmps2 = $mps2->to(MetricAccelerationUnit::MillimeterPerSecondSquared);

        self::assertEqualsWithDelta(1000.0, (float) $mmps2->getValue()->value(), 0.001);
    }

    public function testMeterPerSecondSquaredToGal(): void
    {
        // 1 m/s² = 100 Gal (1 Gal = 1 cm/s²)
        $mps2 = MeterPerSecondSquared::of(NumberFactory::create('1'));
        $gal = $mps2->to(MetricAccelerationUnit::Gal);

        self::assertEqualsWithDelta(100.0, (float) $gal->getValue()->value(), 0.001);
    }

    public function testGalToMeterPerSecondSquared(): void
    {
        // 100 Gal = 1 m/s²
        $gal = Gal::of(NumberFactory::create('100'));
        $mps2 = $gal->to(MetricAccelerationUnit::MeterPerSecondSquared);

        self::assertEqualsWithDelta(1.0, (float) $mps2->getValue()->value(), 0.001);
    }

    public function testStandardGravityToMeterPerSecondSquared(): void
    {
        // 1 g = 9.80665 m/s²
        $g = StandardGravity::of(NumberFactory::create('1'));
        $mps2 = $g->to(MetricAccelerationUnit::MeterPerSecondSquared);

        self::assertEqualsWithDelta(9.80665, (float) $mps2->getValue()->value(), 0.00001);
    }

    public function testMeterPerSecondSquaredToStandardGravity(): void
    {
        // 9.80665 m/s² = 1 g
        $mps2 = MeterPerSecondSquared::of(NumberFactory::create('9.80665'));
        $g = $mps2->to(MetricAccelerationUnit::StandardGravity);

        self::assertEqualsWithDelta(1.0, (float) $g->getValue()->value(), 0.0001);
    }

    // ========== Imperial Unit Tests ==========

    public function testFootPerSecondSquaredToInchPerSecondSquared(): void
    {
        // 1 ft/s² = 12 in/s²
        $ftps2 = FootPerSecondSquared::of(NumberFactory::create('1'));
        $inps2 = $ftps2->to(ImperialAccelerationUnit::InchPerSecondSquared);

        self::assertEqualsWithDelta(12.0, (float) $inps2->getValue()->value(), 0.001);
    }

    public function testInchPerSecondSquaredToFootPerSecondSquared(): void
    {
        // 12 in/s² = 1 ft/s²
        $inps2 = InchPerSecondSquared::of(NumberFactory::create('12'));
        $ftps2 = $inps2->to(ImperialAccelerationUnit::FootPerSecondSquared);

        self::assertEqualsWithDelta(1.0, (float) $ftps2->getValue()->value(), 0.001);
    }

    // ========== Cross-System Conversions ==========

    public function testMeterPerSecondSquaredToFootPerSecondSquared(): void
    {
        // 1 m/s² = 3.28084 ft/s²
        $mps2 = MeterPerSecondSquared::of(NumberFactory::create('1'));
        $ftps2 = $mps2->to(ImperialAccelerationUnit::FootPerSecondSquared);

        self::assertEqualsWithDelta(3.28084, (float) $ftps2->getValue()->value(), 0.001);
    }

    public function testFootPerSecondSquaredToMeterPerSecondSquared(): void
    {
        // 1 ft/s² = 0.3048 m/s²
        $ftps2 = FootPerSecondSquared::of(NumberFactory::create('1'));
        $mps2 = $ftps2->to(MetricAccelerationUnit::MeterPerSecondSquared);

        self::assertEqualsWithDelta(0.3048, (float) $mps2->getValue()->value(), 0.0001);
    }

    public function testInchPerSecondSquaredToMeterPerSecondSquared(): void
    {
        // 1 in/s² = 0.0254 m/s²
        $inps2 = InchPerSecondSquared::of(NumberFactory::create('1'));
        $mps2 = $inps2->to(MetricAccelerationUnit::MeterPerSecondSquared);

        self::assertEqualsWithDelta(0.0254, (float) $mps2->getValue()->value(), 0.00001);
    }

    public function testStandardGravityToFootPerSecondSquared(): void
    {
        // 1 g ≈ 32.174 ft/s²
        $g = StandardGravity::of(NumberFactory::create('1'));
        $ftps2 = $g->to(ImperialAccelerationUnit::FootPerSecondSquared);

        self::assertEqualsWithDelta(32.174, (float) $ftps2->getValue()->value(), 0.01);
    }

    // ========== Real-World Scenario Tests ==========

    public function testEarthGravity(): void
    {
        // Standard gravity: 1 g = 9.80665 m/s² = 980.665 Gal
        $g = StandardGravity::of(NumberFactory::create('1'));

        $mps2 = $g->to(MetricAccelerationUnit::MeterPerSecondSquared);
        self::assertEqualsWithDelta(9.80665, (float) $mps2->getValue()->value(), 0.00001);

        $gal = $g->to(MetricAccelerationUnit::Gal);
        self::assertEqualsWithDelta(980.665, (float) $gal->getValue()->value(), 0.01);
    }

    public function testCarBraking(): void
    {
        // Typical car emergency braking: ~10 m/s² ≈ 1.02 g
        $braking = MeterPerSecondSquared::of(NumberFactory::create('10'));
        $inG = $braking->to(MetricAccelerationUnit::StandardGravity);

        self::assertEqualsWithDelta(1.02, (float) $inG->getValue()->value(), 0.01);
    }

    public function testFighterJetManeuver(): void
    {
        // Fighter jet can pull 9 g
        $maneuver = StandardGravity::of(NumberFactory::create('9'));
        $mps2 = $maneuver->to(MetricAccelerationUnit::MeterPerSecondSquared);

        self::assertEqualsWithDelta(88.26, (float) $mps2->getValue()->value(), 0.1);
    }

    // ========== Mid-Level Class Tests ==========

    public function testMetricAccelerationCreation(): void
    {
        $acceleration = MetricAcceleration::of(
            NumberFactory::create('9.81'),
            MetricAccelerationUnit::MeterPerSecondSquared,
        );

        self::assertEquals('9.81', $acceleration->getValue()->value());
        self::assertSame(MetricAccelerationUnit::MeterPerSecondSquared, $acceleration->getUnit());
    }

    public function testImperialAccelerationCreation(): void
    {
        $acceleration = ImperialAcceleration::of(
            NumberFactory::create('32.174'),
            ImperialAccelerationUnit::FootPerSecondSquared,
        );

        self::assertEquals('32.174', $acceleration->getValue()->value());
        self::assertSame(ImperialAccelerationUnit::FootPerSecondSquared, $acceleration->getUnit());
    }

    public function testMetricAccelerationConversion(): void
    {
        $acceleration = MetricAcceleration::of(
            NumberFactory::create('9.80665'),
            MetricAccelerationUnit::MeterPerSecondSquared,
        );

        $converted = $acceleration->to(MetricAccelerationUnit::StandardGravity);
        self::assertEqualsWithDelta(1.0, (float) $converted->getValue()->value(), 0.0001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericAccelerationWithMetricUnit(): void
    {
        $acceleration = Acceleration::of(
            NumberFactory::create('9.81'),
            MetricAccelerationUnit::MeterPerSecondSquared,
        );

        self::assertEquals('9.81', $acceleration->getValue()->value());
        self::assertSame(MetricAccelerationUnit::MeterPerSecondSquared, $acceleration->getUnit());
    }

    public function testGenericAccelerationWithImperialUnit(): void
    {
        $acceleration = Acceleration::of(
            NumberFactory::create('32.174'),
            ImperialAccelerationUnit::FootPerSecondSquared,
        );

        self::assertEquals('32.174', $acceleration->getValue()->value());
        self::assertSame(ImperialAccelerationUnit::FootPerSecondSquared, $acceleration->getUnit());
    }

    public function testGenericAccelerationConversion(): void
    {
        $acceleration = Acceleration::of(
            NumberFactory::create('1'),
            MetricAccelerationUnit::StandardGravity,
        );

        $converted = $acceleration->to(ImperialAccelerationUnit::FootPerSecondSquared);
        self::assertEqualsWithDelta(32.174, (float) $converted->getValue()->value(), 0.01);
    }

    // ========== Round-Trip Tests ==========

    public function testMetricRoundTrip(): void
    {
        $original = MeterPerSecondSquared::of(NumberFactory::create('9.80665'));
        $toGal = $original->to(MetricAccelerationUnit::Gal);

        $galValue = Gal::of($toGal->getValue());
        $backToMps2 = $galValue->to(MetricAccelerationUnit::MeterPerSecondSquared);

        self::assertEqualsWithDelta(9.80665, (float) $backToMps2->getValue()->value(), 0.0001);
    }

    public function testCrossSystemRoundTrip(): void
    {
        $original = StandardGravity::of(NumberFactory::create('1'));
        $toFtps2 = $original->to(ImperialAccelerationUnit::FootPerSecondSquared);

        $ftps2Quantity = FootPerSecondSquared::of($toFtps2->getValue());
        $backToG = $ftps2Quantity->to(MetricAccelerationUnit::StandardGravity);

        self::assertEqualsWithDelta(1.0, (float) $backToG->getValue()->value(), 0.001);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $a1 = MeterPerSecondSquared::of(NumberFactory::create('5'));
        $a2 = MeterPerSecondSquared::of(NumberFactory::create('4.81'));

        $sum = $a1->add($a2);

        self::assertEqualsWithDelta(9.81, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $a1 = MeterPerSecondSquared::of(NumberFactory::create('9.81'));
        $a2 = MeterPerSecondSquared::of(NumberFactory::create('5'));

        $diff = $a1->subtract($a2);

        self::assertEqualsWithDelta(4.81, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $acceleration = MeterPerSecondSquared::of(NumberFactory::create('5'));
        $result = $acceleration->multiplyBy(NumberFactory::create('2'));

        self::assertEqualsWithDelta(10.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $acceleration = MeterPerSecondSquared::of(NumberFactory::create('10'));
        $result = $acceleration->divideBy(NumberFactory::create('2'));

        self::assertEqualsWithDelta(5.0, (float) $result->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $a1 = MeterPerSecondSquared::of(NumberFactory::create('10'));
        $a2 = MeterPerSecondSquared::of(NumberFactory::create('9.81'));

        self::assertTrue($a1->isGreaterThan($a2));
        self::assertFalse($a1->isLessThan($a2));
        self::assertFalse($a1->equals($a2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 g (9.80665 m/s²) should be less than 10 m/s²
        $g = StandardGravity::of(NumberFactory::create('1'));
        $mps2 = MeterPerSecondSquared::of(NumberFactory::create('10'));

        self::assertTrue($mps2->isGreaterThan($g));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 g = 9.80665 m/s²
        $g = StandardGravity::of(NumberFactory::create('1'));
        $mps2 = MeterPerSecondSquared::of(NumberFactory::create('9.80665'));

        self::assertTrue($g->equals($mps2));
    }
}
