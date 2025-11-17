<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Frequency;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Frequency\Frequency;
use Andante\Measurement\Quantity\Frequency\SI\BeatsPerMinute;
use Andante\Measurement\Quantity\Frequency\SI\Gigahertz;
use Andante\Measurement\Quantity\Frequency\SI\Hertz;
use Andante\Measurement\Quantity\Frequency\SI\Kilohertz;
use Andante\Measurement\Quantity\Frequency\SI\Megahertz;
use Andante\Measurement\Quantity\Frequency\SI\Millihertz;
use Andante\Measurement\Quantity\Frequency\SI\RevolutionPerMinute;
use Andante\Measurement\Quantity\Frequency\SI\RevolutionPerSecond;
use Andante\Measurement\Quantity\Frequency\SI\Terahertz;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Frequency\FrequencyUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for frequency conversions.
 *
 * Frequency [T⁻¹] represents the number of occurrences of a repeating event per unit of time.
 * Base unit: hertz (Hz), defined as 1/s = s⁻¹
 *
 * Common conversions:
 * - 1 mHz = 0.001 Hz
 * - 1 kHz = 1000 Hz
 * - 1 MHz = 1,000,000 Hz
 * - 1 GHz = 1,000,000,000 Hz
 * - 1 THz = 1,000,000,000,000 Hz
 * - 1 RPM = 1/60 Hz
 * - 1 RPS = 1 Hz
 * - 1 BPM = 1/60 Hz
 */
