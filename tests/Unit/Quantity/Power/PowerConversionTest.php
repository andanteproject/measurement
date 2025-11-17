<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Power;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Power\Imperial\BTUPerHour;
use Andante\Measurement\Quantity\Power\Imperial\ElectricalHorsepower;
use Andante\Measurement\Quantity\Power\Imperial\FootPoundPerSecond;
use Andante\Measurement\Quantity\Power\Imperial\MechanicalHorsepower;
use Andante\Measurement\Quantity\Power\Imperial\MetricHorsepower;
use Andante\Measurement\Quantity\Power\ImperialPower;
use Andante\Measurement\Quantity\Power\Power;
use Andante\Measurement\Quantity\Power\SI\Gigawatt;
use Andante\Measurement\Quantity\Power\SI\Kilowatt;
use Andante\Measurement\Quantity\Power\SI\Megawatt;
use Andante\Measurement\Quantity\Power\SI\Milliwatt;
use Andante\Measurement\Quantity\Power\SI\Watt;
use Andante\Measurement\Quantity\Power\SIPower;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Power\ImperialPowerUnit;
use Andante\Measurement\Unit\Power\SIPowerUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for power conversions.
 *
 * Power [L²M¹T⁻³] represents the rate of energy transfer.
 * Base unit: watt (W), defined as J/s = kg⋅m²/s³
 *
 * Common conversions:
 * - 1 mW = 0.001 W
 * - 1 kW = 1000 W
 * - 1 MW = 1,000,000 W
 * - 1 GW = 1,000,000,000 W
 * - 1 hp (mechanical) = 745.7 W
 * - 1 hp (electrical) = 746 W (exact)
 * - 1 PS (metric hp) = 735.5 W
 * - 1 ft⋅lbf/s = 1.356 W
 * - 1 BTU/h = 0.293 W
 */
