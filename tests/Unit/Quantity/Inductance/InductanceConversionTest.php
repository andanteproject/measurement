<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Inductance;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Inductance\Inductance;
use Andante\Measurement\Quantity\Inductance\SI\Henry;
use Andante\Measurement\Quantity\Inductance\SI\Microhenry;
use Andante\Measurement\Quantity\Inductance\SI\Millihenry;
use Andante\Measurement\Quantity\Inductance\SI\Nanohenry;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Inductance\InductanceUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for inductance conversions.
 *
 * Inductance [L²M¹T⁻²I⁻²] represents the property of an electrical conductor
 * to oppose changes in current.
 * Base unit: henry (H), defined as 1 V⋅s/A
 *
 * Common conversions:
 * - 1 H = 1000 mH = 1,000,000 μH = 1,000,000,000 nH
 * - 1 mH = 1000 μH
 * - 1 μH = 1000 nH
 */
final class InductanceConversionTest extends TestCase
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

    public function testHenryToMillihenry(): void
    {
        // 1 H = 1000 mH
        $henry = Henry::of(NumberFactory::create('1'));
        $millihenry = $henry->to(InductanceUnit::Millihenry);

        self::assertEqualsWithDelta(1000.0, (float) $millihenry->getValue()->value(), 0.001);
    }

    public function testMillihenryToHenry(): void
    {
        // 1000 mH = 1 H
        $millihenry = Millihenry::of(NumberFactory::create('1000'));
        $henry = $millihenry->to(InductanceUnit::Henry);

        self::assertEqualsWithDelta(1.0, (float) $henry->getValue()->value(), 0.001);
    }

    public function testHenryToMicrohenry(): void
    {
        // 1 H = 1,000,000 μH
        $henry = Henry::of(NumberFactory::create('1'));
        $microhenry = $henry->to(InductanceUnit::Microhenry);

        self::assertEqualsWithDelta(1000000.0, (float) $microhenry->getValue()->value(), 0.001);
    }

    public function testMicrohenryToHenry(): void
    {
        // 1,000,000 μH = 1 H
        $microhenry = Microhenry::of(NumberFactory::create('1000000'));
        $henry = $microhenry->to(InductanceUnit::Henry);

        self::assertEqualsWithDelta(1.0, (float) $henry->getValue()->value(), 0.001);
    }

    public function testHenryToNanohenry(): void
    {
        // 1 H = 1,000,000,000 nH
        $henry = Henry::of(NumberFactory::create('1'));
        $nanohenry = $henry->to(InductanceUnit::Nanohenry);

        self::assertEqualsWithDelta(1000000000.0, (float) $nanohenry->getValue()->value(), 0.001);
    }

    public function testNanohenryToHenry(): void
    {
        // 1,000,000,000 nH = 1 H
        $nanohenry = Nanohenry::of(NumberFactory::create('1000000000'));
        $henry = $nanohenry->to(InductanceUnit::Henry);

        self::assertEqualsWithDelta(1.0, (float) $henry->getValue()->value(), 0.001);
    }

    public function testMillihenryToMicrohenry(): void
    {
        // 1 mH = 1000 μH
        $millihenry = Millihenry::of(NumberFactory::create('1'));
        $microhenry = $millihenry->to(InductanceUnit::Microhenry);

        self::assertEqualsWithDelta(1000.0, (float) $microhenry->getValue()->value(), 0.001);
    }

    public function testMicrohenryToMillihenry(): void
    {
        // 1000 μH = 1 mH
        $microhenry = Microhenry::of(NumberFactory::create('1000'));
        $millihenry = $microhenry->to(InductanceUnit::Millihenry);

        self::assertEqualsWithDelta(1.0, (float) $millihenry->getValue()->value(), 0.001);
    }

    public function testMicrohenryToNanohenry(): void
    {
        // 1 μH = 1000 nH
        $microhenry = Microhenry::of(NumberFactory::create('1'));
        $nanohenry = $microhenry->to(InductanceUnit::Nanohenry);

        self::assertEqualsWithDelta(1000.0, (float) $nanohenry->getValue()->value(), 0.001);
    }

    public function testNanohenryToMicrohenry(): void
    {
        // 1000 nH = 1 μH
        $nanohenry = Nanohenry::of(NumberFactory::create('1000'));
        $microhenry = $nanohenry->to(InductanceUnit::Microhenry);

        self::assertEqualsWithDelta(1.0, (float) $microhenry->getValue()->value(), 0.001);
    }

    // ========== Real-World Scenario Tests ==========

    public function testPowerSupplyInductor(): void
    {
        // Typical power supply inductor: 100 μH
        $inductor = Microhenry::of(NumberFactory::create('100'));
        $millihenry = $inductor->to(InductanceUnit::Millihenry);

        self::assertEqualsWithDelta(0.1, (float) $millihenry->getValue()->value(), 0.001);
    }

    public function testRFInductor(): void
    {
        // RF inductor: 10 nH
        $rfInductor = Nanohenry::of(NumberFactory::create('10'));
        $microhenry = $rfInductor->to(InductanceUnit::Microhenry);

        self::assertEqualsWithDelta(0.01, (float) $microhenry->getValue()->value(), 0.001);
    }

    public function testAudioCrossoverInductor(): void
    {
        // Audio crossover inductor: 1.5 mH
        $crossover = Millihenry::of(NumberFactory::create('1.5'));
        $henry = $crossover->to(InductanceUnit::Henry);

        self::assertEqualsWithDelta(0.0015, (float) $henry->getValue()->value(), 0.0001);
    }

    public function testTransformerPrimaryInductance(): void
    {
        // Transformer primary: 10 H
        $transformer = Henry::of(NumberFactory::create('10'));
        $millihenry = $transformer->to(InductanceUnit::Millihenry);

        self::assertEqualsWithDelta(10000.0, (float) $millihenry->getValue()->value(), 0.001);
    }

    public function testDCDCConverterInductor(): void
    {
        // DC-DC converter: 47 μH
        $dcdc = Microhenry::of(NumberFactory::create('47'));
        $nanohenry = $dcdc->to(InductanceUnit::Nanohenry);

        self::assertEqualsWithDelta(47000.0, (float) $nanohenry->getValue()->value(), 0.001);
    }

    public function testMotorWindingInductance(): void
    {
        // Motor winding: 50 mH
        $motor = Millihenry::of(NumberFactory::create('50'));
        $microhenry = $motor->to(InductanceUnit::Microhenry);

        self::assertEqualsWithDelta(50000.0, (float) $microhenry->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericInductanceWithHenry(): void
    {
        $inductance = Inductance::of(
            NumberFactory::create('1.5'),
            InductanceUnit::Henry,
        );

        self::assertEquals('1.5', $inductance->getValue()->value());
        self::assertSame(InductanceUnit::Henry, $inductance->getUnit());
    }

    public function testGenericInductanceWithMillihenry(): void
    {
        $inductance = Inductance::of(
            NumberFactory::create('100'),
            InductanceUnit::Millihenry,
        );

        self::assertEquals('100', $inductance->getValue()->value());
        self::assertSame(InductanceUnit::Millihenry, $inductance->getUnit());
    }

    public function testGenericInductanceWithMicrohenry(): void
    {
        $inductance = Inductance::of(
            NumberFactory::create('470'),
            InductanceUnit::Microhenry,
        );

        self::assertEquals('470', $inductance->getValue()->value());
        self::assertSame(InductanceUnit::Microhenry, $inductance->getUnit());
    }

    public function testGenericInductanceConversion(): void
    {
        $inductance = Inductance::of(
            NumberFactory::create('2.2'),
            InductanceUnit::Millihenry,
        );

        $converted = $inductance->to(InductanceUnit::Microhenry);
        self::assertEqualsWithDelta(2200.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Round-Trip Tests ==========

    public function testHenryRoundTrip(): void
    {
        $original = Henry::of(NumberFactory::create('0.5'));
        $toMillihenry = $original->to(InductanceUnit::Millihenry);

        $mHQuantity = Millihenry::of($toMillihenry->getValue());
        $backToHenry = $mHQuantity->to(InductanceUnit::Henry);

        self::assertEqualsWithDelta(0.5, (float) $backToHenry->getValue()->value(), 0.001);
    }

    public function testMillihenryRoundTrip(): void
    {
        $original = Millihenry::of(NumberFactory::create('100'));
        $toMicrohenry = $original->to(InductanceUnit::Microhenry);

        $uHQuantity = Microhenry::of($toMicrohenry->getValue());
        $backToMillihenry = $uHQuantity->to(InductanceUnit::Millihenry);

        self::assertEqualsWithDelta(100.0, (float) $backToMillihenry->getValue()->value(), 0.001);
    }

    public function testMicrohenryRoundTrip(): void
    {
        $original = Microhenry::of(NumberFactory::create('47'));
        $toNanohenry = $original->to(InductanceUnit::Nanohenry);

        $nHQuantity = Nanohenry::of($toNanohenry->getValue());
        $backToMicrohenry = $nHQuantity->to(InductanceUnit::Microhenry);

        self::assertEqualsWithDelta(47.0, (float) $backToMicrohenry->getValue()->value(), 0.001);
    }

    public function testNanohenryRoundTrip(): void
    {
        $original = Nanohenry::of(NumberFactory::create('100'));
        $toMicrohenry = $original->to(InductanceUnit::Microhenry);

        $uHQuantity = Microhenry::of($toMicrohenry->getValue());
        $backToNanohenry = $uHQuantity->to(InductanceUnit::Nanohenry);

        self::assertEqualsWithDelta(100.0, (float) $backToNanohenry->getValue()->value(), 0.001);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $l1 = Millihenry::of(NumberFactory::create('100'));
        $l2 = Millihenry::of(NumberFactory::create('200'));

        $sum = $l1->add($l2);

        self::assertEqualsWithDelta(300.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $l1 = Microhenry::of(NumberFactory::create('500'));
        $l2 = Microhenry::of(NumberFactory::create('200'));

        $diff = $l1->subtract($l2);

        self::assertEqualsWithDelta(300.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $inductance = Millihenry::of(NumberFactory::create('100'));
        $result = $inductance->multiplyBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(300.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $inductance = Microhenry::of(NumberFactory::create('120'));
        $result = $inductance->divideBy(NumberFactory::create('4'));

        self::assertEqualsWithDelta(30.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testAdditionAcrossUnits(): void
    {
        // 1 mH + 500 μH = 1.5 mH
        $millihenry = Millihenry::of(NumberFactory::create('1'));
        $microhenry = Microhenry::of(NumberFactory::create('500'));

        $sum = $millihenry->add($microhenry);

        // Result is in mH (first operand's unit)
        self::assertEqualsWithDelta(1.5, (float) $sum->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $l1 = Millihenry::of(NumberFactory::create('100'));
        $l2 = Millihenry::of(NumberFactory::create('50'));

        self::assertTrue($l1->isGreaterThan($l2));
        self::assertFalse($l1->isLessThan($l2));
        self::assertFalse($l1->equals($l2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 mH > 500 μH
        $millihenry = Millihenry::of(NumberFactory::create('1'));
        $microhenry = Microhenry::of(NumberFactory::create('500'));

        self::assertTrue($millihenry->isGreaterThan($microhenry));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 mH = 1000 μH
        $millihenry = Millihenry::of(NumberFactory::create('1'));
        $microhenry = Microhenry::of(NumberFactory::create('1000'));

        self::assertTrue($millihenry->equals($microhenry));
    }

    public function testCrossUnitComparison(): void
    {
        // 1 mH converted to μH should equal 1000 μH
        $mHToUH = Millihenry::of(NumberFactory::create('1'))->to(InductanceUnit::Microhenry);
        $direct = Microhenry::of(NumberFactory::create('1000'));

        self::assertEqualsWithDelta(
            (float) $mHToUH->getValue()->value(),
            (float) $direct->getValue()->value(),
            0.001,
        );
    }

    // ========== Auto-Scale Tests ==========

    public function testAutoScaleFromNanohenry(): void
    {
        // 1000 nH should auto-scale to 1 μH
        $nanohenry = Nanohenry::of(NumberFactory::create('1000'));
        $scaled = $nanohenry->autoScale();

        self::assertSame(InductanceUnit::Microhenry, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromMicrohenry(): void
    {
        // 1000 μH should auto-scale to 1 mH
        $microhenry = Microhenry::of(NumberFactory::create('1000'));
        $scaled = $microhenry->autoScale();

        self::assertSame(InductanceUnit::Millihenry, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromMillihenry(): void
    {
        // 1000 mH should auto-scale to 1 H
        $millihenry = Millihenry::of(NumberFactory::create('1000'));
        $scaled = $millihenry->autoScale();

        self::assertSame(InductanceUnit::Henry, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromHenry(): void
    {
        // 0.001 H should auto-scale to 1 mH
        $henry = Henry::of(NumberFactory::create('0.001'));
        $scaled = $henry->autoScale();

        self::assertSame(InductanceUnit::Millihenry, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }
}
