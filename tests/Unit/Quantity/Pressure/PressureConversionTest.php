<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Pressure;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Pressure\Imperial\InchOfMercury;
use Andante\Measurement\Quantity\Pressure\Imperial\InchOfWater;
use Andante\Measurement\Quantity\Pressure\Imperial\PoundPerSquareFoot;
use Andante\Measurement\Quantity\Pressure\Imperial\PoundPerSquareInch;
use Andante\Measurement\Quantity\Pressure\ImperialPressure;
use Andante\Measurement\Quantity\Pressure\Pressure;
use Andante\Measurement\Quantity\Pressure\SI\Atmosphere;
use Andante\Measurement\Quantity\Pressure\SI\Bar;
use Andante\Measurement\Quantity\Pressure\SI\Gigapascal;
use Andante\Measurement\Quantity\Pressure\SI\Hectopascal;
use Andante\Measurement\Quantity\Pressure\SI\Kilopascal;
use Andante\Measurement\Quantity\Pressure\SI\Megapascal;
use Andante\Measurement\Quantity\Pressure\SI\Millibar;
use Andante\Measurement\Quantity\Pressure\SI\Pascal;
use Andante\Measurement\Quantity\Pressure\SI\Torr;
use Andante\Measurement\Quantity\Pressure\SIPressure;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Pressure\ImperialPressureUnit;
use Andante\Measurement\Unit\Pressure\SIPressureUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for pressure conversions.
 *
 * Pressure [L⁻¹M¹T⁻²] represents force per unit area.
 * Base unit: pascal (Pa), defined as N/m² = kg/(m·s²)
 *
 * Common conversions:
 * - 1 hPa = 100 Pa
 * - 1 kPa = 1000 Pa
 * - 1 MPa = 1,000,000 Pa
 * - 1 GPa = 1,000,000,000 Pa
 * - 1 bar = 100,000 Pa
 * - 1 mbar = 100 Pa
 * - 1 atm = 101,325 Pa (standard atmosphere)
 * - 1 torr = 133.322 Pa (1/760 atm)
 * - 1 psi = 6894.757 Pa
 * - 1 psf = 47.880 Pa
 * - 1 inHg = 3386.389 Pa
 * - 1 inH₂O = 249.089 Pa
 */
