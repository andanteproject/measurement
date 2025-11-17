<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\LuminousFlux;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\LuminousFlux\LuminousFlux;
use Andante\Measurement\Quantity\LuminousFlux\SI\Kilolumen;
use Andante\Measurement\Quantity\LuminousFlux\SI\Lumen;
use Andante\Measurement\Quantity\LuminousFlux\SI\Millilumen;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\LuminousFlux\LuminousFluxUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for luminous flux conversions.
 *
 * Luminous Flux [J¹] represents the total perceived power of light emitted
 * by a source, weighted for human eye sensitivity.
 * Base unit: lumen (lm), SI derived unit = cd⋅sr
 *
 * Common conversions:
 * - 1 klm = 1000 lm
 * - 1 lm = 1000 mlm
 */
final class LuminousFluxConversionTest extends TestCase
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

    public function testLumenToLumen(): void
    {
        $lumen = Lumen::of(NumberFactory::create('800'));
        $result = $lumen->to(LuminousFluxUnit::Lumen);

        self::assertEqualsWithDelta(800.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testLumenToKilolumen(): void
    {
        // 1000 lm = 1 klm
        $lumen = Lumen::of(NumberFactory::create('1000'));
        $kilolumen = $lumen->to(LuminousFluxUnit::Kilolumen);

        self::assertEqualsWithDelta(1.0, (float) $kilolumen->getValue()->value(), 0.001);
    }

    public function testKilolumenToLumen(): void
    {
        // 1 klm = 1000 lm
        $kilolumen = Kilolumen::of(NumberFactory::create('1'));
        $lumen = $kilolumen->to(LuminousFluxUnit::Lumen);

        self::assertEqualsWithDelta(1000.0, (float) $lumen->getValue()->value(), 0.001);
    }

    public function testLumenToMillilumen(): void
    {
        // 1 lm = 1000 mlm
        $lumen = Lumen::of(NumberFactory::create('1'));
        $millilumen = $lumen->to(LuminousFluxUnit::Millilumen);

        self::assertEqualsWithDelta(1000.0, (float) $millilumen->getValue()->value(), 0.001);
    }

    public function testMillilumenToLumen(): void
    {
        // 1000 mlm = 1 lm
        $millilumen = Millilumen::of(NumberFactory::create('1000'));
        $lumen = $millilumen->to(LuminousFluxUnit::Lumen);

        self::assertEqualsWithDelta(1.0, (float) $lumen->getValue()->value(), 0.001);
    }

    public function testKilolumenToMillilumen(): void
    {
        // 1 klm = 1,000,000 mlm
        $kilolumen = Kilolumen::of(NumberFactory::create('1'));
        $millilumen = $kilolumen->to(LuminousFluxUnit::Millilumen);

        self::assertEqualsWithDelta(1000000.0, (float) $millilumen->getValue()->value(), 0.001);
    }

    public function testMillilumenToKilolumen(): void
    {
        // 1,000,000 mlm = 1 klm
        $millilumen = Millilumen::of(NumberFactory::create('1000000'));
        $kilolumen = $millilumen->to(LuminousFluxUnit::Kilolumen);

        self::assertEqualsWithDelta(1.0, (float) $kilolumen->getValue()->value(), 0.001);
    }

    // ========== Real-World Scenario Tests ==========

    public function testStandardLEDBulb(): void
    {
        // Standard LED bulb: ~800 lm (60W incandescent equivalent)
        $bulb = Lumen::of(NumberFactory::create('800'));
        $kilolumen = $bulb->to(LuminousFluxUnit::Kilolumen);

        self::assertEqualsWithDelta(0.8, (float) $kilolumen->getValue()->value(), 0.001);
    }

    public function testBrightLEDBulb(): void
    {
        // Bright LED bulb: ~1600 lm (100W incandescent equivalent)
        $bulb = Lumen::of(NumberFactory::create('1600'));
        $kilolumen = $bulb->to(LuminousFluxUnit::Kilolumen);

        self::assertEqualsWithDelta(1.6, (float) $kilolumen->getValue()->value(), 0.001);
    }

    public function testProjector(): void
    {
        // Movie projector: ~3 klm
        $projector = Kilolumen::of(NumberFactory::create('3'));
        $lumen = $projector->to(LuminousFluxUnit::Lumen);

        self::assertEqualsWithDelta(3000.0, (float) $lumen->getValue()->value(), 0.001);
    }

    public function testSmallLED(): void
    {
        // Small indicator LED: ~50 mlm
        $led = Millilumen::of(NumberFactory::create('50'));
        $lumen = $led->to(LuminousFluxUnit::Lumen);

        self::assertEqualsWithDelta(0.05, (float) $lumen->getValue()->value(), 0.001);
    }

    public function testStadiumLighting(): void
    {
        // Stadium light fixture: ~100 klm
        $stadium = Kilolumen::of(NumberFactory::create('100'));
        $lumen = $stadium->to(LuminousFluxUnit::Lumen);

        self::assertEqualsWithDelta(100000.0, (float) $lumen->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericLuminousFluxWithLumen(): void
    {
        $flux = LuminousFlux::of(
            NumberFactory::create('800'),
            LuminousFluxUnit::Lumen,
        );

        self::assertEquals('800', $flux->getValue()->value());
        self::assertSame(LuminousFluxUnit::Lumen, $flux->getUnit());
    }

    public function testGenericLuminousFluxWithKilolumen(): void
    {
        $flux = LuminousFlux::of(
            NumberFactory::create('5'),
            LuminousFluxUnit::Kilolumen,
        );

        self::assertEquals('5', $flux->getValue()->value());
        self::assertSame(LuminousFluxUnit::Kilolumen, $flux->getUnit());
    }

    public function testGenericLuminousFluxWithMillilumen(): void
    {
        $flux = LuminousFlux::of(
            NumberFactory::create('250'),
            LuminousFluxUnit::Millilumen,
        );

        self::assertEquals('250', $flux->getValue()->value());
        self::assertSame(LuminousFluxUnit::Millilumen, $flux->getUnit());
    }

    public function testGenericLuminousFluxConversion(): void
    {
        $flux = LuminousFlux::of(
            NumberFactory::create('2.5'),
            LuminousFluxUnit::Kilolumen,
        );

        $converted = $flux->to(LuminousFluxUnit::Lumen);
        self::assertEqualsWithDelta(2500.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Round-Trip Tests ==========

    public function testLumenRoundTrip(): void
    {
        $original = Lumen::of(NumberFactory::create('500'));
        $toMillilumen = $original->to(LuminousFluxUnit::Millilumen);

        $mlmQuantity = Millilumen::of($toMillilumen->getValue());
        $backToLumen = $mlmQuantity->to(LuminousFluxUnit::Lumen);

        self::assertEqualsWithDelta(500.0, (float) $backToLumen->getValue()->value(), 0.001);
    }

    public function testKilolumenRoundTrip(): void
    {
        $original = Kilolumen::of(NumberFactory::create('10'));
        $toLumen = $original->to(LuminousFluxUnit::Lumen);

        $lmQuantity = Lumen::of($toLumen->getValue());
        $backToKilolumen = $lmQuantity->to(LuminousFluxUnit::Kilolumen);

        self::assertEqualsWithDelta(10.0, (float) $backToKilolumen->getValue()->value(), 0.001);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $f1 = Lumen::of(NumberFactory::create('400'));
        $f2 = Lumen::of(NumberFactory::create('400'));

        $sum = $f1->add($f2);

        self::assertEqualsWithDelta(800.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $f1 = Lumen::of(NumberFactory::create('1000'));
        $f2 = Lumen::of(NumberFactory::create('200'));

        $diff = $f1->subtract($f2);

        self::assertEqualsWithDelta(800.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $flux = Lumen::of(NumberFactory::create('400'));
        $result = $flux->multiplyBy(NumberFactory::create('2'));

        self::assertEqualsWithDelta(800.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $flux = Lumen::of(NumberFactory::create('1600'));
        $result = $flux->divideBy(NumberFactory::create('2'));

        self::assertEqualsWithDelta(800.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testAdditionAcrossUnits(): void
    {
        // 1 klm + 500 lm = 1.5 klm
        $kilolumen = Kilolumen::of(NumberFactory::create('1'));
        $lumen = Lumen::of(NumberFactory::create('500'));

        $sum = $kilolumen->add($lumen);

        // Result is in klm (first operand's unit)
        self::assertEqualsWithDelta(1.5, (float) $sum->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $f1 = Lumen::of(NumberFactory::create('1000'));
        $f2 = Lumen::of(NumberFactory::create('500'));

        self::assertTrue($f1->isGreaterThan($f2));
        self::assertFalse($f1->isLessThan($f2));
        self::assertFalse($f1->equals($f2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 klm > 500 lm
        $kilolumen = Kilolumen::of(NumberFactory::create('1'));
        $lumen = Lumen::of(NumberFactory::create('500'));

        self::assertTrue($kilolumen->isGreaterThan($lumen));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 klm = 1000 lm
        $kilolumen = Kilolumen::of(NumberFactory::create('1'));
        $lumen = Lumen::of(NumberFactory::create('1000'));

        self::assertTrue($kilolumen->equals($lumen));
    }

    public function testCrossUnitComparison(): void
    {
        // 1 klm converted to lm should equal 1000 lm
        $klmToLm = Kilolumen::of(NumberFactory::create('1'))->to(LuminousFluxUnit::Lumen);
        $direct = Lumen::of(NumberFactory::create('1000'));

        self::assertEqualsWithDelta(
            (float) $klmToLm->getValue()->value(),
            (float) $direct->getValue()->value(),
            0.001,
        );
    }

    // ========== Auto-Scale Tests ==========

    public function testAutoScaleFromMillilumen(): void
    {
        // 1000 mlm should auto-scale to 1 lm
        $millilumen = Millilumen::of(NumberFactory::create('1000'));
        $scaled = $millilumen->autoScale();

        self::assertSame(LuminousFluxUnit::Lumen, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromLumen(): void
    {
        // 1000 lm should auto-scale to 1 klm
        $lumen = Lumen::of(NumberFactory::create('1000'));
        $scaled = $lumen->autoScale();

        self::assertSame(LuminousFluxUnit::Kilolumen, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromKilolumen(): void
    {
        // 0.001 klm should auto-scale to 1 lm
        $kilolumen = Kilolumen::of(NumberFactory::create('0.001'));
        $scaled = $kilolumen->autoScale();

        self::assertSame(LuminousFluxUnit::Lumen, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }
}