final class PowerConversionTest extends TestCase
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

    public function testWattToKilowatt(): void
    {
        // 1000 W = 1 kW
        $watt = Watt::of(NumberFactory::create('1000'));
        $kW = $watt->to(SIPowerUnit::Kilowatt);

        self::assertEqualsWithDelta(1.0, (float) $kW->getValue()->value(), 0.001);
    }

    public function testKilowattToWatt(): void
    {
        // 1 kW = 1000 W
        $kW = Kilowatt::of(NumberFactory::create('1'));
        $watt = $kW->to(SIPowerUnit::Watt);

        self::assertEqualsWithDelta(1000.0, (float) $watt->getValue()->value(), 0.001);
    }

    public function testWattToMegawatt(): void
    {
        // 1,000,000 W = 1 MW
        $watt = Watt::of(NumberFactory::create('1000000'));
        $MW = $watt->to(SIPowerUnit::Megawatt);

        self::assertEqualsWithDelta(1.0, (float) $MW->getValue()->value(), 0.001);
    }

    public function testMegawattToWatt(): void
    {
        // 1 MW = 1,000,000 W
        $MW = Megawatt::of(NumberFactory::create('1'));
        $watt = $MW->to(SIPowerUnit::Watt);

        self::assertEqualsWithDelta(1000000.0, (float) $watt->getValue()->value(), 0.001);
    }

    public function testWattToGigawatt(): void
    {
        // 1,000,000,000 W = 1 GW
        $watt = Watt::of(NumberFactory::create('1000000000'));
        $GW = $watt->to(SIPowerUnit::Gigawatt);

        self::assertEqualsWithDelta(1.0, (float) $GW->getValue()->value(), 0.001);
    }

    public function testGigawattToWatt(): void
    {
        // 1 GW = 1,000,000,000 W
        $GW = Gigawatt::of(NumberFactory::create('1'));
        $watt = $GW->to(SIPowerUnit::Watt);

        self::assertEqualsWithDelta(1000000000.0, (float) $watt->getValue()->value(), 0.001);
    }

    public function testWattToMilliwatt(): void
    {
        // 1 W = 1000 mW
        $watt = Watt::of(NumberFactory::create('1'));
        $mW = $watt->to(SIPowerUnit::Milliwatt);

        self::assertEqualsWithDelta(1000.0, (float) $mW->getValue()->value(), 0.001);
    }

    public function testMilliwattToWatt(): void
    {
        // 1000 mW = 1 W
        $mW = Milliwatt::of(NumberFactory::create('1000'));
        $watt = $mW->to(SIPowerUnit::Watt);

        self::assertEqualsWithDelta(1.0, (float) $watt->getValue()->value(), 0.001);
    }

    public function testKilowattToMegawatt(): void
    {
        // 1000 kW = 1 MW
        $kW = Kilowatt::of(NumberFactory::create('1000'));
        $MW = $kW->to(SIPowerUnit::Megawatt);

        self::assertEqualsWithDelta(1.0, (float) $MW->getValue()->value(), 0.001);
    }

    // ========== Imperial Unit Tests ==========

    public function testMechanicalToElectricalHorsepower(): void
    {
        // 1 hp (mechanical) ≈ 0.9986 hp (electrical)
        $mechHp = MechanicalHorsepower::of(NumberFactory::create('1'));
        $elecHp = $mechHp->to(ImperialPowerUnit::ElectricalHorsepower);

        self::assertEqualsWithDelta(0.999, (float) $elecHp->getValue()->value(), 0.01);
    }

    public function testMechanicalToMetricHorsepower(): void
    {
        // 1 hp (mechanical) ≈ 1.0139 PS
        $mechHp = MechanicalHorsepower::of(NumberFactory::create('1'));
        $metricHp = $mechHp->to(ImperialPowerUnit::MetricHorsepower);

        self::assertEqualsWithDelta(1.014, (float) $metricHp->getValue()->value(), 0.01);
    }

    public function testHorsepowerToFootPoundPerSecond(): void
    {
        // 1 hp (mechanical) = 550 ft⋅lbf/s (by definition)
        $hp = MechanicalHorsepower::of(NumberFactory::create('1'));
        $ftlbfs = $hp->to(ImperialPowerUnit::FootPoundPerSecond);

        self::assertEqualsWithDelta(550.0, (float) $ftlbfs->getValue()->value(), 0.5);
    }

    public function testFootPoundPerSecondToHorsepower(): void
    {
        // 550 ft⋅lbf/s = 1 hp (mechanical)
        $ftlbfs = FootPoundPerSecond::of(NumberFactory::create('550'));
        $hp = $ftlbfs->to(ImperialPowerUnit::MechanicalHorsepower);

        self::assertEqualsWithDelta(1.0, (float) $hp->getValue()->value(), 0.01);
    }

    public function testHorsepowerToBTUPerHour(): void
    {
        // 1 hp (mechanical) ≈ 2544 BTU/h
        $hp = MechanicalHorsepower::of(NumberFactory::create('1'));
        $btuh = $hp->to(ImperialPowerUnit::BTUPerHour);

        self::assertEqualsWithDelta(2544.4, (float) $btuh->getValue()->value(), 5.0);
    }

    public function testBTUPerHourToHorsepower(): void
    {
        // 2544 BTU/h ≈ 1 hp
        $btuh = BTUPerHour::of(NumberFactory::create('2544.4'));
        $hp = $btuh->to(ImperialPowerUnit::MechanicalHorsepower);

        self::assertEqualsWithDelta(1.0, (float) $hp->getValue()->value(), 0.01);
    }

    // ========== Cross-System Conversions ==========

    public function testWattToMechanicalHorsepower(): void
    {
        // 745.7 W ≈ 1 hp (mechanical)
        $watt = Watt::of(NumberFactory::create('745.69987158227022'));
        $hp = $watt->to(ImperialPowerUnit::MechanicalHorsepower);

        self::assertEqualsWithDelta(1.0, (float) $hp->getValue()->value(), 0.001);
    }

    public function testMechanicalHorsepowerToWatt(): void
    {
        // 1 hp (mechanical) = 745.7 W
        $hp = MechanicalHorsepower::of(NumberFactory::create('1'));
        $watt = $hp->to(SIPowerUnit::Watt);

        self::assertEqualsWithDelta(745.7, (float) $watt->getValue()->value(), 0.1);
    }

    public function testKilowattToMechanicalHorsepower(): void
    {
        // 1 kW ≈ 1.341 hp
        $kW = Kilowatt::of(NumberFactory::create('1'));
        $hp = $kW->to(ImperialPowerUnit::MechanicalHorsepower);

        self::assertEqualsWithDelta(1.341, (float) $hp->getValue()->value(), 0.01);
    }

    public function testElectricalHorsepowerToWatt(): void
    {
        // 1 hp (electrical) = 746 W (exact)
        $hp = ElectricalHorsepower::of(NumberFactory::create('1'));
        $watt = $hp->to(SIPowerUnit::Watt);

        self::assertEqualsWithDelta(746.0, (float) $watt->getValue()->value(), 0.001);
    }

    public function testMetricHorsepowerToWatt(): void
    {
        // 1 PS = 735.49875 W
        $ps = MetricHorsepower::of(NumberFactory::create('1'));
        $watt = $ps->to(SIPowerUnit::Watt);

        self::assertEqualsWithDelta(735.5, (float) $watt->getValue()->value(), 0.1);
    }

    public function testBTUPerHourToWatt(): void
    {
        // 1 BTU/h = 0.293 W
        $btuh = BTUPerHour::of(NumberFactory::create('1'));
        $watt = $btuh->to(SIPowerUnit::Watt);

        self::assertEqualsWithDelta(0.293, (float) $watt->getValue()->value(), 0.01);
    }

    public function testFootPoundPerSecondToWatt(): void
    {
        // 1 ft⋅lbf/s = 1.356 W
        $ftlbfs = FootPoundPerSecond::of(NumberFactory::create('1'));
        $watt = $ftlbfs->to(SIPowerUnit::Watt);

        self::assertEqualsWithDelta(1.356, (float) $watt->getValue()->value(), 0.01);
    }

    // ========== Real-World Scenario Tests ==========

    public function testHouseholdAppliance(): void
    {
        // A typical microwave: 1000 W
        $microwave = Watt::of(NumberFactory::create('1000'));

        // Convert to kW
        $kW = $microwave->to(SIPowerUnit::Kilowatt);
        self::assertEqualsWithDelta(1.0, (float) $kW->getValue()->value(), 0.001);

        // Convert to hp
        $hp = $microwave->to(ImperialPowerUnit::MechanicalHorsepower);
        self::assertEqualsWithDelta(1.341, (float) $hp->getValue()->value(), 0.01);
    }

    public function testCarEngine(): void
    {
        // A typical car engine: 150 hp
        $engine = MechanicalHorsepower::of(NumberFactory::create('150'));

        // Convert to kW
        $kW = $engine->to(SIPowerUnit::Kilowatt);
        self::assertEqualsWithDelta(111.86, (float) $kW->getValue()->value(), 0.5);

        // Convert to PS (European specs)
        $ps = $engine->to(ImperialPowerUnit::MetricHorsepower);
        self::assertEqualsWithDelta(152.1, (float) $ps->getValue()->value(), 0.5);
    }

    public function testPowerPlant(): void
    {
        // A nuclear power plant: 1 GW
        $plant = Gigawatt::of(NumberFactory::create('1'));

        // Convert to MW
        $MW = $plant->to(SIPowerUnit::Megawatt);
        self::assertEqualsWithDelta(1000.0, (float) $MW->getValue()->value(), 0.001);

        // Convert to hp
        $hp = $plant->to(ImperialPowerUnit::MechanicalHorsepower);
        self::assertEqualsWithDelta(1341022, (float) $hp->getValue()->value(), 1000);
    }

    public function testHVACSystem(): void
    {
        // A typical HVAC system rated at 12000 BTU/h (1 ton of cooling)
        $hvac = BTUPerHour::of(NumberFactory::create('12000'));

        // Convert to kW
        $kW = $hvac->to(SIPowerUnit::Kilowatt);
        self::assertEqualsWithDelta(3.517, (float) $kW->getValue()->value(), 0.01);

        // Convert to hp
        $hp = $hvac->to(ImperialPowerUnit::MechanicalHorsepower);
        self::assertEqualsWithDelta(4.717, (float) $hp->getValue()->value(), 0.05);
    }

    public function testElectricVehicleCharger(): void
    {
        // Level 2 EV charger: 7.2 kW
        $charger = Kilowatt::of(NumberFactory::create('7.2'));

        // Convert to W
        $watt = $charger->to(SIPowerUnit::Watt);
        self::assertEqualsWithDelta(7200.0, (float) $watt->getValue()->value(), 0.001);

        // Convert to hp
        $hp = $charger->to(ImperialPowerUnit::MechanicalHorsepower);
        self::assertEqualsWithDelta(9.655, (float) $hp->getValue()->value(), 0.05);
    }

    // ========== Mid-Level Class Tests ==========

    public function testSIPowerCreation(): void
    {
        $power = SIPower::of(
            NumberFactory::create('1000'),
            SIPowerUnit::Watt,
        );

        self::assertEquals('1000', $power->getValue()->value());
        self::assertSame(SIPowerUnit::Watt, $power->getUnit());
    }

    public function testImperialPowerCreation(): void
    {
        $power = ImperialPower::of(
            NumberFactory::create('100'),
            ImperialPowerUnit::MechanicalHorsepower,
        );

        self::assertEquals('100', $power->getValue()->value());
        self::assertSame(ImperialPowerUnit::MechanicalHorsepower, $power->getUnit());
    }

    public function testSIPowerConversion(): void
    {
        $power = SIPower::of(
            NumberFactory::create('1000'),
            SIPowerUnit::Watt,
        );

        $converted = $power->to(SIPowerUnit::Kilowatt);
        self::assertEqualsWithDelta(1.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericPowerWithSIUnit(): void
    {
        $power = Power::of(
            NumberFactory::create('1000'),
            SIPowerUnit::Kilowatt,
        );

        self::assertEquals('1000', $power->getValue()->value());
        self::assertSame(SIPowerUnit::Kilowatt, $power->getUnit());
    }

    public function testGenericPowerWithImperialUnit(): void
    {
        $power = Power::of(
            NumberFactory::create('100'),
            ImperialPowerUnit::MechanicalHorsepower,
        );

        self::assertEquals('100', $power->getValue()->value());
        self::assertSame(ImperialPowerUnit::MechanicalHorsepower, $power->getUnit());
    }

    public function testGenericPowerConversion(): void
    {
        $power = Power::of(
            NumberFactory::create('1'),
            ImperialPowerUnit::MechanicalHorsepower,
        );

        $converted = $power->to(SIPowerUnit::Watt);
        self::assertEqualsWithDelta(745.7, (float) $converted->getValue()->value(), 0.1);
    }

    // ========== Round-Trip Tests ==========

    public function testSIRoundTrip(): void
    {
        $original = Watt::of(NumberFactory::create('1000'));
        $toKW = $original->to(SIPowerUnit::Kilowatt);

        $kWQuantity = Kilowatt::of($toKW->getValue());
        $backToW = $kWQuantity->to(SIPowerUnit::Watt);

        self::assertEqualsWithDelta(1000.0, (float) $backToW->getValue()->value(), 0.001);
    }

    public function testImperialRoundTrip(): void
    {
        $original = MechanicalHorsepower::of(NumberFactory::create('100'));
        $toFtlbfs = $original->to(ImperialPowerUnit::FootPoundPerSecond);

        $ftlbfsQuantity = FootPoundPerSecond::of($toFtlbfs->getValue());
        $backToHp = $ftlbfsQuantity->to(ImperialPowerUnit::MechanicalHorsepower);

        self::assertEqualsWithDelta(100.0, (float) $backToHp->getValue()->value(), 0.1);
    }

    public function testCrossSystemRoundTrip(): void
    {
        $original = Kilowatt::of(NumberFactory::create('1'));
        $toHp = $original->to(ImperialPowerUnit::MechanicalHorsepower);

        $hpQuantity = MechanicalHorsepower::of($toHp->getValue());
        $backToKW = $hpQuantity->to(SIPowerUnit::Kilowatt);

        self::assertEqualsWithDelta(1.0, (float) $backToKW->getValue()->value(), 0.01);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $p1 = Watt::of(NumberFactory::create('500'));
        $p2 = Watt::of(NumberFactory::create('300'));

        $sum = $p1->add($p2);

        self::assertEqualsWithDelta(800.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $p1 = Kilowatt::of(NumberFactory::create('5'));
        $p2 = Kilowatt::of(NumberFactory::create('2'));

        $diff = $p1->subtract($p2);

        self::assertEqualsWithDelta(3.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $power = Kilowatt::of(NumberFactory::create('2'));
        $result = $power->multiplyBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(6.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $power = Megawatt::of(NumberFactory::create('300'));
        $result = $power->divideBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(100.0, (float) $result->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $p1 = Watt::of(NumberFactory::create('1000'));
        $p2 = Watt::of(NumberFactory::create('800'));

        self::assertTrue($p1->isGreaterThan($p2));
        self::assertFalse($p1->isLessThan($p2));
        self::assertFalse($p1->equals($p2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 kW > 500 W
        $kW = Kilowatt::of(NumberFactory::create('1'));
        $watt = Watt::of(NumberFactory::create('500'));

        self::assertTrue($kW->isGreaterThan($watt));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 kW = 1000 W
        $kW = Kilowatt::of(NumberFactory::create('1'));
        $watt = Watt::of(NumberFactory::create('1000'));

        self::assertTrue($kW->equals($watt));
    }

    public function testCrossSystemComparison(): void
    {
        // 1 kW converted to W should equal 1000 W
        $kWtoW = Kilowatt::of(NumberFactory::create('1'))->to(SIPowerUnit::Watt);
        $direct = Watt::of(NumberFactory::create('1000'));

        self::assertEqualsWithDelta(
            (float) $kWtoW->getValue()->value(),
            (float) $direct->getValue()->value(),
            0.001,
        );
    }
}