final class FrequencyConversionTest extends TestCase
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

    // ========== SI Unit Tests ==========

    public function testHertzToKilohertz(): void
    {
        // 1000 Hz = 1 kHz
        $hz = Hertz::of(NumberFactory::create('1000'));
        $kHz = $hz->to(FrequencyUnit::Kilohertz);

        self::assertEqualsWithDelta(1.0, (float) $kHz->getValue()->value(), 0.001);
    }

    public function testKilohertzToHertz(): void
    {
        // 1 kHz = 1000 Hz
        $kHz = Kilohertz::of(NumberFactory::create('1'));
        $hz = $kHz->to(FrequencyUnit::Hertz);

        self::assertEqualsWithDelta(1000.0, (float) $hz->getValue()->value(), 0.001);
    }

    public function testHertzToMegahertz(): void
    {
        // 1,000,000 Hz = 1 MHz
        $hz = Hertz::of(NumberFactory::create('1000000'));
        $MHz = $hz->to(FrequencyUnit::Megahertz);

        self::assertEqualsWithDelta(1.0, (float) $MHz->getValue()->value(), 0.001);
    }

    public function testMegahertzToHertz(): void
    {
        // 1 MHz = 1,000,000 Hz
        $MHz = Megahertz::of(NumberFactory::create('1'));
        $hz = $MHz->to(FrequencyUnit::Hertz);

        self::assertEqualsWithDelta(1000000.0, (float) $hz->getValue()->value(), 0.001);
    }

    public function testHertzToGigahertz(): void
    {
        // 1,000,000,000 Hz = 1 GHz
        $hz = Hertz::of(NumberFactory::create('1000000000'));
        $GHz = $hz->to(FrequencyUnit::Gigahertz);

        self::assertEqualsWithDelta(1.0, (float) $GHz->getValue()->value(), 0.001);
    }

    public function testGigahertzToHertz(): void
    {
        // 1 GHz = 1,000,000,000 Hz
        $GHz = Gigahertz::of(NumberFactory::create('1'));
        $hz = $GHz->to(FrequencyUnit::Hertz);

        self::assertEqualsWithDelta(1000000000.0, (float) $hz->getValue()->value(), 0.001);
    }

    public function testHertzToTerahertz(): void
    {
        // 1,000,000,000,000 Hz = 1 THz
        $hz = Hertz::of(NumberFactory::create('1000000000000'));
        $THz = $hz->to(FrequencyUnit::Terahertz);

        self::assertEqualsWithDelta(1.0, (float) $THz->getValue()->value(), 0.001);
    }

    public function testTerahertzToHertz(): void
    {
        // 1 THz = 1,000,000,000,000 Hz
        $THz = Terahertz::of(NumberFactory::create('1'));
        $hz = $THz->to(FrequencyUnit::Hertz);

        self::assertEqualsWithDelta(1000000000000.0, (float) $hz->getValue()->value(), 0.001);
    }

    public function testHertzToMillihertz(): void
    {
        // 1 Hz = 1000 mHz
        $hz = Hertz::of(NumberFactory::create('1'));
        $mHz = $hz->to(FrequencyUnit::Millihertz);

        self::assertEqualsWithDelta(1000.0, (float) $mHz->getValue()->value(), 0.001);
    }

    public function testMillihertzToHertz(): void
    {
        // 1000 mHz = 1 Hz
        $mHz = Millihertz::of(NumberFactory::create('1000'));
        $hz = $mHz->to(FrequencyUnit::Hertz);

        self::assertEqualsWithDelta(1.0, (float) $hz->getValue()->value(), 0.001);
    }

    public function testKilohertzToMegahertz(): void
    {
        // 1000 kHz = 1 MHz
        $kHz = Kilohertz::of(NumberFactory::create('1000'));
        $MHz = $kHz->to(FrequencyUnit::Megahertz);

        self::assertEqualsWithDelta(1.0, (float) $MHz->getValue()->value(), 0.001);
    }

    public function testMegahertzToGigahertz(): void
    {
        // 1000 MHz = 1 GHz
        $MHz = Megahertz::of(NumberFactory::create('1000'));
        $GHz = $MHz->to(FrequencyUnit::Gigahertz);

        self::assertEqualsWithDelta(1.0, (float) $GHz->getValue()->value(), 0.001);
    }

    public function testGigahertzToTerahertz(): void
    {
        // 1000 GHz = 1 THz
        $GHz = Gigahertz::of(NumberFactory::create('1000'));
        $THz = $GHz->to(FrequencyUnit::Terahertz);

        self::assertEqualsWithDelta(1.0, (float) $THz->getValue()->value(), 0.001);
    }

    // ========== Non-SI Unit Tests ==========

    public function testHertzToRPM(): void
    {
        // 1 Hz = 60 RPM
        $hz = Hertz::of(NumberFactory::create('1'));
        $rpm = $hz->to(FrequencyUnit::RevolutionPerMinute);

        self::assertEqualsWithDelta(60.0, (float) $rpm->getValue()->value(), 0.001);
    }

    public function testRPMToHertz(): void
    {
        // 60 RPM = 1 Hz
        $rpm = RevolutionPerMinute::of(NumberFactory::create('60'));
        $hz = $rpm->to(FrequencyUnit::Hertz);

        self::assertEqualsWithDelta(1.0, (float) $hz->getValue()->value(), 0.001);
    }

    public function testHertzToRPS(): void
    {
        // 1 Hz = 1 RPS
        $hz = Hertz::of(NumberFactory::create('1'));
        $rps = $hz->to(FrequencyUnit::RevolutionPerSecond);

        self::assertEqualsWithDelta(1.0, (float) $rps->getValue()->value(), 0.001);
    }

    public function testRPSToHertz(): void
    {
        // 1 RPS = 1 Hz
        $rps = RevolutionPerSecond::of(NumberFactory::create('1'));
        $hz = $rps->to(FrequencyUnit::Hertz);

        self::assertEqualsWithDelta(1.0, (float) $hz->getValue()->value(), 0.001);
    }

    public function testRPMToRPS(): void
    {
        // 60 RPM = 1 RPS
        $rpm = RevolutionPerMinute::of(NumberFactory::create('60'));
        $rps = $rpm->to(FrequencyUnit::RevolutionPerSecond);

        self::assertEqualsWithDelta(1.0, (float) $rps->getValue()->value(), 0.001);
    }

    public function testRPSToRPM(): void
    {
        // 1 RPS = 60 RPM
        $rps = RevolutionPerSecond::of(NumberFactory::create('1'));
        $rpm = $rps->to(FrequencyUnit::RevolutionPerMinute);

        self::assertEqualsWithDelta(60.0, (float) $rpm->getValue()->value(), 0.001);
    }

    public function testHertzToBPM(): void
    {
        // 1 Hz = 60 BPM
        $hz = Hertz::of(NumberFactory::create('1'));
        $bpm = $hz->to(FrequencyUnit::BeatsPerMinute);

        self::assertEqualsWithDelta(60.0, (float) $bpm->getValue()->value(), 0.001);
    }

    public function testBPMToHertz(): void
    {
        // 60 BPM = 1 Hz
        $bpm = BeatsPerMinute::of(NumberFactory::create('60'));
        $hz = $bpm->to(FrequencyUnit::Hertz);

        self::assertEqualsWithDelta(1.0, (float) $hz->getValue()->value(), 0.001);
    }

    public function testBPMToRPM(): void
    {
        // BPM and RPM have same conversion factor, so 1 BPM = 1 RPM
        $bpm = BeatsPerMinute::of(NumberFactory::create('120'));
        $rpm = $bpm->to(FrequencyUnit::RevolutionPerMinute);

        self::assertEqualsWithDelta(120.0, (float) $rpm->getValue()->value(), 0.001);
    }

    // ========== Real-World Scenario Tests ==========

    public function testCPUClockSpeed(): void
    {
        // A modern CPU: 3.5 GHz
        $cpu = Gigahertz::of(NumberFactory::create('3.5'));

        // Convert to MHz
        $MHz = $cpu->to(FrequencyUnit::Megahertz);
        self::assertEqualsWithDelta(3500.0, (float) $MHz->getValue()->value(), 0.001);

        // Convert to Hz
        $hz = $cpu->to(FrequencyUnit::Hertz);
        self::assertEqualsWithDelta(3500000000.0, (float) $hz->getValue()->value(), 0.001);
    }

    public function testFMRadioFrequency(): void
    {
        // FM radio station at 99.5 MHz
        $station = Megahertz::of(NumberFactory::create('99.5'));

        // Convert to kHz
        $kHz = $station->to(FrequencyUnit::Kilohertz);
        self::assertEqualsWithDelta(99500.0, (float) $kHz->getValue()->value(), 0.001);

        // Convert to Hz
        $hz = $station->to(FrequencyUnit::Hertz);
        self::assertEqualsWithDelta(99500000.0, (float) $hz->getValue()->value(), 0.001);
    }

    public function testHeartRate(): void
    {
        // Resting heart rate: 72 BPM
        $heartRate = BeatsPerMinute::of(NumberFactory::create('72'));

        // Convert to Hz
        $hz = $heartRate->to(FrequencyUnit::Hertz);
        self::assertEqualsWithDelta(1.2, (float) $hz->getValue()->value(), 0.001);

        // Convert to millihertz
        $mHz = $heartRate->to(FrequencyUnit::Millihertz);
        self::assertEqualsWithDelta(1200.0, (float) $mHz->getValue()->value(), 0.001);
    }

    public function testEngineSpeed(): void
    {
        // Engine at 3000 RPM
        $engine = RevolutionPerMinute::of(NumberFactory::create('3000'));

        // Convert to RPS
        $rps = $engine->to(FrequencyUnit::RevolutionPerSecond);
        self::assertEqualsWithDelta(50.0, (float) $rps->getValue()->value(), 0.001);

        // Convert to Hz
        $hz = $engine->to(FrequencyUnit::Hertz);
        self::assertEqualsWithDelta(50.0, (float) $hz->getValue()->value(), 0.001);
    }

    public function testAudioFrequency(): void
    {
        // Concert pitch A: 440 Hz
        $concertA = Hertz::of(NumberFactory::create('440'));

        // Convert to kHz
        $kHz = $concertA->to(FrequencyUnit::Kilohertz);
        self::assertEqualsWithDelta(0.44, (float) $kHz->getValue()->value(), 0.001);
    }

    public function testInfraredLight(): void
    {
        // Infrared light: 300 THz
        $ir = Terahertz::of(NumberFactory::create('300'));

        // Convert to GHz
        $GHz = $ir->to(FrequencyUnit::Gigahertz);
        self::assertEqualsWithDelta(300000.0, (float) $GHz->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericFrequencyWithHertz(): void
    {
        $frequency = Frequency::of(
            NumberFactory::create('1000'),
            FrequencyUnit::Hertz,
        );

        self::assertEquals('1000', $frequency->getValue()->value());
        self::assertSame(FrequencyUnit::Hertz, $frequency->getUnit());
    }

    public function testGenericFrequencyWithKilohertz(): void
    {
        $frequency = Frequency::of(
            NumberFactory::create('2.4'),
            FrequencyUnit::Gigahertz,
        );

        self::assertEquals('2.4', $frequency->getValue()->value());
        self::assertSame(FrequencyUnit::Gigahertz, $frequency->getUnit());
    }

    public function testGenericFrequencyWithRPM(): void
    {
        $frequency = Frequency::of(
            NumberFactory::create('3000'),
            FrequencyUnit::RevolutionPerMinute,
        );

        self::assertEquals('3000', $frequency->getValue()->value());
        self::assertSame(FrequencyUnit::RevolutionPerMinute, $frequency->getUnit());
    }

    public function testGenericFrequencyConversion(): void
    {
        $frequency = Frequency::of(
            NumberFactory::create('1'),
            FrequencyUnit::Kilohertz,
        );

        $converted = $frequency->to(FrequencyUnit::Hertz);
        self::assertEqualsWithDelta(1000.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Round-Trip Tests ==========

    public function testHertzRoundTrip(): void
    {
        $original = Hertz::of(NumberFactory::create('1000'));
        $toKHz = $original->to(FrequencyUnit::Kilohertz);

        $kHzQuantity = Kilohertz::of($toKHz->getValue());
        $backToHz = $kHzQuantity->to(FrequencyUnit::Hertz);

        self::assertEqualsWithDelta(1000.0, (float) $backToHz->getValue()->value(), 0.001);
    }

    public function testRPMRoundTrip(): void
    {
        $original = RevolutionPerMinute::of(NumberFactory::create('3000'));
        $toHz = $original->to(FrequencyUnit::Hertz);

        $hzQuantity = Hertz::of($toHz->getValue());
        $backToRPM = $hzQuantity->to(FrequencyUnit::RevolutionPerMinute);

        self::assertEqualsWithDelta(3000.0, (float) $backToRPM->getValue()->value(), 0.001);
    }

    public function testBPMRoundTrip(): void
    {
        $original = BeatsPerMinute::of(NumberFactory::create('120'));
        $toHz = $original->to(FrequencyUnit::Hertz);

        $hzQuantity = Hertz::of($toHz->getValue());
        $backToBPM = $hzQuantity->to(FrequencyUnit::BeatsPerMinute);

        self::assertEqualsWithDelta(120.0, (float) $backToBPM->getValue()->value(), 0.001);
    }

    public function testGigahertzRoundTrip(): void
    {
        $original = Gigahertz::of(NumberFactory::create('2.4'));
        $toMHz = $original->to(FrequencyUnit::Megahertz);

        $MHzQuantity = Megahertz::of($toMHz->getValue());
        $backToGHz = $MHzQuantity->to(FrequencyUnit::Gigahertz);

        self::assertEqualsWithDelta(2.4, (float) $backToGHz->getValue()->value(), 0.001);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $f1 = Hertz::of(NumberFactory::create('500'));
        $f2 = Hertz::of(NumberFactory::create('300'));

        $sum = $f1->add($f2);

        self::assertEqualsWithDelta(800.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $f1 = Kilohertz::of(NumberFactory::create('5'));
        $f2 = Kilohertz::of(NumberFactory::create('2'));

        $diff = $f1->subtract($f2);

        self::assertEqualsWithDelta(3.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $frequency = Kilohertz::of(NumberFactory::create('2'));
        $result = $frequency->multiplyBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(6.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $frequency = Megahertz::of(NumberFactory::create('300'));
        $result = $frequency->divideBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(100.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testAdditionAcrossUnits(): void
    {
        // 1 kHz + 500 Hz = 1500 Hz
        $kHz = Kilohertz::of(NumberFactory::create('1'));
        $hz = Hertz::of(NumberFactory::create('500'));

        $sum = $kHz->add($hz);

        // Result is in kHz (first operand's unit)
        self::assertEqualsWithDelta(1.5, (float) $sum->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $f1 = Hertz::of(NumberFactory::create('1000'));
        $f2 = Hertz::of(NumberFactory::create('800'));

        self::assertTrue($f1->isGreaterThan($f2));
        self::assertFalse($f1->isLessThan($f2));
        self::assertFalse($f1->equals($f2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 kHz > 500 Hz
        $kHz = Kilohertz::of(NumberFactory::create('1'));
        $hz = Hertz::of(NumberFactory::create('500'));

        self::assertTrue($kHz->isGreaterThan($hz));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 kHz = 1000 Hz
        $kHz = Kilohertz::of(NumberFactory::create('1'));
        $hz = Hertz::of(NumberFactory::create('1000'));

        self::assertTrue($kHz->equals($hz));
    }

    public function testCrossUnitComparison(): void
    {
        // 1 kHz converted to Hz should equal 1000 Hz
        $kHzToHz = Kilohertz::of(NumberFactory::create('1'))->to(FrequencyUnit::Hertz);
        $direct = Hertz::of(NumberFactory::create('1000'));

        self::assertEqualsWithDelta(
            (float) $kHzToHz->getValue()->value(),
            (float) $direct->getValue()->value(),
            0.001,
        );
    }

    public function testRPMAndBPMEquivalence(): void
    {
        // RPM and BPM have the same conversion factor
        $rpm = RevolutionPerMinute::of(NumberFactory::create('120'));
        $bpm = BeatsPerMinute::of(NumberFactory::create('120'));

        // Both should convert to the same Hz value
        $rpmToHz = $rpm->to(FrequencyUnit::Hertz);
        $bpmToHz = $bpm->to(FrequencyUnit::Hertz);

        self::assertEqualsWithDelta(
            (float) $rpmToHz->getValue()->value(),
            (float) $bpmToHz->getValue()->value(),
            0.001,
        );
    }
}
