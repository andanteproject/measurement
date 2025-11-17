<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\ElectricCharge;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\ElectricCharge\ElectricCharge;
use Andante\Measurement\Quantity\ElectricCharge\SI\AmpereHour;
use Andante\Measurement\Quantity\ElectricCharge\SI\Coulomb;
use Andante\Measurement\Quantity\ElectricCharge\SI\Microcoulomb;
use Andante\Measurement\Quantity\ElectricCharge\SI\MilliampereHour;
use Andante\Measurement\Quantity\ElectricCharge\SI\Millicoulomb;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\ElectricCharge\ElectricChargeUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for electric charge conversions.
 *
 * Electric Charge [T¹I¹] represents the quantity of electric charge.
 * Base unit: coulomb (C), defined as 1 A⋅s
 *
 * Common conversions:
 * - 1 C = 1000 mC = 1,000,000 μC
 * - 1 Ah = 3600 C
 * - 1 mAh = 3.6 C
 */
final class ElectricChargeConversionTest extends TestCase
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

    public function testCoulombToMillicoulomb(): void
    {
        // 1 C = 1000 mC
        $coulomb = Coulomb::of(NumberFactory::create('1'));
        $millicoulomb = $coulomb->to(ElectricChargeUnit::Millicoulomb);

        self::assertEqualsWithDelta(1000.0, (float) $millicoulomb->getValue()->value(), 0.001);
    }

    public function testMillicoulombToCoulomb(): void
    {
        // 1000 mC = 1 C
        $millicoulomb = Millicoulomb::of(NumberFactory::create('1000'));
        $coulomb = $millicoulomb->to(ElectricChargeUnit::Coulomb);

        self::assertEqualsWithDelta(1.0, (float) $coulomb->getValue()->value(), 0.001);
    }

    public function testCoulombToMicrocoulomb(): void
    {
        // 1 C = 1,000,000 μC
        $coulomb = Coulomb::of(NumberFactory::create('1'));
        $microcoulomb = $coulomb->to(ElectricChargeUnit::Microcoulomb);

        self::assertEqualsWithDelta(1000000.0, (float) $microcoulomb->getValue()->value(), 0.001);
    }

    public function testMicrocoulombToCoulomb(): void
    {
        // 1,000,000 μC = 1 C
        $microcoulomb = Microcoulomb::of(NumberFactory::create('1000000'));
        $coulomb = $microcoulomb->to(ElectricChargeUnit::Coulomb);

        self::assertEqualsWithDelta(1.0, (float) $coulomb->getValue()->value(), 0.001);
    }

    public function testMillicoulombToMicrocoulomb(): void
    {
        // 1 mC = 1000 μC
        $millicoulomb = Millicoulomb::of(NumberFactory::create('1'));
        $microcoulomb = $millicoulomb->to(ElectricChargeUnit::Microcoulomb);

        self::assertEqualsWithDelta(1000.0, (float) $microcoulomb->getValue()->value(), 0.001);
    }

    // ========== Ampere-Hour Tests ==========

    public function testAmpereHourToCoulomb(): void
    {
        // 1 Ah = 3600 C
        $ampereHour = AmpereHour::of(NumberFactory::create('1'));
        $coulomb = $ampereHour->to(ElectricChargeUnit::Coulomb);

        self::assertEqualsWithDelta(3600.0, (float) $coulomb->getValue()->value(), 0.001);
    }

    public function testCoulombToAmpereHour(): void
    {
        // 3600 C = 1 Ah
        $coulomb = Coulomb::of(NumberFactory::create('3600'));
        $ampereHour = $coulomb->to(ElectricChargeUnit::AmpereHour);

        self::assertEqualsWithDelta(1.0, (float) $ampereHour->getValue()->value(), 0.001);
    }

    public function testMilliampereHourToCoulomb(): void
    {
        // 1 mAh = 3.6 C
        $mAh = MilliampereHour::of(NumberFactory::create('1'));
        $coulomb = $mAh->to(ElectricChargeUnit::Coulomb);

        self::assertEqualsWithDelta(3.6, (float) $coulomb->getValue()->value(), 0.001);
    }

    public function testCoulombToMilliampereHour(): void
    {
        // 3.6 C = 1 mAh
        $coulomb = Coulomb::of(NumberFactory::create('3.6'));
        $mAh = $coulomb->to(ElectricChargeUnit::MilliampereHour);

        self::assertEqualsWithDelta(1.0, (float) $mAh->getValue()->value(), 0.001);
    }

    public function testAmpereHourToMilliampereHour(): void
    {
        // 1 Ah = 1000 mAh
        $ampereHour = AmpereHour::of(NumberFactory::create('1'));
        $mAh = $ampereHour->to(ElectricChargeUnit::MilliampereHour);

        self::assertEqualsWithDelta(1000.0, (float) $mAh->getValue()->value(), 0.001);
    }

    public function testMilliampereHourToAmpereHour(): void
    {
        // 1000 mAh = 1 Ah
        $mAh = MilliampereHour::of(NumberFactory::create('1000'));
        $ampereHour = $mAh->to(ElectricChargeUnit::AmpereHour);

        self::assertEqualsWithDelta(1.0, (float) $ampereHour->getValue()->value(), 0.001);
    }

    // ========== Real-World Scenario Tests ==========

    public function testSmartphoneBattery(): void
    {
        // Typical smartphone battery: 4000 mAh
        $battery = MilliampereHour::of(NumberFactory::create('4000'));
        $coulomb = $battery->to(ElectricChargeUnit::Coulomb);

        // 4000 mAh = 4000 × 3.6 = 14400 C
        self::assertEqualsWithDelta(14400.0, (float) $coulomb->getValue()->value(), 0.001);
    }

    public function testCarBattery(): void
    {
        // Car battery: 60 Ah
        $battery = AmpereHour::of(NumberFactory::create('60'));
        $coulomb = $battery->to(ElectricChargeUnit::Coulomb);

        // 60 Ah = 60 × 3600 = 216000 C
        self::assertEqualsWithDelta(216000.0, (float) $coulomb->getValue()->value(), 0.001);
    }

    public function testCapacitorCharge(): void
    {
        // Small capacitor charge: 100 μC
        $charge = Microcoulomb::of(NumberFactory::create('100'));
        $millicoulomb = $charge->to(ElectricChargeUnit::Millicoulomb);

        self::assertEqualsWithDelta(0.1, (float) $millicoulomb->getValue()->value(), 0.001);
    }

    public function testStaticElectricityDischarge(): void
    {
        // Static electricity discharge: ~1 μC
        $discharge = Microcoulomb::of(NumberFactory::create('1'));
        $coulomb = $discharge->to(ElectricChargeUnit::Coulomb);

        self::assertEqualsWithDelta(0.000001, (float) $coulomb->getValue()->value(), 0.0000001);
    }

    public function testPowerbankCapacity(): void
    {
        // Powerbank capacity: 10000 mAh = 10 Ah
        $powerbank = MilliampereHour::of(NumberFactory::create('10000'));
        $ampereHour = $powerbank->to(ElectricChargeUnit::AmpereHour);

        self::assertEqualsWithDelta(10.0, (float) $ampereHour->getValue()->value(), 0.001);
    }

    public function testLightningBolt(): void
    {
        // Lightning bolt: approximately 15-20 C
        $lightning = Coulomb::of(NumberFactory::create('15'));
        $millicoulomb = $lightning->to(ElectricChargeUnit::Millicoulomb);

        self::assertEqualsWithDelta(15000.0, (float) $millicoulomb->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericElectricChargeWithCoulomb(): void
    {
        $charge = ElectricCharge::of(
            NumberFactory::create('100'),
            ElectricChargeUnit::Coulomb,
        );

        self::assertEquals('100', $charge->getValue()->value());
        self::assertSame(ElectricChargeUnit::Coulomb, $charge->getUnit());
    }

    public function testGenericElectricChargeWithAmpereHour(): void
    {
        $charge = ElectricCharge::of(
            NumberFactory::create('4.5'),
            ElectricChargeUnit::AmpereHour,
        );

        self::assertEquals('4.5', $charge->getValue()->value());
        self::assertSame(ElectricChargeUnit::AmpereHour, $charge->getUnit());
    }

    public function testGenericElectricChargeWithMilliampereHour(): void
    {
        $charge = ElectricCharge::of(
            NumberFactory::create('5000'),
            ElectricChargeUnit::MilliampereHour,
        );

        self::assertEquals('5000', $charge->getValue()->value());
        self::assertSame(ElectricChargeUnit::MilliampereHour, $charge->getUnit());
    }

    public function testGenericElectricChargeConversion(): void
    {
        $charge = ElectricCharge::of(
            NumberFactory::create('2'),
            ElectricChargeUnit::AmpereHour,
        );

        $converted = $charge->to(ElectricChargeUnit::Coulomb);
        self::assertEqualsWithDelta(7200.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Round-Trip Tests ==========

    public function testCoulombRoundTrip(): void
    {
        $original = Coulomb::of(NumberFactory::create('3600'));
        $toAh = $original->to(ElectricChargeUnit::AmpereHour);

        $ahQuantity = AmpereHour::of($toAh->getValue());
        $backToCoulomb = $ahQuantity->to(ElectricChargeUnit::Coulomb);

        self::assertEqualsWithDelta(3600.0, (float) $backToCoulomb->getValue()->value(), 0.001);
    }

    public function testAmpereHourRoundTrip(): void
    {
        $original = AmpereHour::of(NumberFactory::create('5'));
        $toMAh = $original->to(ElectricChargeUnit::MilliampereHour);

        $mAhQuantity = MilliampereHour::of($toMAh->getValue());
        $backToAh = $mAhQuantity->to(ElectricChargeUnit::AmpereHour);

        self::assertEqualsWithDelta(5.0, (float) $backToAh->getValue()->value(), 0.001);
    }

    public function testMicrocoulombRoundTrip(): void
    {
        $original = Microcoulomb::of(NumberFactory::create('500'));
        $toMillicoulomb = $original->to(ElectricChargeUnit::Millicoulomb);

        $mcQuantity = Millicoulomb::of($toMillicoulomb->getValue());
        $backToMicrocoulomb = $mcQuantity->to(ElectricChargeUnit::Microcoulomb);

        self::assertEqualsWithDelta(500.0, (float) $backToMicrocoulomb->getValue()->value(), 0.001);
    }

    public function testMilliampereHourRoundTrip(): void
    {
        $original = MilliampereHour::of(NumberFactory::create('2500'));
        $toCoulomb = $original->to(ElectricChargeUnit::Coulomb);

        $cQuantity = Coulomb::of($toCoulomb->getValue());
        $backToMAh = $cQuantity->to(ElectricChargeUnit::MilliampereHour);

        self::assertEqualsWithDelta(2500.0, (float) $backToMAh->getValue()->value(), 0.001);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $c1 = Coulomb::of(NumberFactory::create('100'));
        $c2 = Coulomb::of(NumberFactory::create('200'));

        $sum = $c1->add($c2);

        self::assertEqualsWithDelta(300.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $c1 = MilliampereHour::of(NumberFactory::create('5000'));
        $c2 = MilliampereHour::of(NumberFactory::create('2000'));

        $diff = $c1->subtract($c2);

        self::assertEqualsWithDelta(3000.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $charge = Coulomb::of(NumberFactory::create('100'));
        $result = $charge->multiplyBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(300.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $charge = AmpereHour::of(NumberFactory::create('12'));
        $result = $charge->divideBy(NumberFactory::create('4'));

        self::assertEqualsWithDelta(3.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testAdditionAcrossUnits(): void
    {
        // 1 Ah + 1800 C = 1.5 Ah (1800 C = 0.5 Ah)
        $ampereHour = AmpereHour::of(NumberFactory::create('1'));
        $coulomb = Coulomb::of(NumberFactory::create('1800'));

        $sum = $ampereHour->add($coulomb);

        // Result is in Ah (first operand's unit)
        self::assertEqualsWithDelta(1.5, (float) $sum->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $c1 = Coulomb::of(NumberFactory::create('1000'));
        $c2 = Coulomb::of(NumberFactory::create('500'));

        self::assertTrue($c1->isGreaterThan($c2));
        self::assertFalse($c1->isLessThan($c2));
        self::assertFalse($c1->equals($c2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 Ah > 1000 C (1 Ah = 3600 C)
        $ampereHour = AmpereHour::of(NumberFactory::create('1'));
        $coulomb = Coulomb::of(NumberFactory::create('1000'));

        self::assertTrue($ampereHour->isGreaterThan($coulomb));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 Ah = 3600 C
        $ampereHour = AmpereHour::of(NumberFactory::create('1'));
        $coulomb = Coulomb::of(NumberFactory::create('3600'));

        self::assertTrue($ampereHour->equals($coulomb));
    }

    public function testCrossUnitComparison(): void
    {
        // 1 Ah converted to C should equal 3600 C
        $ahToC = AmpereHour::of(NumberFactory::create('1'))->to(ElectricChargeUnit::Coulomb);
        $direct = Coulomb::of(NumberFactory::create('3600'));

        self::assertEqualsWithDelta(
            (float) $ahToC->getValue()->value(),
            (float) $direct->getValue()->value(),
            0.001,
        );
    }

    // ========== Auto-Scale Tests ==========

    public function testAutoScaleFromMicrocoulomb(): void
    {
        // 1000 μC should auto-scale to 1 mC
        $microcoulomb = Microcoulomb::of(NumberFactory::create('1000'));
        $scaled = $microcoulomb->autoScale();

        self::assertSame(ElectricChargeUnit::Millicoulomb, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromMillicoulomb(): void
    {
        // 1000 mC should auto-scale to 1 C
        $millicoulomb = Millicoulomb::of(NumberFactory::create('1000'));
        $scaled = $millicoulomb->autoScale();

        self::assertSame(ElectricChargeUnit::Coulomb, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromCoulomb(): void
    {
        // 3600 C should auto-scale to 1 Ah
        $coulomb = Coulomb::of(NumberFactory::create('3600'));
        $scaled = $coulomb->autoScale();

        self::assertSame(ElectricChargeUnit::AmpereHour, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromMilliampereHour(): void
    {
        // 1000 mAh should auto-scale to 1 Ah
        $mAh = MilliampereHour::of(NumberFactory::create('1000'));
        $scaled = $mAh->autoScale();

        self::assertSame(ElectricChargeUnit::AmpereHour, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }
}
