<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\CalorificValue;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\CalorificValue\CalorificValue;
use Andante\Measurement\Quantity\CalorificValue\Imperial\BTUPerCubicFoot;
use Andante\Measurement\Quantity\CalorificValue\Imperial\ThermPerCubicFoot;
use Andante\Measurement\Quantity\CalorificValue\ImperialCalorificValue;
use Andante\Measurement\Quantity\CalorificValue\Metric\GigajoulePerCubicMeter;
use Andante\Measurement\Quantity\CalorificValue\Metric\JoulePerCubicMeter;
use Andante\Measurement\Quantity\CalorificValue\Metric\KilojoulePerCubicMeter;
use Andante\Measurement\Quantity\CalorificValue\Metric\MegajoulePerCubicMeter;
use Andante\Measurement\Quantity\CalorificValue\MetricCalorificValue;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\CalorificValue\ImperialCalorificValueUnit;
use Andante\Measurement\Unit\CalorificValue\MetricCalorificValueUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for calorific value conversions.
 *
 * Calorific value is Energy per Volume [L⁻¹M¹T⁻²].
 * Base unit: J/m³ (Joule per cubic meter)
 *
 * Common conversions:
 * - 1 kJ/m³ = 1,000 J/m³
 * - 1 MJ/m³ = 1,000,000 J/m³
 * - 1 GJ/m³ = 1,000,000,000 J/m³
 * - 1 BTU/ft³ ≈ 37,258.946 J/m³
 * - 1 therm/ft³ = 100,000 BTU/ft³ ≈ 3,725,894,600 J/m³
 */