final class PressureConversionTest extends TestCase
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

    public function testPascalToHectopascal(): void
    {
        // 100 Pa = 1 hPa
        $pascal = Pascal::of(NumberFactory::create('100'));
        $hPa = $pascal->to(SIPressureUnit::Hectopascal);

        self::assertEqualsWithDelta(1.0, (float) $hPa->getValue()->value(), 0.001);
    }

    public function testHectopascalToPascal(): void
    {
        // 1 hPa = 100 Pa
        $hPa = Hectopascal::of(NumberFactory::create('1'));
        $pascal = $hPa->to(SIPressureUnit::Pascal);

        self::assertEqualsWithDelta(100.0, (float) $pascal->getValue()->value(), 0.001);
    }

    public function testPascalToKilopascal(): void
    {
        // 1000 Pa = 1 kPa
        $pascal = Pascal::of(NumberFactory::create('1000'));
        $kPa = $pascal->to(SIPressureUnit::Kilopascal);

        self::assertEqualsWithDelta(1.0, (float) $kPa->getValue()->value(), 0.001);
    }

    public function testKilopascalToPascal(): void
    {
        // 1 kPa = 1000 Pa
        $kPa = Kilopascal::of(NumberFactory::create('1'));
        $pascal = $kPa->to(SIPressureUnit::Pascal);

        self::assertEqualsWithDelta(1000.0, (float) $pascal->getValue()->value(), 0.001);
    }

    public function testPascalToMegapascal(): void
    {
        // 1,000,000 Pa = 1 MPa
        $pascal = Pascal::of(NumberFactory::create('1000000'));
        $MPa = $pascal->to(SIPressureUnit::Megapascal);

        self::assertEqualsWithDelta(1.0, (float) $MPa->getValue()->value(), 0.001);
    }

    public function testMegapascalToPascal(): void
    {
        // 1 MPa = 1,000,000 Pa
        $MPa = Megapascal::of(NumberFactory::create('1'));
        $pascal = $MPa->to(SIPressureUnit::Pascal);

        self::assertEqualsWithDelta(1000000.0, (float) $pascal->getValue()->value(), 0.001);
    }

    public function testPascalToGigapascal(): void
    {
        // 1,000,000,000 Pa = 1 GPa
        $pascal = Pascal::of(NumberFactory::create('1000000000'));
        $GPa = $pascal->to(SIPressureUnit::Gigapascal);

        self::assertEqualsWithDelta(1.0, (float) $GPa->getValue()->value(), 0.001);
    }

    public function testGigapascalToPascal(): void
    {
        // 1 GPa = 1,000,000,000 Pa
        $GPa = Gigapascal::of(NumberFactory::create('1'));
        $pascal = $GPa->to(SIPressureUnit::Pascal);

        self::assertEqualsWithDelta(1000000000.0, (float) $pascal->getValue()->value(), 0.001);
    }

    public function testPascalToBar(): void
    {
        // 100,000 Pa = 1 bar
        $pascal = Pascal::of(NumberFactory::create('100000'));
        $bar = $pascal->to(SIPressureUnit::Bar);

        self::assertEqualsWithDelta(1.0, (float) $bar->getValue()->value(), 0.001);
    }

    public function testBarToPascal(): void
    {
        // 1 bar = 100,000 Pa
        $bar = Bar::of(NumberFactory::create('1'));
        $pascal = $bar->to(SIPressureUnit::Pascal);

        self::assertEqualsWithDelta(100000.0, (float) $pascal->getValue()->value(), 0.001);
    }

    public function testPascalToMillibar(): void
    {
        // 100 Pa = 1 mbar
        $pascal = Pascal::of(NumberFactory::create('100'));
        $mbar = $pascal->to(SIPressureUnit::Millibar);

        self::assertEqualsWithDelta(1.0, (float) $mbar->getValue()->value(), 0.001);
    }

    public function testMillibarToPascal(): void
    {
        // 1 mbar = 100 Pa
        $mbar = Millibar::of(NumberFactory::create('1'));
        $pascal = $mbar->to(SIPressureUnit::Pascal);

        self::assertEqualsWithDelta(100.0, (float) $pascal->getValue()->value(), 0.001);
    }

    public function testPascalToAtmosphere(): void
    {
        // 101,325 Pa = 1 atm
        $pascal = Pascal::of(NumberFactory::create('101325'));
        $atm = $pascal->to(SIPressureUnit::Atmosphere);

        self::assertEqualsWithDelta(1.0, (float) $atm->getValue()->value(), 0.001);
    }

    public function testAtmosphereToPascal(): void
    {
        // 1 atm = 101,325 Pa
        $atm = Atmosphere::of(NumberFactory::create('1'));
        $pascal = $atm->to(SIPressureUnit::Pascal);

        self::assertEqualsWithDelta(101325.0, (float) $pascal->getValue()->value(), 0.001);
    }

    public function testPascalToTorr(): void
    {
        // 133.322 Pa ≈ 1 torr
        $pascal = Pascal::of(NumberFactory::create('133.32236842105263'));
        $torr = $pascal->to(SIPressureUnit::Torr);

        self::assertEqualsWithDelta(1.0, (float) $torr->getValue()->value(), 0.001);
    }

    public function testTorrToPascal(): void
    {
        // 1 torr ≈ 133.322 Pa
        $torr = Torr::of(NumberFactory::create('1'));
        $pascal = $torr->to(SIPressureUnit::Pascal);

        self::assertEqualsWithDelta(133.322, (float) $pascal->getValue()->value(), 0.01);
    }

    public function testAtmosphereToTorr(): void
    {
        // 1 atm = 760 torr (by definition)
        $atm = Atmosphere::of(NumberFactory::create('1'));
        $torr = $atm->to(SIPressureUnit::Torr);

        self::assertEqualsWithDelta(760.0, (float) $torr->getValue()->value(), 0.01);
    }

    public function testBarToMillibar(): void
    {
        // 1 bar = 1000 mbar
        $bar = Bar::of(NumberFactory::create('1'));
        $mbar = $bar->to(SIPressureUnit::Millibar);

        self::assertEqualsWithDelta(1000.0, (float) $mbar->getValue()->value(), 0.001);
    }

    // ========== Imperial Unit Tests ==========

    public function testPsiToPsf(): void
    {
        // 1 psi = 144 psf (12² = 144 square inches per square foot)
        $psi = PoundPerSquareInch::of(NumberFactory::create('1'));
        $psf = $psi->to(ImperialPressureUnit::PoundPerSquareFoot);

        self::assertEqualsWithDelta(144.0, (float) $psf->getValue()->value(), 0.1);
    }

    public function testPsfToPsi(): void
    {
        // 144 psf = 1 psi
        $psf = PoundPerSquareFoot::of(NumberFactory::create('144'));
        $psi = $psf->to(ImperialPressureUnit::PoundPerSquareInch);

        self::assertEqualsWithDelta(1.0, (float) $psi->getValue()->value(), 0.01);
    }

    public function testInchOfMercuryToInchOfWater(): void
    {
        // 1 inHg ≈ 13.595 inH₂O (mercury is ~13.595x denser than water)
        $inHg = InchOfMercury::of(NumberFactory::create('1'));
        $inH2O = $inHg->to(ImperialPressureUnit::InchOfWater);

        self::assertEqualsWithDelta(13.595, (float) $inH2O->getValue()->value(), 0.1);
    }

    public function testInchOfWaterToInchOfMercury(): void
    {
        // 13.595 inH₂O ≈ 1 inHg
        $inH2O = InchOfWater::of(NumberFactory::create('13.595'));
        $inHg = $inH2O->to(ImperialPressureUnit::InchOfMercury);

        self::assertEqualsWithDelta(1.0, (float) $inHg->getValue()->value(), 0.01);
    }

    public function testPsiToInchOfMercury(): void
    {
        // 1 psi ≈ 2.036 inHg
        $psi = PoundPerSquareInch::of(NumberFactory::create('1'));
        $inHg = $psi->to(ImperialPressureUnit::InchOfMercury);

        self::assertEqualsWithDelta(2.036, (float) $inHg->getValue()->value(), 0.01);
    }

    // ========== Cross-System Conversions ==========

    public function testPascalToPsi(): void
    {
        // 6894.757 Pa ≈ 1 psi
        $pascal = Pascal::of(NumberFactory::create('6894.757293168361'));
        $psi = $pascal->to(ImperialPressureUnit::PoundPerSquareInch);

        self::assertEqualsWithDelta(1.0, (float) $psi->getValue()->value(), 0.001);
    }

    public function testPsiToPascal(): void
    {
        // 1 psi = 6894.757 Pa
        $psi = PoundPerSquareInch::of(NumberFactory::create('1'));
        $pascal = $psi->to(SIPressureUnit::Pascal);

        self::assertEqualsWithDelta(6894.757, (float) $pascal->getValue()->value(), 0.01);
    }

    public function testBarToPsi(): void
    {
        // 1 bar ≈ 14.504 psi
        $bar = Bar::of(NumberFactory::create('1'));
        $psi = $bar->to(ImperialPressureUnit::PoundPerSquareInch);

        self::assertEqualsWithDelta(14.504, (float) $psi->getValue()->value(), 0.01);
    }

    public function testAtmosphereToPsi(): void
    {
        // 1 atm ≈ 14.696 psi
        $atm = Atmosphere::of(NumberFactory::create('1'));
        $psi = $atm->to(ImperialPressureUnit::PoundPerSquareInch);

        self::assertEqualsWithDelta(14.696, (float) $psi->getValue()->value(), 0.01);
    }

    public function testKilopascalToPsi(): void
    {
        // 1 kPa ≈ 0.145 psi
        $kPa = Kilopascal::of(NumberFactory::create('1'));
        $psi = $kPa->to(ImperialPressureUnit::PoundPerSquareInch);

        self::assertEqualsWithDelta(0.145, (float) $psi->getValue()->value(), 0.001);
    }

    public function testInchOfMercuryToKilopascal(): void
    {
        // 1 inHg ≈ 3.386 kPa
        $inHg = InchOfMercury::of(NumberFactory::create('1'));
        $kPa = $inHg->to(SIPressureUnit::Kilopascal);

        self::assertEqualsWithDelta(3.386, (float) $kPa->getValue()->value(), 0.01);
    }

    public function testAtmosphereToInchOfMercury(): void
    {
        // 1 atm ≈ 29.92 inHg (common weather reference)
        $atm = Atmosphere::of(NumberFactory::create('1'));
        $inHg = $atm->to(ImperialPressureUnit::InchOfMercury);

        self::assertEqualsWithDelta(29.92, (float) $inHg->getValue()->value(), 0.05);
    }

    // ========== Real-World Scenario Tests ==========

    public function testTirePressure(): void
    {
        // Typical car tire pressure: 32 psi
        $tirePressure = PoundPerSquareInch::of(NumberFactory::create('32'));

        // Convert to bar (common in Europe)
        $bar = $tirePressure->to(SIPressureUnit::Bar);
        self::assertEqualsWithDelta(2.206, (float) $bar->getValue()->value(), 0.01);

        // Convert to kPa (common in some countries)
        $kPa = $tirePressure->to(SIPressureUnit::Kilopascal);
        self::assertEqualsWithDelta(220.6, (float) $kPa->getValue()->value(), 1.0);
    }

    public function testWeatherBarometricPressure(): void
    {
        // Normal atmospheric pressure at sea level: 1013.25 hPa
        $pressure = Hectopascal::of(NumberFactory::create('1013.25'));

        // Convert to inHg (common in US weather reports)
        $inHg = $pressure->to(ImperialPressureUnit::InchOfMercury);
        self::assertEqualsWithDelta(29.92, (float) $inHg->getValue()->value(), 0.05);

        // Convert to mbar (also common in meteorology)
        $mbar = $pressure->to(SIPressureUnit::Millibar);
        self::assertEqualsWithDelta(1013.25, (float) $mbar->getValue()->value(), 0.01);
    }

    public function testScubaDivingDepthPressure(): void
    {
        // At 30 meters depth: ~4 atm (1 atm surface + ~3 atm from water)
        $pressure = Atmosphere::of(NumberFactory::create('4'));

        // Convert to bar
        $bar = $pressure->to(SIPressureUnit::Bar);
        self::assertEqualsWithDelta(4.053, (float) $bar->getValue()->value(), 0.01);

        // Convert to psi
        $psi = $pressure->to(ImperialPressureUnit::PoundPerSquareInch);
        self::assertEqualsWithDelta(58.78, (float) $psi->getValue()->value(), 0.1);
    }

    public function testIndustrialHydraulics(): void
    {
        // Hydraulic press operating at 200 bar
        $pressure = Bar::of(NumberFactory::create('200'));

        // Convert to psi
        $psi = $pressure->to(ImperialPressureUnit::PoundPerSquareInch);
        self::assertEqualsWithDelta(2900.8, (float) $psi->getValue()->value(), 1.0);

        // Convert to MPa
        $MPa = $pressure->to(SIPressureUnit::Megapascal);
        self::assertEqualsWithDelta(20.0, (float) $MPa->getValue()->value(), 0.001);
    }

    public function testMaterialStrength(): void
    {
        // Steel yield strength: ~250 MPa
        $yieldStrength = Megapascal::of(NumberFactory::create('250'));

        // Convert to GPa
        $GPa = $yieldStrength->to(SIPressureUnit::Gigapascal);
        self::assertEqualsWithDelta(0.25, (float) $GPa->getValue()->value(), 0.001);

        // Convert to psi (common in US engineering)
        $psi = $yieldStrength->to(ImperialPressureUnit::PoundPerSquareInch);
        self::assertEqualsWithDelta(36260, (float) $psi->getValue()->value(), 100);
    }

    public function testBloodPressure(): void
    {
        // Systolic blood pressure: 120 mmHg ≈ 120 torr
        $bloodPressure = Torr::of(NumberFactory::create('120'));

        // Convert to kPa
        $kPa = $bloodPressure->to(SIPressureUnit::Kilopascal);
        self::assertEqualsWithDelta(16.0, (float) $kPa->getValue()->value(), 0.1);

        // Convert to psi
        $psi = $bloodPressure->to(ImperialPressureUnit::PoundPerSquareInch);
        self::assertEqualsWithDelta(2.32, (float) $psi->getValue()->value(), 0.05);
    }

    // ========== Mid-Level Class Tests ==========

    public function testSIPressureCreation(): void
    {
        $pressure = SIPressure::of(
            NumberFactory::create('100000'),
            SIPressureUnit::Pascal,
        );

        self::assertEquals('100000', $pressure->getValue()->value());
        self::assertSame(SIPressureUnit::Pascal, $pressure->getUnit());
    }

    public function testImperialPressureCreation(): void
    {
        $pressure = ImperialPressure::of(
            NumberFactory::create('14.7'),
            ImperialPressureUnit::PoundPerSquareInch,
        );

        self::assertEquals('14.7', $pressure->getValue()->value());
        self::assertSame(ImperialPressureUnit::PoundPerSquareInch, $pressure->getUnit());
    }

    public function testSIPressureConversion(): void
    {
        $pressure = SIPressure::of(
            NumberFactory::create('101325'),
            SIPressureUnit::Pascal,
        );

        $converted = $pressure->to(SIPressureUnit::Atmosphere);
        self::assertEqualsWithDelta(1.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericPressureWithSIUnit(): void
    {
        $pressure = Pressure::of(
            NumberFactory::create('1'),
            SIPressureUnit::Bar,
        );

        self::assertEquals('1', $pressure->getValue()->value());
        self::assertSame(SIPressureUnit::Bar, $pressure->getUnit());
    }

    public function testGenericPressureWithImperialUnit(): void
    {
        $pressure = Pressure::of(
            NumberFactory::create('100'),
            ImperialPressureUnit::PoundPerSquareInch,
        );

        self::assertEquals('100', $pressure->getValue()->value());
        self::assertSame(ImperialPressureUnit::PoundPerSquareInch, $pressure->getUnit());
    }

    public function testGenericPressureConversion(): void
    {
        $pressure = Pressure::of(
            NumberFactory::create('1'),
            SIPressureUnit::Atmosphere,
        );

        $converted = $pressure->to(ImperialPressureUnit::PoundPerSquareInch);
        self::assertEqualsWithDelta(14.696, (float) $converted->getValue()->value(), 0.01);
    }

    // ========== Round-Trip Tests ==========

    public function testSIRoundTrip(): void
    {
        $original = Pascal::of(NumberFactory::create('101325'));
        $toAtm = $original->to(SIPressureUnit::Atmosphere);

        $atmQuantity = Atmosphere::of($toAtm->getValue());
        $backToPa = $atmQuantity->to(SIPressureUnit::Pascal);

        self::assertEqualsWithDelta(101325.0, (float) $backToPa->getValue()->value(), 0.1);
    }

    public function testImperialRoundTrip(): void
    {
        $original = PoundPerSquareInch::of(NumberFactory::create('144'));
        $toPsf = $original->to(ImperialPressureUnit::PoundPerSquareFoot);

        $psfQuantity = PoundPerSquareFoot::of($toPsf->getValue());
        $backToPsi = $psfQuantity->to(ImperialPressureUnit::PoundPerSquareInch);

        self::assertEqualsWithDelta(144.0, (float) $backToPsi->getValue()->value(), 0.1);
    }

    public function testCrossSystemRoundTrip(): void
    {
        $original = Bar::of(NumberFactory::create('1'));
        $toPsi = $original->to(ImperialPressureUnit::PoundPerSquareInch);

        $psiQuantity = PoundPerSquareInch::of($toPsi->getValue());
        $backToBar = $psiQuantity->to(SIPressureUnit::Bar);

        self::assertEqualsWithDelta(1.0, (float) $backToBar->getValue()->value(), 0.01);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $p1 = Pascal::of(NumberFactory::create('50000'));
        $p2 = Pascal::of(NumberFactory::create('30000'));

        $sum = $p1->add($p2);

        self::assertEqualsWithDelta(80000.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $p1 = Bar::of(NumberFactory::create('5'));
        $p2 = Bar::of(NumberFactory::create('2'));

        $diff = $p1->subtract($p2);

        self::assertEqualsWithDelta(3.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $pressure = Atmosphere::of(NumberFactory::create('2'));
        $result = $pressure->multiplyBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(6.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $pressure = Kilopascal::of(NumberFactory::create('300'));
        $result = $pressure->divideBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(100.0, (float) $result->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $p1 = Pascal::of(NumberFactory::create('102000'));
        $p2 = Pascal::of(NumberFactory::create('101325'));

        self::assertTrue($p1->isGreaterThan($p2));
        self::assertFalse($p1->isLessThan($p2));
        self::assertFalse($p1->equals($p2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 bar > 0.9 atm
        $bar = Bar::of(NumberFactory::create('1'));
        $atm = Atmosphere::of(NumberFactory::create('0.9'));

        self::assertTrue($bar->isGreaterThan($atm));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 bar = 1000 mbar
        $bar = Bar::of(NumberFactory::create('1'));
        $mbar = Millibar::of(NumberFactory::create('1000'));

        self::assertTrue($bar->equals($mbar));
    }

    public function testCrossSystemComparison(): void
    {
        // 1 bar converted to Pa should equal 100000 Pa
        $barToPa = Bar::of(NumberFactory::create('1'))->to(SIPressureUnit::Pascal);
        $direct = Pascal::of(NumberFactory::create('100000'));

        self::assertEqualsWithDelta(
            (float) $barToPa->getValue()->value(),
            (float) $direct->getValue()->value(),
            0.001,
        );
    }
}
