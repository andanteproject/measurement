<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\ElectricResistance;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\ElectricResistance\ElectricResistance;
use Andante\Measurement\Quantity\ElectricResistance\SI\Kiloohm;
use Andante\Measurement\Quantity\ElectricResistance\SI\Megaohm;
use Andante\Measurement\Quantity\ElectricResistance\SI\Microohm;
use Andante\Measurement\Quantity\ElectricResistance\SI\Milliohm;
use Andante\Measurement\Quantity\ElectricResistance\SI\Ohm;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\ElectricResistance\ElectricResistanceUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for electric resistance conversions.
 *
 * Electric Resistance [L²M¹T⁻³I⁻²] represents the opposition to the flow of electric current.
 * Base unit: ohm (Ω), defined as 1 V/A
 *
 * Common conversions:
 * - 1 MΩ = 1,000,000 Ω
 * - 1 kΩ = 1000 Ω
 * - 1 Ω = 1000 mΩ
 * - 1 mΩ = 1000 μΩ
 */
final class ElectricResistanceConversionTest extends TestCase
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

    public function testOhmToKiloohm(): void
    {
        // 1000 Ω = 1 kΩ
        $ohm = Ohm::of(NumberFactory::create('1000'));
        $kiloohm = $ohm->to(ElectricResistanceUnit::Kiloohm);

        self::assertEqualsWithDelta(1.0, (float) $kiloohm->getValue()->value(), 0.001);
    }

    public function testKiloohmToOhm(): void
    {
        // 1 kΩ = 1000 Ω
        $kiloohm = Kiloohm::of(NumberFactory::create('1'));
        $ohm = $kiloohm->to(ElectricResistanceUnit::Ohm);

        self::assertEqualsWithDelta(1000.0, (float) $ohm->getValue()->value(), 0.001);
    }

    public function testOhmToMegaohm(): void
    {
        // 1,000,000 Ω = 1 MΩ
        $ohm = Ohm::of(NumberFactory::create('1000000'));
        $megaohm = $ohm->to(ElectricResistanceUnit::Megaohm);

        self::assertEqualsWithDelta(1.0, (float) $megaohm->getValue()->value(), 0.001);
    }

    public function testMegaohmToOhm(): void
    {
        // 1 MΩ = 1,000,000 Ω
        $megaohm = Megaohm::of(NumberFactory::create('1'));
        $ohm = $megaohm->to(ElectricResistanceUnit::Ohm);

        self::assertEqualsWithDelta(1000000.0, (float) $ohm->getValue()->value(), 0.001);
    }

    public function testOhmToMilliohm(): void
    {
        // 1 Ω = 1000 mΩ
        $ohm = Ohm::of(NumberFactory::create('1'));
        $milliohm = $ohm->to(ElectricResistanceUnit::Milliohm);

        self::assertEqualsWithDelta(1000.0, (float) $milliohm->getValue()->value(), 0.001);
    }

    public function testMilliohmToOhm(): void
    {
        // 1000 mΩ = 1 Ω
        $milliohm = Milliohm::of(NumberFactory::create('1000'));
        $ohm = $milliohm->to(ElectricResistanceUnit::Ohm);

        self::assertEqualsWithDelta(1.0, (float) $ohm->getValue()->value(), 0.001);
    }

    public function testOhmToMicroohm(): void
    {
        // 1 Ω = 1,000,000 μΩ
        $ohm = Ohm::of(NumberFactory::create('1'));
        $microohm = $ohm->to(ElectricResistanceUnit::Microohm);

        self::assertEqualsWithDelta(1000000.0, (float) $microohm->getValue()->value(), 0.001);
    }

    public function testMicroohmToOhm(): void
    {
        // 1,000,000 μΩ = 1 Ω
        $microohm = Microohm::of(NumberFactory::create('1000000'));
        $ohm = $microohm->to(ElectricResistanceUnit::Ohm);

        self::assertEqualsWithDelta(1.0, (float) $ohm->getValue()->value(), 0.001);
    }

    public function testMilliohmToMicroohm(): void
    {
        // 1 mΩ = 1000 μΩ
        $milliohm = Milliohm::of(NumberFactory::create('1'));
        $microohm = $milliohm->to(ElectricResistanceUnit::Microohm);

        self::assertEqualsWithDelta(1000.0, (float) $microohm->getValue()->value(), 0.001);
    }

    public function testKiloohmToMegaohm(): void
    {
        // 1000 kΩ = 1 MΩ
        $kiloohm = Kiloohm::of(NumberFactory::create('1000'));
        $megaohm = $kiloohm->to(ElectricResistanceUnit::Megaohm);

        self::assertEqualsWithDelta(1.0, (float) $megaohm->getValue()->value(), 0.001);
    }

    public function testMegaohmToKiloohm(): void
    {
        // 1 MΩ = 1000 kΩ
        $megaohm = Megaohm::of(NumberFactory::create('1'));
        $kiloohm = $megaohm->to(ElectricResistanceUnit::Kiloohm);

        self::assertEqualsWithDelta(1000.0, (float) $kiloohm->getValue()->value(), 0.001);
    }

    // ========== Real-World Scenario Tests ==========

    public function testStandardResistor(): void
    {
        // Common resistor value: 4.7 kΩ
        $resistor = Kiloohm::of(NumberFactory::create('4.7'));
        $ohm = $resistor->to(ElectricResistanceUnit::Ohm);

        self::assertEqualsWithDelta(4700.0, (float) $ohm->getValue()->value(), 0.001);
    }

    public function testPullUpResistor(): void
    {
        // Common pull-up resistor: 10 kΩ
        $pullup = Kiloohm::of(NumberFactory::create('10'));
        $megaohm = $pullup->to(ElectricResistanceUnit::Megaohm);

        self::assertEqualsWithDelta(0.01, (float) $megaohm->getValue()->value(), 0.001);
    }

    public function testShuntResistor(): void
    {
        // Shunt resistor for current sensing: 10 mΩ
        $shunt = Milliohm::of(NumberFactory::create('10'));
        $ohm = $shunt->to(ElectricResistanceUnit::Ohm);

        self::assertEqualsWithDelta(0.01, (float) $ohm->getValue()->value(), 0.001);
    }

    public function testInsulationResistance(): void
    {
        // Cable insulation resistance: 500 MΩ
        $insulation = Megaohm::of(NumberFactory::create('500'));
        $kiloohm = $insulation->to(ElectricResistanceUnit::Kiloohm);

        self::assertEqualsWithDelta(500000.0, (float) $kiloohm->getValue()->value(), 0.001);
    }

    public function testContactResistance(): void
    {
        // Relay contact resistance: 100 μΩ
        $contact = Microohm::of(NumberFactory::create('100'));
        $milliohm = $contact->to(ElectricResistanceUnit::Milliohm);

        self::assertEqualsWithDelta(0.1, (float) $milliohm->getValue()->value(), 0.001);
    }

    public function testWireResistance(): void
    {
        // Copper wire resistance: 50 mΩ/m
        $wire = Milliohm::of(NumberFactory::create('50'));
        $microohm = $wire->to(ElectricResistanceUnit::Microohm);

        self::assertEqualsWithDelta(50000.0, (float) $microohm->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericElectricResistanceWithOhm(): void
    {
        $resistance = ElectricResistance::of(
            NumberFactory::create('100'),
            ElectricResistanceUnit::Ohm,
        );

        self::assertEquals('100', $resistance->getValue()->value());
        self::assertSame(ElectricResistanceUnit::Ohm, $resistance->getUnit());
    }

    public function testGenericElectricResistanceWithKiloohm(): void
    {
        $resistance = ElectricResistance::of(
            NumberFactory::create('4.7'),
            ElectricResistanceUnit::Kiloohm,
        );

        self::assertEquals('4.7', $resistance->getValue()->value());
        self::assertSame(ElectricResistanceUnit::Kiloohm, $resistance->getUnit());
    }

    public function testGenericElectricResistanceWithMegaohm(): void
    {
        $resistance = ElectricResistance::of(
            NumberFactory::create('10'),
            ElectricResistanceUnit::Megaohm,
        );

        self::assertEquals('10', $resistance->getValue()->value());
        self::assertSame(ElectricResistanceUnit::Megaohm, $resistance->getUnit());
    }

    public function testGenericElectricResistanceConversion(): void
    {
        $resistance = ElectricResistance::of(
            NumberFactory::create('2.2'),
            ElectricResistanceUnit::Kiloohm,
        );

        $converted = $resistance->to(ElectricResistanceUnit::Ohm);
        self::assertEqualsWithDelta(2200.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Round-Trip Tests ==========

    public function testOhmRoundTrip(): void
    {
        $original = Ohm::of(NumberFactory::create('470'));
        $toKiloohm = $original->to(ElectricResistanceUnit::Kiloohm);

        $kOhmQuantity = Kiloohm::of($toKiloohm->getValue());
        $backToOhm = $kOhmQuantity->to(ElectricResistanceUnit::Ohm);

        self::assertEqualsWithDelta(470.0, (float) $backToOhm->getValue()->value(), 0.001);
    }

    public function testKiloohmRoundTrip(): void
    {
        $original = Kiloohm::of(NumberFactory::create('10'));
        $toOhm = $original->to(ElectricResistanceUnit::Ohm);

        $ohmQuantity = Ohm::of($toOhm->getValue());
        $backToKiloohm = $ohmQuantity->to(ElectricResistanceUnit::Kiloohm);

        self::assertEqualsWithDelta(10.0, (float) $backToKiloohm->getValue()->value(), 0.001);
    }

    public function testMicroohmRoundTrip(): void
    {
        $original = Microohm::of(NumberFactory::create('500'));
        $toMilliohm = $original->to(ElectricResistanceUnit::Milliohm);

        $mOhmQuantity = Milliohm::of($toMilliohm->getValue());
        $backToMicroohm = $mOhmQuantity->to(ElectricResistanceUnit::Microohm);

        self::assertEqualsWithDelta(500.0, (float) $backToMicroohm->getValue()->value(), 0.001);
    }

    public function testMegaohmRoundTrip(): void
    {
        $original = Megaohm::of(NumberFactory::create('2.2'));
        $toKiloohm = $original->to(ElectricResistanceUnit::Kiloohm);

        $kOhmQuantity = Kiloohm::of($toKiloohm->getValue());
        $backToMegaohm = $kOhmQuantity->to(ElectricResistanceUnit::Megaohm);

        self::assertEqualsWithDelta(2.2, (float) $backToMegaohm->getValue()->value(), 0.001);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $r1 = Ohm::of(NumberFactory::create('100'));
        $r2 = Ohm::of(NumberFactory::create('200'));

        $sum = $r1->add($r2);

        self::assertEqualsWithDelta(300.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $r1 = Kiloohm::of(NumberFactory::create('10'));
        $r2 = Kiloohm::of(NumberFactory::create('4'));

        $diff = $r1->subtract($r2);

        self::assertEqualsWithDelta(6.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $resistance = Ohm::of(NumberFactory::create('100'));
        $result = $resistance->multiplyBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(300.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $resistance = Kiloohm::of(NumberFactory::create('12'));
        $result = $resistance->divideBy(NumberFactory::create('4'));

        self::assertEqualsWithDelta(3.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testAdditionAcrossUnits(): void
    {
        // 1 kΩ + 500 Ω = 1.5 kΩ
        $kiloohm = Kiloohm::of(NumberFactory::create('1'));
        $ohm = Ohm::of(NumberFactory::create('500'));

        $sum = $kiloohm->add($ohm);

        // Result is in kΩ (first operand's unit)
        self::assertEqualsWithDelta(1.5, (float) $sum->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $r1 = Ohm::of(NumberFactory::create('1000'));
        $r2 = Ohm::of(NumberFactory::create('470'));

        self::assertTrue($r1->isGreaterThan($r2));
        self::assertFalse($r1->isLessThan($r2));
        self::assertFalse($r1->equals($r2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 kΩ > 500 Ω
        $kiloohm = Kiloohm::of(NumberFactory::create('1'));
        $ohm = Ohm::of(NumberFactory::create('500'));

        self::assertTrue($kiloohm->isGreaterThan($ohm));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 kΩ = 1000 Ω
        $kiloohm = Kiloohm::of(NumberFactory::create('1'));
        $ohm = Ohm::of(NumberFactory::create('1000'));

        self::assertTrue($kiloohm->equals($ohm));
    }

    public function testCrossUnitComparison(): void
    {
        // 1 kΩ converted to Ω should equal 1000 Ω
        $kOhmToOhm = Kiloohm::of(NumberFactory::create('1'))->to(ElectricResistanceUnit::Ohm);
        $direct = Ohm::of(NumberFactory::create('1000'));

        self::assertEqualsWithDelta(
            (float) $kOhmToOhm->getValue()->value(),
            (float) $direct->getValue()->value(),
            0.001,
        );
    }

    // ========== Auto-Scale Tests ==========

    public function testAutoScaleFromMicroohm(): void
    {
        // 1000 μΩ should auto-scale to 1 mΩ
        $microohm = Microohm::of(NumberFactory::create('1000'));
        $scaled = $microohm->autoScale();

        self::assertSame(ElectricResistanceUnit::Milliohm, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromMilliohm(): void
    {
        // 1000 mΩ should auto-scale to 1 Ω
        $milliohm = Milliohm::of(NumberFactory::create('1000'));
        $scaled = $milliohm->autoScale();

        self::assertSame(ElectricResistanceUnit::Ohm, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromOhm(): void
    {
        // 1000 Ω should auto-scale to 1 kΩ
        $ohm = Ohm::of(NumberFactory::create('1000'));
        $scaled = $ohm->autoScale();

        self::assertSame(ElectricResistanceUnit::Kiloohm, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromKiloohm(): void
    {
        // 1000 kΩ should auto-scale to 1 MΩ
        $kiloohm = Kiloohm::of(NumberFactory::create('1000'));
        $scaled = $kiloohm->autoScale();

        self::assertSame(ElectricResistanceUnit::Megaohm, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }
}
