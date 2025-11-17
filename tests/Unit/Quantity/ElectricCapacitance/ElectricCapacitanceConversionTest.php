<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\ElectricCapacitance;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\ElectricCapacitance\ElectricCapacitance;
use Andante\Measurement\Quantity\ElectricCapacitance\SI\Farad;
use Andante\Measurement\Quantity\ElectricCapacitance\SI\Microfarad;
use Andante\Measurement\Quantity\ElectricCapacitance\SI\Millifarad;
use Andante\Measurement\Quantity\ElectricCapacitance\SI\Nanofarad;
use Andante\Measurement\Quantity\ElectricCapacitance\SI\Picofarad;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\ElectricCapacitance\ElectricCapacitanceUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for electric capacitance conversions.
 *
 * Electric Capacitance [L⁻²M⁻¹T⁴I²] represents the ability of a system
 * to store electric charge per unit voltage.
 * Base unit: farad (F), SI derived unit = C/V = A²⋅s⁴/(kg⋅m²)
 *
 * Common conversions:
 * - 1 F = 1000 mF
 * - 1 mF = 1000 μF
 * - 1 μF = 1000 nF
 * - 1 nF = 1000 pF
 */
final class ElectricCapacitanceConversionTest extends TestCase
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

    public function testFaradToFarad(): void
    {
        $farad = Farad::of(NumberFactory::create('1'));
        $result = $farad->to(ElectricCapacitanceUnit::Farad);

        self::assertEqualsWithDelta(1.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testFaradToMillifarad(): void
    {
        // 1 F = 1000 mF
        $farad = Farad::of(NumberFactory::create('1'));
        $millifarad = $farad->to(ElectricCapacitanceUnit::Millifarad);

        self::assertEqualsWithDelta(1000.0, (float) $millifarad->getValue()->value(), 0.001);
    }

    public function testMillifaradToFarad(): void
    {
        // 1000 mF = 1 F
        $millifarad = Millifarad::of(NumberFactory::create('1000'));
        $farad = $millifarad->to(ElectricCapacitanceUnit::Farad);

        self::assertEqualsWithDelta(1.0, (float) $farad->getValue()->value(), 0.001);
    }

    public function testFaradToMicrofarad(): void
    {
        // 1 F = 1,000,000 μF
        $farad = Farad::of(NumberFactory::create('1'));
        $microfarad = $farad->to(ElectricCapacitanceUnit::Microfarad);

        self::assertEqualsWithDelta(1000000.0, (float) $microfarad->getValue()->value(), 0.001);
    }

    public function testMicrofaradToFarad(): void
    {
        // 1,000,000 μF = 1 F
        $microfarad = Microfarad::of(NumberFactory::create('1000000'));
        $farad = $microfarad->to(ElectricCapacitanceUnit::Farad);

        self::assertEqualsWithDelta(1.0, (float) $farad->getValue()->value(), 0.001);
    }

    public function testMicrofaradToNanofarad(): void
    {
        // 1 μF = 1000 nF
        $microfarad = Microfarad::of(NumberFactory::create('1'));
        $nanofarad = $microfarad->to(ElectricCapacitanceUnit::Nanofarad);

        self::assertEqualsWithDelta(1000.0, (float) $nanofarad->getValue()->value(), 0.001);
    }

    public function testNanofaradToMicrofarad(): void
    {
        // 1000 nF = 1 μF
        $nanofarad = Nanofarad::of(NumberFactory::create('1000'));
        $microfarad = $nanofarad->to(ElectricCapacitanceUnit::Microfarad);

        self::assertEqualsWithDelta(1.0, (float) $microfarad->getValue()->value(), 0.001);
    }

    public function testNanofaradToPicofarad(): void
    {
        // 1 nF = 1000 pF
        $nanofarad = Nanofarad::of(NumberFactory::create('1'));
        $picofarad = $nanofarad->to(ElectricCapacitanceUnit::Picofarad);

        self::assertEqualsWithDelta(1000.0, (float) $picofarad->getValue()->value(), 0.001);
    }

    public function testPicofaradToNanofarad(): void
    {
        // 1000 pF = 1 nF
        $picofarad = Picofarad::of(NumberFactory::create('1000'));
        $nanofarad = $picofarad->to(ElectricCapacitanceUnit::Nanofarad);

        self::assertEqualsWithDelta(1.0, (float) $nanofarad->getValue()->value(), 0.001);
    }

    public function testFaradToPicofarad(): void
    {
        // 1 F = 10^12 pF
        $farad = Farad::of(NumberFactory::create('0.000001'));
        $picofarad = $farad->to(ElectricCapacitanceUnit::Picofarad);

        self::assertEqualsWithDelta(1000000.0, (float) $picofarad->getValue()->value(), 0.001);
    }

    public function testPicofaradToFarad(): void
    {
        // 10^12 pF = 1 F
        $picofarad = Picofarad::of(NumberFactory::create('1000000000000'));
        $farad = $picofarad->to(ElectricCapacitanceUnit::Farad);

        self::assertEqualsWithDelta(1.0, (float) $farad->getValue()->value(), 0.001);
    }

    // ========== Real-World Scenario Tests ==========

    public function testElectrolyticCapacitor(): void
    {
        // Common electrolytic capacitor: 100 μF
        $cap = Microfarad::of(NumberFactory::create('100'));
        $nanofarad = $cap->to(ElectricCapacitanceUnit::Nanofarad);

        self::assertEqualsWithDelta(100000.0, (float) $nanofarad->getValue()->value(), 0.001);
    }

    public function testCeramicCapacitor(): void
    {
        // Common ceramic capacitor: 100 nF (0.1 μF)
        $cap = Nanofarad::of(NumberFactory::create('100'));
        $microfarad = $cap->to(ElectricCapacitanceUnit::Microfarad);

        self::assertEqualsWithDelta(0.1, (float) $microfarad->getValue()->value(), 0.001);
    }

    public function testRFCapacitor(): void
    {
        // RF capacitor: 10 pF
        $cap = Picofarad::of(NumberFactory::create('10'));
        $nanofarad = $cap->to(ElectricCapacitanceUnit::Nanofarad);

        self::assertEqualsWithDelta(0.01, (float) $nanofarad->getValue()->value(), 0.001);
    }

    public function testSupercapacitor(): void
    {
        // Supercapacitor: 1 F (very large capacitance)
        $supercap = Farad::of(NumberFactory::create('1'));
        $millifarad = $supercap->to(ElectricCapacitanceUnit::Millifarad);

        self::assertEqualsWithDelta(1000.0, (float) $millifarad->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericElectricCapacitanceWithFarad(): void
    {
        $cap = ElectricCapacitance::of(
            NumberFactory::create('1'),
            ElectricCapacitanceUnit::Farad,
        );

        self::assertEquals('1', $cap->getValue()->value());
        self::assertSame(ElectricCapacitanceUnit::Farad, $cap->getUnit());
    }

    public function testGenericElectricCapacitanceWithMicrofarad(): void
    {
        $cap = ElectricCapacitance::of(
            NumberFactory::create('100'),
            ElectricCapacitanceUnit::Microfarad,
        );

        self::assertEquals('100', $cap->getValue()->value());
        self::assertSame(ElectricCapacitanceUnit::Microfarad, $cap->getUnit());
    }

    public function testGenericElectricCapacitanceConversion(): void
    {
        $cap = ElectricCapacitance::of(
            NumberFactory::create('100'),
            ElectricCapacitanceUnit::Microfarad,
        );

        $converted = $cap->to(ElectricCapacitanceUnit::Nanofarad);
        self::assertEqualsWithDelta(100000.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Round-Trip Tests ==========

    public function testMicrofaradRoundTrip(): void
    {
        $original = Microfarad::of(NumberFactory::create('100'));
        $toNanofarad = $original->to(ElectricCapacitanceUnit::Nanofarad);

        $nfQuantity = Nanofarad::of($toNanofarad->getValue());
        $backToMicrofarad = $nfQuantity->to(ElectricCapacitanceUnit::Microfarad);

        self::assertEqualsWithDelta(100.0, (float) $backToMicrofarad->getValue()->value(), 0.001);
    }

    public function testPicofaradRoundTrip(): void
    {
        $original = Picofarad::of(NumberFactory::create('1000'));
        $toNanofarad = $original->to(ElectricCapacitanceUnit::Nanofarad);

        $nfQuantity = Nanofarad::of($toNanofarad->getValue());
        $backToPicofarad = $nfQuantity->to(ElectricCapacitanceUnit::Picofarad);

        self::assertEqualsWithDelta(1000.0, (float) $backToPicofarad->getValue()->value(), 0.001);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $c1 = Microfarad::of(NumberFactory::create('100'));
        $c2 = Microfarad::of(NumberFactory::create('50'));

        $sum = $c1->add($c2);

        self::assertEqualsWithDelta(150.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $c1 = Microfarad::of(NumberFactory::create('100'));
        $c2 = Microfarad::of(NumberFactory::create('30'));

        $diff = $c1->subtract($c2);

        self::assertEqualsWithDelta(70.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $cap = Microfarad::of(NumberFactory::create('50'));
        $result = $cap->multiplyBy(NumberFactory::create('2'));

        self::assertEqualsWithDelta(100.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $cap = Microfarad::of(NumberFactory::create('100'));
        $result = $cap->divideBy(NumberFactory::create('2'));

        self::assertEqualsWithDelta(50.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testAdditionAcrossUnits(): void
    {
        // 100 μF + 50000 nF = 150 μF
        $microfarad = Microfarad::of(NumberFactory::create('100'));
        $nanofarad = Nanofarad::of(NumberFactory::create('50000'));

        $sum = $microfarad->add($nanofarad);

        // Result is in μF (first operand's unit)
        self::assertEqualsWithDelta(150.0, (float) $sum->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $c1 = Microfarad::of(NumberFactory::create('100'));
        $c2 = Microfarad::of(NumberFactory::create('50'));

        self::assertTrue($c1->isGreaterThan($c2));
        self::assertFalse($c1->isLessThan($c2));
        self::assertFalse($c1->equals($c2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 100 μF > 50000 nF (50 μF)
        $microfarad = Microfarad::of(NumberFactory::create('100'));
        $nanofarad = Nanofarad::of(NumberFactory::create('50000'));

        self::assertTrue($microfarad->isGreaterThan($nanofarad));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 μF = 1000 nF
        $microfarad = Microfarad::of(NumberFactory::create('1'));
        $nanofarad = Nanofarad::of(NumberFactory::create('1000'));

        self::assertTrue($microfarad->equals($nanofarad));
    }

    // ========== Auto-Scale Tests ==========

    public function testAutoScaleFromMicrofaradToMillifarad(): void
    {
        // 1000 μF should auto-scale to 1 mF
        $microfarad = Microfarad::of(NumberFactory::create('1000'));
        $scaled = $microfarad->autoScale();

        self::assertSame(ElectricCapacitanceUnit::Millifarad, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromNanofaradToMicrofarad(): void
    {
        // 1000 nF should auto-scale to 1 μF
        $nanofarad = Nanofarad::of(NumberFactory::create('1000'));
        $scaled = $nanofarad->autoScale();

        self::assertSame(ElectricCapacitanceUnit::Microfarad, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromPicofaradToNanofarad(): void
    {
        // 1000 pF should auto-scale to 1 nF
        $picofarad = Picofarad::of(NumberFactory::create('1000'));
        $scaled = $picofarad->autoScale();

        self::assertSame(ElectricCapacitanceUnit::Nanofarad, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromMillifaradToMicrofarad(): void
    {
        // 0.1 mF should auto-scale to 100 μF
        $millifarad = Millifarad::of(NumberFactory::create('0.1'));
        $scaled = $millifarad->autoScale();

        self::assertSame(ElectricCapacitanceUnit::Microfarad, $scaled->getUnit());
        self::assertEqualsWithDelta(100.0, (float) $scaled->getValue()->value(), 0.001);
    }
}
