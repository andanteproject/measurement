<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Density;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Density\Density;
use Andante\Measurement\Quantity\Density\Imperial\OuncePerCubicInch;
use Andante\Measurement\Quantity\Density\Imperial\PoundPerCubicFoot;
use Andante\Measurement\Quantity\Density\Imperial\PoundPerCubicInch;
use Andante\Measurement\Quantity\Density\Imperial\PoundPerGallon;
use Andante\Measurement\Quantity\Density\Imperial\SlugPerCubicFoot;
use Andante\Measurement\Quantity\Density\ImperialDensity;
use Andante\Measurement\Quantity\Density\SI\GramPerCubicCentimeter;
use Andante\Measurement\Quantity\Density\SI\GramPerCubicMeter;
use Andante\Measurement\Quantity\Density\SI\GramPerLiter;
use Andante\Measurement\Quantity\Density\SI\KilogramPerCubicMeter;
use Andante\Measurement\Quantity\Density\SI\KilogramPerLiter;
use Andante\Measurement\Quantity\Density\SI\MilligramPerCubicMeter;
use Andante\Measurement\Quantity\Density\SI\TonnePerCubicMeter;
use Andante\Measurement\Quantity\Density\SIDensity;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Density\ImperialDensityUnit;
use Andante\Measurement\Unit\Density\SIDensityUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for density conversions.
 *
 * Density [L⁻³M¹] represents mass per unit volume.
 * Base unit: kilogram per cubic meter (kg/m³)
 *
 * Common conversions:
 * - 1 g/m³ = 0.001 kg/m³
 * - 1 g/cm³ = 1000 kg/m³
 * - 1 g/L = 1 kg/m³
 * - 1 kg/L = 1000 kg/m³
 * - 1 mg/m³ = 0.000001 kg/m³
 * - 1 t/m³ = 1000 kg/m³
 * - 1 lb/ft³ = 16.018 kg/m³
 * - 1 lb/in³ = 27,679.9 kg/m³
 * - 1 lb/gal = 119.826 kg/m³
 * - 1 oz/in³ = 1,729.99 kg/m³
 * - 1 slug/ft³ = 515.379 kg/m³
 */
