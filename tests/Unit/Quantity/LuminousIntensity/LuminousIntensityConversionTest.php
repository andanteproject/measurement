<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\LuminousIntensity;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\LuminousIntensity\LuminousIntensity;
use Andante\Measurement\Quantity\LuminousIntensity\SI\Candela;
use Andante\Measurement\Quantity\LuminousIntensity\SI\Kilocandela;
use Andante\Measurement\Quantity\LuminousIntensity\SI\Microcandela;
use Andante\Measurement\Quantity\LuminousIntensity\SI\Millicandela;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\LuminousIntensity\LuminousIntensityUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for luminous intensity conversions.
 *
 * Luminous Intensity [J¹] represents the luminous power emitted by a light
 * source in a particular direction per unit solid angle.
 * Base unit: candela (cd), one of the seven SI base units.
 *
 * Common conversions:
 * - 1 kcd = 1000 cd
 * - 1 cd = 1000 mcd
 * - 1 mcd = 1000 μcd
 */
final class LuminousIntensityConversionTest extends TestCase
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

    public function testCandelaToCandela(): void
    {
        $candela = Candela::of(NumberFactory::create('100'));
        $result = $candela->to(LuminousIntensityUnit::Candela);

        self::assertEqualsWithDelta(100.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testCandelaToKilocandela(): void
    {
        // 1000 cd = 1 kcd
        $candela = Candela::of(NumberFactory::create('1000'));
        $kilocandela = $candela->to(LuminousIntensityUnit::Kilocandela);

        self::assertEqualsWithDelta(1.0, (float) $kilocandela->getValue()->value(), 0.001);
    }

    public function testKilocandelaToCandela(): void
    {
        // 1 kcd = 1000 cd
        $kilocandela = Kilocandela::of(NumberFactory::create('1'));
        $candela = $kilocandela->to(LuminousIntensityUnit::Candela);

        self::assertEqualsWithDelta(1000.0, (float) $candela->getValue()->value(), 0.001);
    }

    public function testCandelaToMillicandela(): void
    {
        // 1 cd = 1000 mcd
        $candela = Candela::of(NumberFactory::create('1'));
        $millicandela = $candela->to(LuminousIntensityUnit::Millicandela);

        self::assertEqualsWithDelta(1000.0, (float) $millicandela->getValue()->value(), 0.001);
    }

    public function testMillicandelaToCandela(): void
    {
        // 1000 mcd = 1 cd
        $millicandela = Millicandela::of(NumberFactory::create('1000'));
        $candela = $millicandela->to(LuminousIntensityUnit::Candela);

        self::assertEqualsWithDelta(1.0, (float) $candela->getValue()->value(), 0.001);
    }

    public function testCandelaToMicrocandela(): void
    {
        // 1 cd = 1,000,000 μcd
        $candela = Candela::of(NumberFactory::create('1'));
        $microcandela = $candela->to(LuminousIntensityUnit::Microcandela);

        self::assertEqualsWithDelta(1000000.0, (float) $microcandela->getValue()->value(), 0.001);
    }

    public function testMicrocandelaToCandela(): void
    {
        // 1,000,000 μcd = 1 cd
        $microcandela = Microcandela::of(NumberFactory::create('1000000'));
        $candela = $microcandela->to(LuminousIntensityUnit::Candela);

        self::assertEqualsWithDelta(1.0, (float) $candela->getValue()->value(), 0.001);
    }

    public function testMillicandelaToMicrocandela(): void
    {
        // 1 mcd = 1000 μcd
        $millicandela = Millicandela::of(NumberFactory::create('1'));
        $microcandela = $millicandela->to(LuminousIntensityUnit::Microcandela);

        self::assertEqualsWithDelta(1000.0, (float) $microcandela->getValue()->value(), 0.001);
    }

    public function testMicrocandelaToMillicandela(): void
    {
        // 1000 μcd = 1 mcd
        $microcandela = Microcandela::of(NumberFactory::create('1000'));
        $millicandela = $microcandela->to(LuminousIntensityUnit::Millicandela);

        self::assertEqualsWithDelta(1.0, (float) $millicandela->getValue()->value(), 0.001);
    }

    public function testKilocandelaToMillicandela(): void
    {
        // 1 kcd = 1,000,000 mcd
        $kilocandela = Kilocandela::of(NumberFactory::create('1'));
        $millicandela = $kilocandela->to(LuminousIntensityUnit::Millicandela);

        self::assertEqualsWithDelta(1000000.0, (float) $millicandela->getValue()->value(), 0.001);
    }

    // ========== Real-World Scenario Tests ==========

    public function testTypicalLED(): void
    {
        // Typical bright LED: 20 mcd
        $led = Millicandela::of(NumberFactory::create('20'));
        $candela = $led->to(LuminousIntensityUnit::Candela);

        self::assertEqualsWithDelta(0.02, (float) $candela->getValue()->value(), 0.001);
    }

    public function testHighPowerLED(): void
    {
        // High-power LED: 5000 mcd = 5 cd
        $highPowerLed = Millicandela::of(NumberFactory::create('5000'));
        $candela = $highPowerLed->to(LuminousIntensityUnit::Candela);

        self::assertEqualsWithDelta(5.0, (float) $candela->getValue()->value(), 0.001);
    }

    public function testCandleFlame(): void
    {
        // Typical candle flame: ~1 cd
        $candle = Candela::of(NumberFactory::create('1'));
        $millicandela = $candle->to(LuminousIntensityUnit::Millicandela);

        self::assertEqualsWithDelta(1000.0, (float) $millicandela->getValue()->value(), 0.001);
    }

    public function testSearchlight(): void
    {
        // Large searchlight: ~800 kcd
        $searchlight = Kilocandela::of(NumberFactory::create('800'));
        $candela = $searchlight->to(LuminousIntensityUnit::Candela);

        self::assertEqualsWithDelta(800000.0, (float) $candela->getValue()->value(), 0.001);
    }

    public function testDimIndicatorLED(): void
    {
        // Dim indicator LED: 500 μcd
        $dimLed = Microcandela::of(NumberFactory::create('500'));
        $millicandela = $dimLed->to(LuminousIntensityUnit::Millicandela);

        self::assertEqualsWithDelta(0.5, (float) $millicandela->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericLuminousIntensityWithCandela(): void
    {
        $intensity = LuminousIntensity::of(
            NumberFactory::create('100'),
            LuminousIntensityUnit::Candela,
        );

        self::assertEquals('100', $intensity->getValue()->value());
        self::assertSame(LuminousIntensityUnit::Candela, $intensity->getUnit());
    }

    public function testGenericLuminousIntensityWithKilocandela(): void
    {
        $intensity = LuminousIntensity::of(
            NumberFactory::create('5'),
            LuminousIntensityUnit::Kilocandela,
        );

        self::assertEquals('5', $intensity->getValue()->value());
        self::assertSame(LuminousIntensityUnit::Kilocandela, $intensity->getUnit());
    }

    public function testGenericLuminousIntensityWithMillicandela(): void
    {
        $intensity = LuminousIntensity::of(
            NumberFactory::create('250'),
            LuminousIntensityUnit::Millicandela,
        );

        self::assertEquals('250', $intensity->getValue()->value());
        self::assertSame(LuminousIntensityUnit::Millicandela, $intensity->getUnit());
    }

    public function testGenericLuminousIntensityConversion(): void
    {
        $intensity = LuminousIntensity::of(
            NumberFactory::create('2.5'),
            LuminousIntensityUnit::Candela,
        );

        $converted = $intensity->to(LuminousIntensityUnit::Millicandela);
        self::assertEqualsWithDelta(2500.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Round-Trip Tests ==========

    public function testCandelaRoundTrip(): void
    {
        $original = Candela::of(NumberFactory::create('50'));
        $toMillicandela = $original->to(LuminousIntensityUnit::Millicandela);

        $mcdQuantity = Millicandela::of($toMillicandela->getValue());
        $backToCandela = $mcdQuantity->to(LuminousIntensityUnit::Candela);

        self::assertEqualsWithDelta(50.0, (float) $backToCandela->getValue()->value(), 0.001);
    }

    public function testKilocandelaRoundTrip(): void
    {
        $original = Kilocandela::of(NumberFactory::create('10'));
        $toCandela = $original->to(LuminousIntensityUnit::Candela);

        $cdQuantity = Candela::of($toCandela->getValue());
        $backToKilocandela = $cdQuantity->to(LuminousIntensityUnit::Kilocandela);

        self::assertEqualsWithDelta(10.0, (float) $backToKilocandela->getValue()->value(), 0.001);
    }

    public function testMillicandelaRoundTrip(): void
    {
        $original = Millicandela::of(NumberFactory::create('100'));
        $toMicrocandela = $original->to(LuminousIntensityUnit::Microcandela);

        $ucdQuantity = Microcandela::of($toMicrocandela->getValue());
        $backToMillicandela = $ucdQuantity->to(LuminousIntensityUnit::Millicandela);

        self::assertEqualsWithDelta(100.0, (float) $backToMillicandela->getValue()->value(), 0.001);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $i1 = Candela::of(NumberFactory::create('100'));
        $i2 = Candela::of(NumberFactory::create('200'));

        $sum = $i1->add($i2);

        self::assertEqualsWithDelta(300.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $i1 = Millicandela::of(NumberFactory::create('500'));
        $i2 = Millicandela::of(NumberFactory::create('200'));

        $diff = $i1->subtract($i2);

        self::assertEqualsWithDelta(300.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $intensity = Candela::of(NumberFactory::create('100'));
        $result = $intensity->multiplyBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(300.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $intensity = Millicandela::of(NumberFactory::create('120'));
        $result = $intensity->divideBy(NumberFactory::create('4'));

        self::assertEqualsWithDelta(30.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testAdditionAcrossUnits(): void
    {
        // 1 cd + 500 mcd = 1.5 cd
        $candela = Candela::of(NumberFactory::create('1'));
        $millicandela = Millicandela::of(NumberFactory::create('500'));

        $sum = $candela->add($millicandela);

        // Result is in cd (first operand's unit)
        self::assertEqualsWithDelta(1.5, (float) $sum->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $i1 = Candela::of(NumberFactory::create('100'));
        $i2 = Candela::of(NumberFactory::create('50'));

        self::assertTrue($i1->isGreaterThan($i2));
        self::assertFalse($i1->isLessThan($i2));
        self::assertFalse($i1->equals($i2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 cd > 500 mcd
        $candela = Candela::of(NumberFactory::create('1'));
        $millicandela = Millicandela::of(NumberFactory::create('500'));

        self::assertTrue($candela->isGreaterThan($millicandela));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 cd = 1000 mcd
        $candela = Candela::of(NumberFactory::create('1'));
        $millicandela = Millicandela::of(NumberFactory::create('1000'));

        self::assertTrue($candela->equals($millicandela));
    }

    public function testCrossUnitComparison(): void
    {
        // 1 cd converted to mcd should equal 1000 mcd
        $cdToMcd = Candela::of(NumberFactory::create('1'))->to(LuminousIntensityUnit::Millicandela);
        $direct = Millicandela::of(NumberFactory::create('1000'));

        self::assertEqualsWithDelta(
            (float) $cdToMcd->getValue()->value(),
            (float) $direct->getValue()->value(),
            0.001,
        );
    }

    // ========== Auto-Scale Tests ==========

    public function testAutoScaleFromMicrocandela(): void
    {
        // 1000 μcd should auto-scale to 1 mcd
        $microcandela = Microcandela::of(NumberFactory::create('1000'));
        $scaled = $microcandela->autoScale();

        self::assertSame(LuminousIntensityUnit::Millicandela, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromMillicandela(): void
    {
        // 1000 mcd should auto-scale to 1 cd
        $millicandela = Millicandela::of(NumberFactory::create('1000'));
        $scaled = $millicandela->autoScale();

        self::assertSame(LuminousIntensityUnit::Candela, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromCandela(): void
    {
        // 1000 cd should auto-scale to 1 kcd
        $candela = Candela::of(NumberFactory::create('1000'));
        $scaled = $candela->autoScale();

        self::assertSame(LuminousIntensityUnit::Kilocandela, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromKilocandela(): void
    {
        // 0.001 kcd should auto-scale to 1 cd
        $kilocandela = Kilocandela::of(NumberFactory::create('0.001'));
        $scaled = $kilocandela->autoScale();

        self::assertSame(LuminousIntensityUnit::Candela, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }
}
