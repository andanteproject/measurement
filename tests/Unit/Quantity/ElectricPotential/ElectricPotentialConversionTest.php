<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\ElectricPotential;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\ElectricPotential\ElectricPotential;
use Andante\Measurement\Quantity\ElectricPotential\SI\Kilovolt;
use Andante\Measurement\Quantity\ElectricPotential\SI\Megavolt;
use Andante\Measurement\Quantity\ElectricPotential\SI\Microvolt;
use Andante\Measurement\Quantity\ElectricPotential\SI\Millivolt;
use Andante\Measurement\Quantity\ElectricPotential\SI\Volt;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\ElectricPotential\ElectricPotentialUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for electric potential conversions.
 *
 * Electric Potential [L²M¹T⁻³I⁻¹] represents the work done per unit charge.
 * Base unit: volt (V), defined as 1 W/A = 1 J/C
 *
 * Common conversions:
 * - 1 MV = 1,000,000 V
 * - 1 kV = 1000 V
 * - 1 V = 1000 mV
 * - 1 mV = 1000 μV
 */
final class ElectricPotentialConversionTest extends TestCase
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

    public function testVoltToKilovolt(): void
    {
        // 1000 V = 1 kV
        $volt = Volt::of(NumberFactory::create('1000'));
        $kilovolt = $volt->to(ElectricPotentialUnit::Kilovolt);

        self::assertEqualsWithDelta(1.0, (float) $kilovolt->getValue()->value(), 0.001);
    }

    public function testKilovoltToVolt(): void
    {
        // 1 kV = 1000 V
        $kilovolt = Kilovolt::of(NumberFactory::create('1'));
        $volt = $kilovolt->to(ElectricPotentialUnit::Volt);

        self::assertEqualsWithDelta(1000.0, (float) $volt->getValue()->value(), 0.001);
    }

    public function testVoltToMegavolt(): void
    {
        // 1,000,000 V = 1 MV
        $volt = Volt::of(NumberFactory::create('1000000'));
        $megavolt = $volt->to(ElectricPotentialUnit::Megavolt);

        self::assertEqualsWithDelta(1.0, (float) $megavolt->getValue()->value(), 0.001);
    }

    public function testMegavoltToVolt(): void
    {
        // 1 MV = 1,000,000 V
        $megavolt = Megavolt::of(NumberFactory::create('1'));
        $volt = $megavolt->to(ElectricPotentialUnit::Volt);

        self::assertEqualsWithDelta(1000000.0, (float) $volt->getValue()->value(), 0.001);
    }

    public function testVoltToMillivolt(): void
    {
        // 1 V = 1000 mV
        $volt = Volt::of(NumberFactory::create('1'));
        $millivolt = $volt->to(ElectricPotentialUnit::Millivolt);

        self::assertEqualsWithDelta(1000.0, (float) $millivolt->getValue()->value(), 0.001);
    }

    public function testMillivoltToVolt(): void
    {
        // 1000 mV = 1 V
        $millivolt = Millivolt::of(NumberFactory::create('1000'));
        $volt = $millivolt->to(ElectricPotentialUnit::Volt);

        self::assertEqualsWithDelta(1.0, (float) $volt->getValue()->value(), 0.001);
    }

    public function testVoltToMicrovolt(): void
    {
        // 1 V = 1,000,000 μV
        $volt = Volt::of(NumberFactory::create('1'));
        $microvolt = $volt->to(ElectricPotentialUnit::Microvolt);

        self::assertEqualsWithDelta(1000000.0, (float) $microvolt->getValue()->value(), 0.001);
    }

    public function testMicrovoltToVolt(): void
    {
        // 1,000,000 μV = 1 V
        $microvolt = Microvolt::of(NumberFactory::create('1000000'));
        $volt = $microvolt->to(ElectricPotentialUnit::Volt);

        self::assertEqualsWithDelta(1.0, (float) $volt->getValue()->value(), 0.001);
    }

    public function testMillivoltToMicrovolt(): void
    {
        // 1 mV = 1000 μV
        $millivolt = Millivolt::of(NumberFactory::create('1'));
        $microvolt = $millivolt->to(ElectricPotentialUnit::Microvolt);

        self::assertEqualsWithDelta(1000.0, (float) $microvolt->getValue()->value(), 0.001);
    }

    public function testKilovoltToMegavolt(): void
    {
        // 1000 kV = 1 MV
        $kilovolt = Kilovolt::of(NumberFactory::create('1000'));
        $megavolt = $kilovolt->to(ElectricPotentialUnit::Megavolt);

        self::assertEqualsWithDelta(1.0, (float) $megavolt->getValue()->value(), 0.001);
    }

    public function testMegavoltToKilovolt(): void
    {
        // 1 MV = 1000 kV
        $megavolt = Megavolt::of(NumberFactory::create('1'));
        $kilovolt = $megavolt->to(ElectricPotentialUnit::Kilovolt);

        self::assertEqualsWithDelta(1000.0, (float) $kilovolt->getValue()->value(), 0.001);
    }

    // ========== Real-World Scenario Tests ==========

    public function testHouseholdVoltage(): void
    {
        // European household: 230 V
        $household = Volt::of(NumberFactory::create('230'));
        $millivolt = $household->to(ElectricPotentialUnit::Millivolt);

        self::assertEqualsWithDelta(230000.0, (float) $millivolt->getValue()->value(), 0.001);
    }

    public function testUSHouseholdVoltage(): void
    {
        // US household: 120 V
        $household = Volt::of(NumberFactory::create('120'));
        $kilovolt = $household->to(ElectricPotentialUnit::Kilovolt);

        self::assertEqualsWithDelta(0.12, (float) $kilovolt->getValue()->value(), 0.001);
    }

    public function testBatteryVoltage(): void
    {
        // AA battery: 1.5 V = 1500 mV
        $battery = Volt::of(NumberFactory::create('1.5'));
        $millivolt = $battery->to(ElectricPotentialUnit::Millivolt);

        self::assertEqualsWithDelta(1500.0, (float) $millivolt->getValue()->value(), 0.001);
    }

    public function testHighVoltagePowerLine(): void
    {
        // High voltage transmission: 400 kV
        $transmission = Kilovolt::of(NumberFactory::create('400'));
        $megavolt = $transmission->to(ElectricPotentialUnit::Megavolt);

        self::assertEqualsWithDelta(0.4, (float) $megavolt->getValue()->value(), 0.001);
    }

    public function testECGSignal(): void
    {
        // Typical ECG signal: 1000 μV = 1 mV
        $ecg = Microvolt::of(NumberFactory::create('1000'));
        $millivolt = $ecg->to(ElectricPotentialUnit::Millivolt);

        self::assertEqualsWithDelta(1.0, (float) $millivolt->getValue()->value(), 0.001);
    }

    public function testThermocoupleOutput(): void
    {
        // Thermocouple output: 40 μV/°C
        $thermocouple = Microvolt::of(NumberFactory::create('40'));
        $volt = $thermocouple->to(ElectricPotentialUnit::Volt);

        self::assertEqualsWithDelta(0.00004, (float) $volt->getValue()->value(), 0.0000001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericElectricPotentialWithVolt(): void
    {
        $voltage = ElectricPotential::of(
            NumberFactory::create('230'),
            ElectricPotentialUnit::Volt,
        );

        self::assertEquals('230', $voltage->getValue()->value());
        self::assertSame(ElectricPotentialUnit::Volt, $voltage->getUnit());
    }

    public function testGenericElectricPotentialWithKilovolt(): void
    {
        $voltage = ElectricPotential::of(
            NumberFactory::create('11'),
            ElectricPotentialUnit::Kilovolt,
        );

        self::assertEquals('11', $voltage->getValue()->value());
        self::assertSame(ElectricPotentialUnit::Kilovolt, $voltage->getUnit());
    }

    public function testGenericElectricPotentialWithMillivolt(): void
    {
        $voltage = ElectricPotential::of(
            NumberFactory::create('500'),
            ElectricPotentialUnit::Millivolt,
        );

        self::assertEquals('500', $voltage->getValue()->value());
        self::assertSame(ElectricPotentialUnit::Millivolt, $voltage->getUnit());
    }

    public function testGenericElectricPotentialConversion(): void
    {
        $voltage = ElectricPotential::of(
            NumberFactory::create('5'),
            ElectricPotentialUnit::Volt,
        );

        $converted = $voltage->to(ElectricPotentialUnit::Millivolt);
        self::assertEqualsWithDelta(5000.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Round-Trip Tests ==========

    public function testVoltRoundTrip(): void
    {
        $original = Volt::of(NumberFactory::create('230'));
        $toMillivolt = $original->to(ElectricPotentialUnit::Millivolt);

        $mVQuantity = Millivolt::of($toMillivolt->getValue());
        $backToVolt = $mVQuantity->to(ElectricPotentialUnit::Volt);

        self::assertEqualsWithDelta(230.0, (float) $backToVolt->getValue()->value(), 0.001);
    }

    public function testKilovoltRoundTrip(): void
    {
        $original = Kilovolt::of(NumberFactory::create('11'));
        $toVolt = $original->to(ElectricPotentialUnit::Volt);

        $vQuantity = Volt::of($toVolt->getValue());
        $backToKilovolt = $vQuantity->to(ElectricPotentialUnit::Kilovolt);

        self::assertEqualsWithDelta(11.0, (float) $backToKilovolt->getValue()->value(), 0.001);
    }

    public function testMicrovoltRoundTrip(): void
    {
        $original = Microvolt::of(NumberFactory::create('500'));
        $toMillivolt = $original->to(ElectricPotentialUnit::Millivolt);

        $mVQuantity = Millivolt::of($toMillivolt->getValue());
        $backToMicrovolt = $mVQuantity->to(ElectricPotentialUnit::Microvolt);

        self::assertEqualsWithDelta(500.0, (float) $backToMicrovolt->getValue()->value(), 0.001);
    }

    public function testMegavoltRoundTrip(): void
    {
        $original = Megavolt::of(NumberFactory::create('1.5'));
        $toKilovolt = $original->to(ElectricPotentialUnit::Kilovolt);

        $kVQuantity = Kilovolt::of($toKilovolt->getValue());
        $backToMegavolt = $kVQuantity->to(ElectricPotentialUnit::Megavolt);

        self::assertEqualsWithDelta(1.5, (float) $backToMegavolt->getValue()->value(), 0.001);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $v1 = Volt::of(NumberFactory::create('100'));
        $v2 = Volt::of(NumberFactory::create('50'));

        $sum = $v1->add($v2);

        self::assertEqualsWithDelta(150.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $v1 = Kilovolt::of(NumberFactory::create('11'));
        $v2 = Kilovolt::of(NumberFactory::create('4'));

        $diff = $v1->subtract($v2);

        self::assertEqualsWithDelta(7.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $voltage = Volt::of(NumberFactory::create('230'));
        $result = $voltage->multiplyBy(NumberFactory::create('2'));

        self::assertEqualsWithDelta(460.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $voltage = Kilovolt::of(NumberFactory::create('400'));
        $result = $voltage->divideBy(NumberFactory::create('4'));

        self::assertEqualsWithDelta(100.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testAdditionAcrossUnits(): void
    {
        // 1 V + 500 mV = 1.5 V
        $volt = Volt::of(NumberFactory::create('1'));
        $millivolt = Millivolt::of(NumberFactory::create('500'));

        $sum = $volt->add($millivolt);

        // Result is in V (first operand's unit)
        self::assertEqualsWithDelta(1.5, (float) $sum->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $v1 = Volt::of(NumberFactory::create('230'));
        $v2 = Volt::of(NumberFactory::create('120'));

        self::assertTrue($v1->isGreaterThan($v2));
        self::assertFalse($v1->isLessThan($v2));
        self::assertFalse($v1->equals($v2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 kV > 500 V
        $kilovolt = Kilovolt::of(NumberFactory::create('1'));
        $volt = Volt::of(NumberFactory::create('500'));

        self::assertTrue($kilovolt->isGreaterThan($volt));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 V = 1000 mV
        $volt = Volt::of(NumberFactory::create('1'));
        $millivolt = Millivolt::of(NumberFactory::create('1000'));

        self::assertTrue($volt->equals($millivolt));
    }

    public function testCrossUnitComparison(): void
    {
        // 1 kV converted to V should equal 1000 V
        $kvToV = Kilovolt::of(NumberFactory::create('1'))->to(ElectricPotentialUnit::Volt);
        $direct = Volt::of(NumberFactory::create('1000'));

        self::assertEqualsWithDelta(
            (float) $kvToV->getValue()->value(),
            (float) $direct->getValue()->value(),
            0.001,
        );
    }

    // ========== Auto-Scale Tests ==========

    public function testAutoScaleFromMicrovolt(): void
    {
        // 1000 μV should auto-scale to 1 mV
        $microvolt = Microvolt::of(NumberFactory::create('1000'));
        $scaled = $microvolt->autoScale();

        self::assertSame(ElectricPotentialUnit::Millivolt, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromMillivolt(): void
    {
        // 1000 mV should auto-scale to 1 V
        $millivolt = Millivolt::of(NumberFactory::create('1000'));
        $scaled = $millivolt->autoScale();

        self::assertSame(ElectricPotentialUnit::Volt, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromVolt(): void
    {
        // 1000 V should auto-scale to 1 kV
        $volt = Volt::of(NumberFactory::create('1000'));
        $scaled = $volt->autoScale();

        self::assertSame(ElectricPotentialUnit::Kilovolt, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromKilovolt(): void
    {
        // 1000 kV should auto-scale to 1 MV
        $kilovolt = Kilovolt::of(NumberFactory::create('1000'));
        $scaled = $kilovolt->autoScale();

        self::assertSame(ElectricPotentialUnit::Megavolt, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }
}