final class DensityConversionTest extends TestCase
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

    public function testKilogramPerCubicMeterToGramPerCubicMeter(): void
    {
        // 1 kg/m³ = 1000 g/m³
        $kgm3 = KilogramPerCubicMeter::of(NumberFactory::create('1'));
        $gm3 = $kgm3->to(SIDensityUnit::GramPerCubicMeter);

        self::assertEqualsWithDelta(1000.0, (float) $gm3->getValue()->value(), 0.001);
    }

    public function testGramPerCubicMeterToKilogramPerCubicMeter(): void
    {
        // 1000 g/m³ = 1 kg/m³
        $gm3 = GramPerCubicMeter::of(NumberFactory::create('1000'));
        $kgm3 = $gm3->to(SIDensityUnit::KilogramPerCubicMeter);

        self::assertEqualsWithDelta(1.0, (float) $kgm3->getValue()->value(), 0.001);
    }

    public function testKilogramPerCubicMeterToGramPerCubicCentimeter(): void
    {
        // 1000 kg/m³ = 1 g/cm³
        $kgm3 = KilogramPerCubicMeter::of(NumberFactory::create('1000'));
        $gcm3 = $kgm3->to(SIDensityUnit::GramPerCubicCentimeter);

        self::assertEqualsWithDelta(1.0, (float) $gcm3->getValue()->value(), 0.001);
    }

    public function testGramPerCubicCentimeterToKilogramPerCubicMeter(): void
    {
        // 1 g/cm³ = 1000 kg/m³
        $gcm3 = GramPerCubicCentimeter::of(NumberFactory::create('1'));
        $kgm3 = $gcm3->to(SIDensityUnit::KilogramPerCubicMeter);

        self::assertEqualsWithDelta(1000.0, (float) $kgm3->getValue()->value(), 0.001);
    }

    public function testKilogramPerCubicMeterToGramPerLiter(): void
    {
        // 1 kg/m³ = 1 g/L
        $kgm3 = KilogramPerCubicMeter::of(NumberFactory::create('1'));
        $gL = $kgm3->to(SIDensityUnit::GramPerLiter);

        self::assertEqualsWithDelta(1.0, (float) $gL->getValue()->value(), 0.001);
    }

    public function testGramPerLiterToKilogramPerCubicMeter(): void
    {
        // 1 g/L = 1 kg/m³
        $gL = GramPerLiter::of(NumberFactory::create('1'));
        $kgm3 = $gL->to(SIDensityUnit::KilogramPerCubicMeter);

        self::assertEqualsWithDelta(1.0, (float) $kgm3->getValue()->value(), 0.001);
    }

    public function testKilogramPerCubicMeterToKilogramPerLiter(): void
    {
        // 1000 kg/m³ = 1 kg/L
        $kgm3 = KilogramPerCubicMeter::of(NumberFactory::create('1000'));
        $kgL = $kgm3->to(SIDensityUnit::KilogramPerLiter);

        self::assertEqualsWithDelta(1.0, (float) $kgL->getValue()->value(), 0.001);
    }

    public function testKilogramPerLiterToKilogramPerCubicMeter(): void
    {
        // 1 kg/L = 1000 kg/m³
        $kgL = KilogramPerLiter::of(NumberFactory::create('1'));
        $kgm3 = $kgL->to(SIDensityUnit::KilogramPerCubicMeter);

        self::assertEqualsWithDelta(1000.0, (float) $kgm3->getValue()->value(), 0.001);
    }

    public function testKilogramPerCubicMeterToMilligramPerCubicMeter(): void
    {
        // 1 kg/m³ = 1,000,000 mg/m³
        $kgm3 = KilogramPerCubicMeter::of(NumberFactory::create('1'));
        $mgm3 = $kgm3->to(SIDensityUnit::MilligramPerCubicMeter);

        self::assertEqualsWithDelta(1000000.0, (float) $mgm3->getValue()->value(), 0.001);
    }

    public function testMilligramPerCubicMeterToKilogramPerCubicMeter(): void
    {
        // 1,000,000 mg/m³ = 1 kg/m³
        $mgm3 = MilligramPerCubicMeter::of(NumberFactory::create('1000000'));
        $kgm3 = $mgm3->to(SIDensityUnit::KilogramPerCubicMeter);

        self::assertEqualsWithDelta(1.0, (float) $kgm3->getValue()->value(), 0.001);
    }

    public function testKilogramPerCubicMeterToTonnePerCubicMeter(): void
    {
        // 1000 kg/m³ = 1 t/m³
        $kgm3 = KilogramPerCubicMeter::of(NumberFactory::create('1000'));
        $tm3 = $kgm3->to(SIDensityUnit::TonnePerCubicMeter);

        self::assertEqualsWithDelta(1.0, (float) $tm3->getValue()->value(), 0.001);
    }

    public function testTonnePerCubicMeterToKilogramPerCubicMeter(): void
    {
        // 1 t/m³ = 1000 kg/m³
        $tm3 = TonnePerCubicMeter::of(NumberFactory::create('1'));
        $kgm3 = $tm3->to(SIDensityUnit::KilogramPerCubicMeter);

        self::assertEqualsWithDelta(1000.0, (float) $kgm3->getValue()->value(), 0.001);
    }

    public function testGramPerCubicCentimeterToGramPerLiter(): void
    {
        // 1 g/cm³ = 1000 g/L
        $gcm3 = GramPerCubicCentimeter::of(NumberFactory::create('1'));
        $gL = $gcm3->to(SIDensityUnit::GramPerLiter);

        self::assertEqualsWithDelta(1000.0, (float) $gL->getValue()->value(), 0.001);
    }

    // ========== Imperial Unit Tests ==========

    public function testPoundPerCubicFootToPoundPerCubicInch(): void
    {
        // 1 lb/ft³ = 1/1728 lb/in³ (12³ = 1728)
        $lbft3 = PoundPerCubicFoot::of(NumberFactory::create('1728'));
        $lbin3 = $lbft3->to(ImperialDensityUnit::PoundPerCubicInch);

        self::assertEqualsWithDelta(1.0, (float) $lbin3->getValue()->value(), 0.01);
    }

    public function testPoundPerCubicInchToPoundPerCubicFoot(): void
    {
        // 1 lb/in³ = 1728 lb/ft³
        $lbin3 = PoundPerCubicInch::of(NumberFactory::create('1'));
        $lbft3 = $lbin3->to(ImperialDensityUnit::PoundPerCubicFoot);

        self::assertEqualsWithDelta(1728.0, (float) $lbft3->getValue()->value(), 1.0);
    }

    public function testPoundPerCubicFootToPoundPerGallon(): void
    {
        // 1 lb/ft³ ≈ 0.1337 lb/gal (US)
        $lbft3 = PoundPerCubicFoot::of(NumberFactory::create('1'));
        $lbgal = $lbft3->to(ImperialDensityUnit::PoundPerGallon);

        self::assertEqualsWithDelta(0.1337, (float) $lbgal->getValue()->value(), 0.01);
    }

    public function testPoundPerGallonToPoundPerCubicFoot(): void
    {
        // 1 lb/gal ≈ 7.48 lb/ft³
        $lbgal = PoundPerGallon::of(NumberFactory::create('1'));
        $lbft3 = $lbgal->to(ImperialDensityUnit::PoundPerCubicFoot);

        self::assertEqualsWithDelta(7.48, (float) $lbft3->getValue()->value(), 0.1);
    }

    public function testPoundPerCubicInchToOuncePerCubicInch(): void
    {
        // 1 lb/in³ = 16 oz/in³
        $lbin3 = PoundPerCubicInch::of(NumberFactory::create('1'));
        $ozin3 = $lbin3->to(ImperialDensityUnit::OuncePerCubicInch);

        self::assertEqualsWithDelta(16.0, (float) $ozin3->getValue()->value(), 0.1);
    }

    public function testOuncePerCubicInchToPoundPerCubicInch(): void
    {
        // 16 oz/in³ = 1 lb/in³
        $ozin3 = OuncePerCubicInch::of(NumberFactory::create('16'));
        $lbin3 = $ozin3->to(ImperialDensityUnit::PoundPerCubicInch);

        self::assertEqualsWithDelta(1.0, (float) $lbin3->getValue()->value(), 0.01);
    }

    public function testSlugPerCubicFootToPoundPerCubicFoot(): void
    {
        // 1 slug/ft³ ≈ 32.174 lb/ft³ (g in ft/s²)
        $slugft3 = SlugPerCubicFoot::of(NumberFactory::create('1'));
        $lbft3 = $slugft3->to(ImperialDensityUnit::PoundPerCubicFoot);

        self::assertEqualsWithDelta(32.174, (float) $lbft3->getValue()->value(), 0.1);
    }

    // ========== Cross-System Conversions ==========

    public function testKilogramPerCubicMeterToPoundPerCubicFoot(): void
    {
        // 16.018 kg/m³ = 1 lb/ft³
        $kgm3 = KilogramPerCubicMeter::of(NumberFactory::create('16.018463373960138'));
        $lbft3 = $kgm3->to(ImperialDensityUnit::PoundPerCubicFoot);

        self::assertEqualsWithDelta(1.0, (float) $lbft3->getValue()->value(), 0.001);
    }

    public function testPoundPerCubicFootToKilogramPerCubicMeter(): void
    {
        // 1 lb/ft³ = 16.018 kg/m³
        $lbft3 = PoundPerCubicFoot::of(NumberFactory::create('1'));
        $kgm3 = $lbft3->to(SIDensityUnit::KilogramPerCubicMeter);

        self::assertEqualsWithDelta(16.018, (float) $kgm3->getValue()->value(), 0.01);
    }

    public function testGramPerCubicCentimeterToPoundPerCubicInch(): void
    {
        // 1 g/cm³ ≈ 0.0361 lb/in³
        $gcm3 = GramPerCubicCentimeter::of(NumberFactory::create('1'));
        $lbin3 = $gcm3->to(ImperialDensityUnit::PoundPerCubicInch);

        self::assertEqualsWithDelta(0.0361, (float) $lbin3->getValue()->value(), 0.001);
    }

    public function testPoundPerCubicInchToGramPerCubicCentimeter(): void
    {
        // 1 lb/in³ ≈ 27.68 g/cm³
        $lbin3 = PoundPerCubicInch::of(NumberFactory::create('1'));
        $gcm3 = $lbin3->to(SIDensityUnit::GramPerCubicCentimeter);

        self::assertEqualsWithDelta(27.68, (float) $gcm3->getValue()->value(), 0.1);
    }

    public function testKilogramPerLiterToPoundPerGallon(): void
    {
        // 1 kg/L ≈ 8.345 lb/gal (US)
        $kgL = KilogramPerLiter::of(NumberFactory::create('1'));
        $lbgal = $kgL->to(ImperialDensityUnit::PoundPerGallon);

        self::assertEqualsWithDelta(8.345, (float) $lbgal->getValue()->value(), 0.05);
    }

    public function testPoundPerGallonToKilogramPerLiter(): void
    {
        // 1 lb/gal ≈ 0.1198 kg/L
        $lbgal = PoundPerGallon::of(NumberFactory::create('1'));
        $kgL = $lbgal->to(SIDensityUnit::KilogramPerLiter);

        self::assertEqualsWithDelta(0.1198, (float) $kgL->getValue()->value(), 0.01);
    }

    // ========== Real-World Scenario Tests ==========

    public function testWaterDensity(): void
    {
        // Water at 4°C has a density of 1000 kg/m³ = 1 g/cm³ = 1 kg/L
        $waterKgm3 = KilogramPerCubicMeter::of(NumberFactory::create('1000'));

        // Convert to g/cm³
        $gcm3 = $waterKgm3->to(SIDensityUnit::GramPerCubicCentimeter);
        self::assertEqualsWithDelta(1.0, (float) $gcm3->getValue()->value(), 0.001);

        // Convert to kg/L
        $kgL = $waterKgm3->to(SIDensityUnit::KilogramPerLiter);
        self::assertEqualsWithDelta(1.0, (float) $kgL->getValue()->value(), 0.001);

        // Convert to lb/ft³
        $lbft3 = $waterKgm3->to(ImperialDensityUnit::PoundPerCubicFoot);
        self::assertEqualsWithDelta(62.4, (float) $lbft3->getValue()->value(), 0.1);
    }

    public function testIronDensity(): void
    {
        // Iron density: ~7.874 g/cm³
        $iron = GramPerCubicCentimeter::of(NumberFactory::create('7.874'));

        // Convert to kg/m³
        $kgm3 = $iron->to(SIDensityUnit::KilogramPerCubicMeter);
        self::assertEqualsWithDelta(7874.0, (float) $kgm3->getValue()->value(), 1.0);

        // Convert to lb/in³
        $lbin3 = $iron->to(ImperialDensityUnit::PoundPerCubicInch);
        self::assertEqualsWithDelta(0.284, (float) $lbin3->getValue()->value(), 0.01);
    }

    public function testGasolineDensity(): void
    {
        // Gasoline density: ~0.75 kg/L (6.26 lb/gal)
        $gasoline = KilogramPerLiter::of(NumberFactory::create('0.75'));

        // Convert to lb/gal
        $lbgal = $gasoline->to(ImperialDensityUnit::PoundPerGallon);
        self::assertEqualsWithDelta(6.26, (float) $lbgal->getValue()->value(), 0.1);

        // Convert to g/cm³
        $gcm3 = $gasoline->to(SIDensityUnit::GramPerCubicCentimeter);
        self::assertEqualsWithDelta(0.75, (float) $gcm3->getValue()->value(), 0.001);
    }

    public function testAirDensity(): void
    {
        // Air density at sea level: ~1.225 kg/m³
        $air = KilogramPerCubicMeter::of(NumberFactory::create('1.225'));

        // Convert to g/L (same as kg/m³)
        $gL = $air->to(SIDensityUnit::GramPerLiter);
        self::assertEqualsWithDelta(1.225, (float) $gL->getValue()->value(), 0.001);

        // Convert to lb/ft³
        $lbft3 = $air->to(ImperialDensityUnit::PoundPerCubicFoot);
        self::assertEqualsWithDelta(0.0765, (float) $lbft3->getValue()->value(), 0.001);
    }

    public function testGoldDensity(): void
    {
        // Gold density: 19.32 g/cm³
        $gold = GramPerCubicCentimeter::of(NumberFactory::create('19.32'));

        // Convert to kg/m³
        $kgm3 = $gold->to(SIDensityUnit::KilogramPerCubicMeter);
        self::assertEqualsWithDelta(19320.0, (float) $kgm3->getValue()->value(), 1.0);

        // Convert to lb/in³
        $lbin3 = $gold->to(ImperialDensityUnit::PoundPerCubicInch);
        self::assertEqualsWithDelta(0.698, (float) $lbin3->getValue()->value(), 0.01);
    }

    public function testConcreteDensity(): void
    {
        // Concrete density: ~2400 kg/m³
        $concrete = KilogramPerCubicMeter::of(NumberFactory::create('2400'));

        // Convert to lb/ft³
        $lbft3 = $concrete->to(ImperialDensityUnit::PoundPerCubicFoot);
        self::assertEqualsWithDelta(150.0, (float) $lbft3->getValue()->value(), 1.0);

        // Convert to t/m³
        $tm3 = $concrete->to(SIDensityUnit::TonnePerCubicMeter);
        self::assertEqualsWithDelta(2.4, (float) $tm3->getValue()->value(), 0.01);
    }

    // ========== Mid-Level Class Tests ==========

    public function testSIDensityCreation(): void
    {
        $density = SIDensity::of(
            NumberFactory::create('1000'),
            SIDensityUnit::KilogramPerCubicMeter,
        );

        self::assertEquals('1000', $density->getValue()->value());
        self::assertSame(SIDensityUnit::KilogramPerCubicMeter, $density->getUnit());
    }

    public function testImperialDensityCreation(): void
    {
        $density = ImperialDensity::of(
            NumberFactory::create('62.4'),
            ImperialDensityUnit::PoundPerCubicFoot,
        );

        self::assertEquals('62.4', $density->getValue()->value());
        self::assertSame(ImperialDensityUnit::PoundPerCubicFoot, $density->getUnit());
    }

    public function testSIDensityConversion(): void
    {
        $density = SIDensity::of(
            NumberFactory::create('1000'),
            SIDensityUnit::KilogramPerCubicMeter,
        );

        $converted = $density->to(SIDensityUnit::GramPerCubicCentimeter);
        self::assertEqualsWithDelta(1.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericDensityWithSIUnit(): void
    {
        $density = Density::of(
            NumberFactory::create('1'),
            SIDensityUnit::GramPerCubicCentimeter,
        );

        self::assertEquals('1', $density->getValue()->value());
        self::assertSame(SIDensityUnit::GramPerCubicCentimeter, $density->getUnit());
    }

    public function testGenericDensityWithImperialUnit(): void
    {
        $density = Density::of(
            NumberFactory::create('100'),
            ImperialDensityUnit::PoundPerCubicFoot,
        );

        self::assertEquals('100', $density->getValue()->value());
        self::assertSame(ImperialDensityUnit::PoundPerCubicFoot, $density->getUnit());
    }

    public function testGenericDensityConversion(): void
    {
        $density = Density::of(
            NumberFactory::create('1'),
            SIDensityUnit::GramPerCubicCentimeter,
        );

        $converted = $density->to(ImperialDensityUnit::PoundPerCubicInch);
        self::assertEqualsWithDelta(0.0361, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Round-Trip Tests ==========

    public function testSIRoundTrip(): void
    {
        $original = KilogramPerCubicMeter::of(NumberFactory::create('1000'));
        $toGcm3 = $original->to(SIDensityUnit::GramPerCubicCentimeter);

        $gcm3Quantity = GramPerCubicCentimeter::of($toGcm3->getValue());
        $backToKgm3 = $gcm3Quantity->to(SIDensityUnit::KilogramPerCubicMeter);

        self::assertEqualsWithDelta(1000.0, (float) $backToKgm3->getValue()->value(), 0.1);
    }

    public function testImperialRoundTrip(): void
    {
        $original = PoundPerCubicFoot::of(NumberFactory::create('62.4'));
        $toLbin3 = $original->to(ImperialDensityUnit::PoundPerCubicInch);

        $lbin3Quantity = PoundPerCubicInch::of($toLbin3->getValue());
        $backToLbft3 = $lbin3Quantity->to(ImperialDensityUnit::PoundPerCubicFoot);

        self::assertEqualsWithDelta(62.4, (float) $backToLbft3->getValue()->value(), 0.1);
    }

    public function testCrossSystemRoundTrip(): void
    {
        $original = GramPerCubicCentimeter::of(NumberFactory::create('1'));
        $toLbft3 = $original->to(ImperialDensityUnit::PoundPerCubicFoot);

        $lbft3Quantity = PoundPerCubicFoot::of($toLbft3->getValue());
        $backToGcm3 = $lbft3Quantity->to(SIDensityUnit::GramPerCubicCentimeter);

        self::assertEqualsWithDelta(1.0, (float) $backToGcm3->getValue()->value(), 0.01);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $d1 = KilogramPerCubicMeter::of(NumberFactory::create('500'));
        $d2 = KilogramPerCubicMeter::of(NumberFactory::create('500'));

        $sum = $d1->add($d2);

        self::assertEqualsWithDelta(1000.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $d1 = GramPerCubicCentimeter::of(NumberFactory::create('2'));
        $d2 = GramPerCubicCentimeter::of(NumberFactory::create('1'));

        $diff = $d1->subtract($d2);

        self::assertEqualsWithDelta(1.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $density = KilogramPerCubicMeter::of(NumberFactory::create('500'));
        $result = $density->multiplyBy(NumberFactory::create('2'));

        self::assertEqualsWithDelta(1000.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $density = KilogramPerCubicMeter::of(NumberFactory::create('1000'));
        $result = $density->divideBy(NumberFactory::create('2'));

        self::assertEqualsWithDelta(500.0, (float) $result->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $d1 = KilogramPerCubicMeter::of(NumberFactory::create('1200'));
        $d2 = KilogramPerCubicMeter::of(NumberFactory::create('1000'));

        self::assertTrue($d1->isGreaterThan($d2));
        self::assertFalse($d1->isLessThan($d2));
        self::assertFalse($d1->equals($d2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 g/cm³ > 0.9 kg/L
        $gcm3 = GramPerCubicCentimeter::of(NumberFactory::create('1'));
        $kgL = KilogramPerLiter::of(NumberFactory::create('0.9'));

        self::assertTrue($gcm3->isGreaterThan($kgL));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 g/cm³ = 1000 kg/m³
        $gcm3 = GramPerCubicCentimeter::of(NumberFactory::create('1'));
        $kgm3 = KilogramPerCubicMeter::of(NumberFactory::create('1000'));

        self::assertTrue($gcm3->equals($kgm3));
    }

    public function testCrossSystemComparison(): void
    {
        // 1 g/cm³ converted to kg/m³ should equal 1000 kg/m³
        $gcm3ToKgm3 = GramPerCubicCentimeter::of(NumberFactory::create('1'))->to(SIDensityUnit::KilogramPerCubicMeter);
        $direct = KilogramPerCubicMeter::of(NumberFactory::create('1000'));

        self::assertEqualsWithDelta(
            (float) $gcm3ToKgm3->getValue()->value(),
            (float) $direct->getValue()->value(),
            0.001,
        );
    }
}