final class CalorificValueConversionTest extends TestCase
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

    public function testJoulePerCubicMeterToKilojoulePerCubicMeter(): void
    {
        // 1000 J/m³ = 1 kJ/m³
        $jpm = JoulePerCubicMeter::of(NumberFactory::create('1000'));
        $kjpm = $jpm->to(MetricCalorificValueUnit::KilojoulePerCubicMeter);

        self::assertEqualsWithDelta(1.0, (float) $kjpm->getValue()->value(), 0.001);
    }

    public function testKilojoulePerCubicMeterToJoulePerCubicMeter(): void
    {
        // 1 kJ/m³ = 1000 J/m³
        $kjpm = KilojoulePerCubicMeter::of(NumberFactory::create('1'));
        $jpm = $kjpm->to(MetricCalorificValueUnit::JoulePerCubicMeter);

        self::assertEqualsWithDelta(1000.0, (float) $jpm->getValue()->value(), 0.001);
    }

    public function testMegajoulePerCubicMeterToJoulePerCubicMeter(): void
    {
        // 1 MJ/m³ = 1,000,000 J/m³
        $mjpm = MegajoulePerCubicMeter::of(NumberFactory::create('1'));
        $jpm = $mjpm->to(MetricCalorificValueUnit::JoulePerCubicMeter);

        self::assertEqualsWithDelta(1000000.0, (float) $jpm->getValue()->value(), 0.001);
    }

    public function testGigajoulePerCubicMeterToMegajoulePerCubicMeter(): void
    {
        // 1 GJ/m³ = 1000 MJ/m³
        $gjpm = GigajoulePerCubicMeter::of(NumberFactory::create('1'));
        $mjpm = $gjpm->to(MetricCalorificValueUnit::MegajoulePerCubicMeter);

        self::assertEqualsWithDelta(1000.0, (float) $mjpm->getValue()->value(), 0.001);
    }

    public function testNaturalGasCalorificValue(): void
    {
        // Natural gas typically has ~35-40 MJ/m³
        // Test 38 MJ/m³ conversion to kJ/m³
        $mjpm = MegajoulePerCubicMeter::of(NumberFactory::create('38'));
        $kjpm = $mjpm->to(MetricCalorificValueUnit::KilojoulePerCubicMeter);

        self::assertEqualsWithDelta(38000.0, (float) $kjpm->getValue()->value(), 0.001);
    }

    // ========== Imperial Unit Tests ==========

    public function testThermPerCubicFootToBTUPerCubicFoot(): void
    {
        // 1 therm/ft³ = 100,000 BTU/ft³
        $thermPerFt3 = ThermPerCubicFoot::of(NumberFactory::create('1'));
        $btuPerFt3 = $thermPerFt3->to(ImperialCalorificValueUnit::BTUPerCubicFoot);

        self::assertEqualsWithDelta(100000.0, (float) $btuPerFt3->getValue()->value(), 0.1);
    }

    public function testBTUPerCubicFootToThermPerCubicFoot(): void
    {
        // 100,000 BTU/ft³ = 1 therm/ft³
        $btuPerFt3 = BTUPerCubicFoot::of(NumberFactory::create('100000'));
        $thermPerFt3 = $btuPerFt3->to(ImperialCalorificValueUnit::ThermPerCubicFoot);

        self::assertEqualsWithDelta(1.0, (float) $thermPerFt3->getValue()->value(), 0.001);
    }

    // ========== Cross-System Conversions ==========

    public function testBTUPerCubicFootToJoulePerCubicMeter(): void
    {
        // 1 BTU/ft³ ≈ 37,258.946 J/m³
        $btuPerFt3 = BTUPerCubicFoot::of(NumberFactory::create('1'));
        $jpm = $btuPerFt3->to(MetricCalorificValueUnit::JoulePerCubicMeter);

        self::assertEqualsWithDelta(37258.946, (float) $jpm->getValue()->value(), 1.0);
    }

    public function testJoulePerCubicMeterToBTUPerCubicFoot(): void
    {
        // 37258.946 J/m³ ≈ 1 BTU/ft³
        $jpm = JoulePerCubicMeter::of(NumberFactory::create('37258.946'));
        $btuPerFt3 = $jpm->to(ImperialCalorificValueUnit::BTUPerCubicFoot);

        self::assertEqualsWithDelta(1.0, (float) $btuPerFt3->getValue()->value(), 0.001);
    }

    public function testMegajoulePerCubicMeterToBTUPerCubicFoot(): void
    {
        // 1 MJ/m³ = 1,000,000 J/m³ ≈ 26.839 BTU/ft³
        $mjpm = MegajoulePerCubicMeter::of(NumberFactory::create('1'));
        $btuPerFt3 = $mjpm->to(ImperialCalorificValueUnit::BTUPerCubicFoot);

        self::assertEqualsWithDelta(26.839, (float) $btuPerFt3->getValue()->value(), 0.01);
    }

    public function testNaturalGasMetricToImperial(): void
    {
        // Natural gas ~38 MJ/m³ ≈ 1019.9 BTU/ft³
        $mjpm = MegajoulePerCubicMeter::of(NumberFactory::create('38'));
        $btuPerFt3 = $mjpm->to(ImperialCalorificValueUnit::BTUPerCubicFoot);

        self::assertEqualsWithDelta(1019.9, (float) $btuPerFt3->getValue()->value(), 1.0);
    }

    // ========== Mid-Level Class Tests ==========

    public function testMetricCalorificValueCreation(): void
    {
        $metricCV = MetricCalorificValue::of(
            NumberFactory::create('38'),
            MetricCalorificValueUnit::MegajoulePerCubicMeter,
        );

        self::assertEquals('38', $metricCV->getValue()->value());
        self::assertSame(MetricCalorificValueUnit::MegajoulePerCubicMeter, $metricCV->getUnit());
    }

    public function testImperialCalorificValueCreation(): void
    {
        $imperialCV = ImperialCalorificValue::of(
            NumberFactory::create('1000'),
            ImperialCalorificValueUnit::BTUPerCubicFoot,
        );

        self::assertEquals('1000', $imperialCV->getValue()->value());
        self::assertSame(ImperialCalorificValueUnit::BTUPerCubicFoot, $imperialCV->getUnit());
    }

    public function testMetricCalorificValueConversion(): void
    {
        $metricCV = MetricCalorificValue::of(
            NumberFactory::create('1'),
            MetricCalorificValueUnit::MegajoulePerCubicMeter,
        );

        $converted = $metricCV->to(MetricCalorificValueUnit::KilojoulePerCubicMeter);
        self::assertEqualsWithDelta(1000.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericCalorificValueWithMetricUnit(): void
    {
        $cv = CalorificValue::of(
            NumberFactory::create('35'),
            MetricCalorificValueUnit::MegajoulePerCubicMeter,
        );

        self::assertEquals('35', $cv->getValue()->value());
        self::assertSame(MetricCalorificValueUnit::MegajoulePerCubicMeter, $cv->getUnit());
    }

    public function testGenericCalorificValueWithImperialUnit(): void
    {
        $cv = CalorificValue::of(
            NumberFactory::create('1000'),
            ImperialCalorificValueUnit::BTUPerCubicFoot,
        );

        self::assertEquals('1000', $cv->getValue()->value());
        self::assertSame(ImperialCalorificValueUnit::BTUPerCubicFoot, $cv->getUnit());
    }

    public function testGenericCalorificValueConversion(): void
    {
        $cv = CalorificValue::of(
            NumberFactory::create('1'),
            MetricCalorificValueUnit::GigajoulePerCubicMeter,
        );

        $converted = $cv->to(MetricCalorificValueUnit::MegajoulePerCubicMeter);
        self::assertEqualsWithDelta(1000.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Round-Trip Tests ==========

    public function testMetricRoundTrip(): void
    {
        $original = MegajoulePerCubicMeter::of(NumberFactory::create('38.5'));
        $toKJ = $original->to(MetricCalorificValueUnit::KilojoulePerCubicMeter);

        $kjValue = KilojoulePerCubicMeter::of($toKJ->getValue());
        $backToMJ = $kjValue->to(MetricCalorificValueUnit::MegajoulePerCubicMeter);

        self::assertEqualsWithDelta(38.5, (float) $backToMJ->getValue()->value(), 0.001);
    }

    public function testCrossSystemRoundTrip(): void
    {
        $original = MegajoulePerCubicMeter::of(NumberFactory::create('38'));
        $toBTU = $original->to(ImperialCalorificValueUnit::BTUPerCubicFoot);

        $btuQuantity = BTUPerCubicFoot::of($toBTU->getValue());
        $backToMJ = $btuQuantity->to(MetricCalorificValueUnit::MegajoulePerCubicMeter);

        self::assertEqualsWithDelta(38.0, (float) $backToMJ->getValue()->value(), 0.01);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $cv1 = MegajoulePerCubicMeter::of(NumberFactory::create('35'));
        $cv2 = MegajoulePerCubicMeter::of(NumberFactory::create('3'));

        $sum = $cv1->add($cv2);

        self::assertEqualsWithDelta(38.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $cv1 = MegajoulePerCubicMeter::of(NumberFactory::create('40'));
        $cv2 = MegajoulePerCubicMeter::of(NumberFactory::create('2'));

        $diff = $cv1->subtract($cv2);

        self::assertEqualsWithDelta(38.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $cv = MegajoulePerCubicMeter::of(NumberFactory::create('19'));
        $result = $cv->multiplyBy(NumberFactory::create('2'));

        self::assertEqualsWithDelta(38.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $cv = MegajoulePerCubicMeter::of(NumberFactory::create('76'));
        $result = $cv->divideBy(NumberFactory::create('2'));

        self::assertEqualsWithDelta(38.0, (float) $result->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $cv1 = MegajoulePerCubicMeter::of(NumberFactory::create('38'));
        $cv2 = MegajoulePerCubicMeter::of(NumberFactory::create('35'));

        self::assertTrue($cv1->isGreaterThan($cv2));
        self::assertFalse($cv1->isLessThan($cv2));
        self::assertFalse($cv1->equals($cv2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 MJ/m³ should be greater than 1 kJ/m³
        $mjpm = MegajoulePerCubicMeter::of(NumberFactory::create('1'));
        $kjpm = KilojoulePerCubicMeter::of(NumberFactory::create('1'));

        self::assertTrue($mjpm->isGreaterThan($kjpm));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 MJ/m³ = 1000 kJ/m³
        $mjpm = MegajoulePerCubicMeter::of(NumberFactory::create('1'));
        $kjpm = KilojoulePerCubicMeter::of(NumberFactory::create('1000'));

        self::assertTrue($mjpm->equals($kjpm));
    }
}
