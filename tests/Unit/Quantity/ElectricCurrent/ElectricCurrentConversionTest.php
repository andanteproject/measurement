<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\ElectricCurrent;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\ElectricCurrent\ElectricCurrent;
use Andante\Measurement\Quantity\ElectricCurrent\SI\Ampere;
use Andante\Measurement\Quantity\ElectricCurrent\SI\Kiloampere;
use Andante\Measurement\Quantity\ElectricCurrent\SI\Microampere;
use Andante\Measurement\Quantity\ElectricCurrent\SI\Milliampere;
use Andante\Measurement\Quantity\ElectricCurrent\SI\Nanoampere;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\ElectricCurrent\ElectricCurrentUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for electric current conversions.
 *
 * Electric Current [I¹] is one of the seven SI base quantities.
 * Base unit: ampere (A)
 *
 * Common conversions:
 * - 1 kA = 1000 A
 * - 1 mA = 0.001 A
 * - 1 μA = 0.000001 A
 * - 1 nA = 0.000000001 A
 */
final class ElectricCurrentConversionTest extends TestCase
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

    public function testAmpereToKiloampere(): void
    {
        // 1000 A = 1 kA
        $ampere = Ampere::of(NumberFactory::create('1000'));
        $kiloampere = $ampere->to(ElectricCurrentUnit::Kiloampere);

        self::assertEqualsWithDelta(1.0, (float) $kiloampere->getValue()->value(), 0.001);
    }

    public function testKiloampereToAmpere(): void
    {
        // 1 kA = 1000 A
        $kiloampere = Kiloampere::of(NumberFactory::create('1'));
        $ampere = $kiloampere->to(ElectricCurrentUnit::Ampere);

        self::assertEqualsWithDelta(1000.0, (float) $ampere->getValue()->value(), 0.001);
    }

    public function testAmpereToMilliampere(): void
    {
        // 1 A = 1000 mA
        $ampere = Ampere::of(NumberFactory::create('1'));
        $milliampere = $ampere->to(ElectricCurrentUnit::Milliampere);

        self::assertEqualsWithDelta(1000.0, (float) $milliampere->getValue()->value(), 0.001);
    }

    public function testMilliampereToAmpere(): void
    {
        // 1000 mA = 1 A
        $milliampere = Milliampere::of(NumberFactory::create('1000'));
        $ampere = $milliampere->to(ElectricCurrentUnit::Ampere);

        self::assertEqualsWithDelta(1.0, (float) $ampere->getValue()->value(), 0.001);
    }

    public function testAmpereToMicroampere(): void
    {
        // 1 A = 1,000,000 μA
        $ampere = Ampere::of(NumberFactory::create('1'));
        $microampere = $ampere->to(ElectricCurrentUnit::Microampere);

        self::assertEqualsWithDelta(1000000.0, (float) $microampere->getValue()->value(), 0.001);
    }

    public function testMicroampereToAmpere(): void
    {
        // 1,000,000 μA = 1 A
        $microampere = Microampere::of(NumberFactory::create('1000000'));
        $ampere = $microampere->to(ElectricCurrentUnit::Ampere);

        self::assertEqualsWithDelta(1.0, (float) $ampere->getValue()->value(), 0.001);
    }

    public function testAmpereToNanoampere(): void
    {
        // 1 A = 1,000,000,000 nA
        $ampere = Ampere::of(NumberFactory::create('1'));
        $nanoampere = $ampere->to(ElectricCurrentUnit::Nanoampere);

        self::assertEqualsWithDelta(1000000000.0, (float) $nanoampere->getValue()->value(), 0.001);
    }

    public function testNanoampereToAmpere(): void
    {
        // 1,000,000,000 nA = 1 A
        $nanoampere = Nanoampere::of(NumberFactory::create('1000000000'));
        $ampere = $nanoampere->to(ElectricCurrentUnit::Ampere);

        self::assertEqualsWithDelta(1.0, (float) $ampere->getValue()->value(), 0.001);
    }

    public function testMilliampereToMicroampere(): void
    {
        // 1 mA = 1000 μA
        $milliampere = Milliampere::of(NumberFactory::create('1'));
        $microampere = $milliampere->to(ElectricCurrentUnit::Microampere);

        self::assertEqualsWithDelta(1000.0, (float) $microampere->getValue()->value(), 0.001);
    }

    public function testMicroampereToNanoampere(): void
    {
        // 1 μA = 1000 nA
        $microampere = Microampere::of(NumberFactory::create('1'));
        $nanoampere = $microampere->to(ElectricCurrentUnit::Nanoampere);

        self::assertEqualsWithDelta(1000.0, (float) $nanoampere->getValue()->value(), 0.001);
    }

    public function testKiloampereToMilliampere(): void
    {
        // 1 kA = 1,000,000 mA
        $kiloampere = Kiloampere::of(NumberFactory::create('1'));
        $milliampere = $kiloampere->to(ElectricCurrentUnit::Milliampere);

        self::assertEqualsWithDelta(1000000.0, (float) $milliampere->getValue()->value(), 0.001);
    }

    // ========== Real-World Scenario Tests ==========

    public function testUSBChargingCurrent(): void
    {
        // USB 2.0 max current: 500 mA
        $usb2 = Milliampere::of(NumberFactory::create('500'));
        $ampere = $usb2->to(ElectricCurrentUnit::Ampere);

        self::assertEqualsWithDelta(0.5, (float) $ampere->getValue()->value(), 0.001);
    }

    public function testUSBFastChargingCurrent(): void
    {
        // USB Power Delivery: 3 A
        $usbPD = Ampere::of(NumberFactory::create('3'));
        $milliampere = $usbPD->to(ElectricCurrentUnit::Milliampere);

        self::assertEqualsWithDelta(3000.0, (float) $milliampere->getValue()->value(), 0.001);
    }

    public function testLEDCurrent(): void
    {
        // Typical LED: 20 mA
        $led = Milliampere::of(NumberFactory::create('20'));
        $microampere = $led->to(ElectricCurrentUnit::Microampere);

        self::assertEqualsWithDelta(20000.0, (float) $microampere->getValue()->value(), 0.001);
    }

    public function testSensorLeakageCurrent(): void
    {
        // Ultra-low power sensor: 100 nA
        $sensor = Nanoampere::of(NumberFactory::create('100'));
        $microampere = $sensor->to(ElectricCurrentUnit::Microampere);

        self::assertEqualsWithDelta(0.1, (float) $microampere->getValue()->value(), 0.001);
    }

    public function testWeldingCurrent(): void
    {
        // Industrial welding: 500 A
        $welding = Ampere::of(NumberFactory::create('500'));
        $kiloampere = $welding->to(ElectricCurrentUnit::Kiloampere);

        self::assertEqualsWithDelta(0.5, (float) $kiloampere->getValue()->value(), 0.001);
    }

    public function testLightningStrikeCurrent(): void
    {
        // Typical lightning: 30 kA
        $lightning = Kiloampere::of(NumberFactory::create('30'));
        $ampere = $lightning->to(ElectricCurrentUnit::Ampere);

        self::assertEqualsWithDelta(30000.0, (float) $ampere->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericElectricCurrentWithAmpere(): void
    {
        $current = ElectricCurrent::of(
            NumberFactory::create('5'),
            ElectricCurrentUnit::Ampere,
        );

        self::assertEquals('5', $current->getValue()->value());
        self::assertSame(ElectricCurrentUnit::Ampere, $current->getUnit());
    }

    public function testGenericElectricCurrentWithMilliampere(): void
    {
        $current = ElectricCurrent::of(
            NumberFactory::create('250'),
            ElectricCurrentUnit::Milliampere,
        );

        self::assertEquals('250', $current->getValue()->value());
        self::assertSame(ElectricCurrentUnit::Milliampere, $current->getUnit());
    }

    public function testGenericElectricCurrentWithMicroampere(): void
    {
        $current = ElectricCurrent::of(
            NumberFactory::create('50'),
            ElectricCurrentUnit::Microampere,
        );

        self::assertEquals('50', $current->getValue()->value());
        self::assertSame(ElectricCurrentUnit::Microampere, $current->getUnit());
    }

    public function testGenericElectricCurrentConversion(): void
    {
        $current = ElectricCurrent::of(
            NumberFactory::create('2'),
            ElectricCurrentUnit::Ampere,
        );

        $converted = $current->to(ElectricCurrentUnit::Milliampere);
        self::assertEqualsWithDelta(2000.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Round-Trip Tests ==========

    public function testAmpereRoundTrip(): void
    {
        $original = Ampere::of(NumberFactory::create('5'));
        $toMilliampere = $original->to(ElectricCurrentUnit::Milliampere);

        $mAQuantity = Milliampere::of($toMilliampere->getValue());
        $backToAmpere = $mAQuantity->to(ElectricCurrentUnit::Ampere);

        self::assertEqualsWithDelta(5.0, (float) $backToAmpere->getValue()->value(), 0.001);
    }

    public function testMilliampereRoundTrip(): void
    {
        $original = Milliampere::of(NumberFactory::create('350'));
        $toAmpere = $original->to(ElectricCurrentUnit::Ampere);

        $aQuantity = Ampere::of($toAmpere->getValue());
        $backToMilliampere = $aQuantity->to(ElectricCurrentUnit::Milliampere);

        self::assertEqualsWithDelta(350.0, (float) $backToMilliampere->getValue()->value(), 0.001);
    }

    public function testMicroampereRoundTrip(): void
    {
        $original = Microampere::of(NumberFactory::create('500'));
        $toNanoampere = $original->to(ElectricCurrentUnit::Nanoampere);

        $nAQuantity = Nanoampere::of($toNanoampere->getValue());
        $backToMicroampere = $nAQuantity->to(ElectricCurrentUnit::Microampere);

        self::assertEqualsWithDelta(500.0, (float) $backToMicroampere->getValue()->value(), 0.001);
    }

    public function testKiloampereRoundTrip(): void
    {
        $original = Kiloampere::of(NumberFactory::create('2.5'));
        $toAmpere = $original->to(ElectricCurrentUnit::Ampere);

        $aQuantity = Ampere::of($toAmpere->getValue());
        $backToKiloampere = $aQuantity->to(ElectricCurrentUnit::Kiloampere);

        self::assertEqualsWithDelta(2.5, (float) $backToKiloampere->getValue()->value(), 0.001);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $i1 = Ampere::of(NumberFactory::create('2'));
        $i2 = Ampere::of(NumberFactory::create('3'));

        $sum = $i1->add($i2);

        self::assertEqualsWithDelta(5.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $i1 = Milliampere::of(NumberFactory::create('500'));
        $i2 = Milliampere::of(NumberFactory::create('200'));

        $diff = $i1->subtract($i2);

        self::assertEqualsWithDelta(300.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $current = Ampere::of(NumberFactory::create('5'));
        $result = $current->multiplyBy(NumberFactory::create('2'));

        self::assertEqualsWithDelta(10.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $current = Milliampere::of(NumberFactory::create('600'));
        $result = $current->divideBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(200.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testAdditionAcrossUnits(): void
    {
        // 1 A + 500 mA = 1.5 A
        $ampere = Ampere::of(NumberFactory::create('1'));
        $milliampere = Milliampere::of(NumberFactory::create('500'));

        $sum = $ampere->add($milliampere);

        // Result is in A (first operand's unit)
        self::assertEqualsWithDelta(1.5, (float) $sum->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $i1 = Ampere::of(NumberFactory::create('5'));
        $i2 = Ampere::of(NumberFactory::create('3'));

        self::assertTrue($i1->isGreaterThan($i2));
        self::assertFalse($i1->isLessThan($i2));
        self::assertFalse($i1->equals($i2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 A > 500 mA
        $ampere = Ampere::of(NumberFactory::create('1'));
        $milliampere = Milliampere::of(NumberFactory::create('500'));

        self::assertTrue($ampere->isGreaterThan($milliampere));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 A = 1000 mA
        $ampere = Ampere::of(NumberFactory::create('1'));
        $milliampere = Milliampere::of(NumberFactory::create('1000'));

        self::assertTrue($ampere->equals($milliampere));
    }

    public function testCrossUnitComparison(): void
    {
        // 1 A converted to mA should equal 1000 mA
        $aToMa = Ampere::of(NumberFactory::create('1'))->to(ElectricCurrentUnit::Milliampere);
        $direct = Milliampere::of(NumberFactory::create('1000'));

        self::assertEqualsWithDelta(
            (float) $aToMa->getValue()->value(),
            (float) $direct->getValue()->value(),
            0.001,
        );
    }

    // ========== Auto-Scale Tests ==========

    public function testAutoScaleFromNanoampere(): void
    {
        // 1000 nA should auto-scale to 1 μA
        $nanoampere = Nanoampere::of(NumberFactory::create('1000'));
        $scaled = $nanoampere->autoScale();

        self::assertSame(ElectricCurrentUnit::Microampere, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromMicroampere(): void
    {
        // 1000 μA should auto-scale to 1 mA
        $microampere = Microampere::of(NumberFactory::create('1000'));
        $scaled = $microampere->autoScale();

        self::assertSame(ElectricCurrentUnit::Milliampere, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromMilliampere(): void
    {
        // 1000 mA should auto-scale to 1 A
        $milliampere = Milliampere::of(NumberFactory::create('1000'));
        $scaled = $milliampere->autoScale();

        self::assertSame(ElectricCurrentUnit::Ampere, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromAmpere(): void
    {
        // 1000 A should auto-scale to 1 kA
        $ampere = Ampere::of(NumberFactory::create('1000'));
        $scaled = $ampere->autoScale();

        self::assertSame(ElectricCurrentUnit::Kiloampere, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }
}
