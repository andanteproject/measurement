<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\MagneticFlux;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\MagneticFlux\CGS\Maxwell;
use Andante\Measurement\Quantity\MagneticFlux\MagneticFlux;
use Andante\Measurement\Quantity\MagneticFlux\SI\Microweber;
use Andante\Measurement\Quantity\MagneticFlux\SI\Milliweber;
use Andante\Measurement\Quantity\MagneticFlux\SI\Weber;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\MagneticFlux\MagneticFluxUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for magnetic flux conversions.
 *
 * Magnetic Flux [L²M¹T⁻²I⁻¹] represents the total magnetic field passing through a surface.
 * Base unit: weber (Wb), defined as 1 V⋅s
 *
 * Common conversions:
 * - 1 Wb = 1000 mWb = 1,000,000 μWb
 * - 1 Wb = 100,000,000 Mx (maxwell, CGS unit)
 * - 1 Mx = 10⁻⁸ Wb
 */
final class MagneticFluxConversionTest extends TestCase
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

    public function testWeberToMilliweber(): void
    {
        // 1 Wb = 1000 mWb
        $weber = Weber::of(NumberFactory::create('1'));
        $milliweber = $weber->to(MagneticFluxUnit::Milliweber);

        self::assertEqualsWithDelta(1000.0, (float) $milliweber->getValue()->value(), 0.001);
    }

    public function testMilliweberToWeber(): void
    {
        // 1000 mWb = 1 Wb
        $milliweber = Milliweber::of(NumberFactory::create('1000'));
        $weber = $milliweber->to(MagneticFluxUnit::Weber);

        self::assertEqualsWithDelta(1.0, (float) $weber->getValue()->value(), 0.001);
    }

    public function testWeberToMicroweber(): void
    {
        // 1 Wb = 1,000,000 μWb
        $weber = Weber::of(NumberFactory::create('1'));
        $microweber = $weber->to(MagneticFluxUnit::Microweber);

        self::assertEqualsWithDelta(1000000.0, (float) $microweber->getValue()->value(), 0.001);
    }

    public function testMicroweberToWeber(): void
    {
        // 1,000,000 μWb = 1 Wb
        $microweber = Microweber::of(NumberFactory::create('1000000'));
        $weber = $microweber->to(MagneticFluxUnit::Weber);

        self::assertEqualsWithDelta(1.0, (float) $weber->getValue()->value(), 0.001);
    }

    public function testMilliweberToMicroweber(): void
    {
        // 1 mWb = 1000 μWb
        $milliweber = Milliweber::of(NumberFactory::create('1'));
        $microweber = $milliweber->to(MagneticFluxUnit::Microweber);

        self::assertEqualsWithDelta(1000.0, (float) $microweber->getValue()->value(), 0.001);
    }

    public function testMicroweberToMilliweber(): void
    {
        // 1000 μWb = 1 mWb
        $microweber = Microweber::of(NumberFactory::create('1000'));
        $milliweber = $microweber->to(MagneticFluxUnit::Milliweber);

        self::assertEqualsWithDelta(1.0, (float) $milliweber->getValue()->value(), 0.001);
    }

    // ========== CGS Unit Tests (Maxwell) ==========

    public function testWeberToMaxwell(): void
    {
        // 1 Wb = 100,000,000 Mx
        $weber = Weber::of(NumberFactory::create('1'));
        $maxwell = $weber->to(MagneticFluxUnit::Maxwell);

        self::assertEqualsWithDelta(100000000.0, (float) $maxwell->getValue()->value(), 0.001);
    }

    public function testMaxwellToWeber(): void
    {
        // 100,000,000 Mx = 1 Wb
        $maxwell = Maxwell::of(NumberFactory::create('100000000'));
        $weber = $maxwell->to(MagneticFluxUnit::Weber);

        self::assertEqualsWithDelta(1.0, (float) $weber->getValue()->value(), 0.001);
    }

    public function testMilliweberToMaxwell(): void
    {
        // 1 mWb = 100,000 Mx
        $milliweber = Milliweber::of(NumberFactory::create('1'));
        $maxwell = $milliweber->to(MagneticFluxUnit::Maxwell);

        self::assertEqualsWithDelta(100000.0, (float) $maxwell->getValue()->value(), 0.001);
    }

    public function testMaxwellToMilliweber(): void
    {
        // 100,000 Mx = 1 mWb
        $maxwell = Maxwell::of(NumberFactory::create('100000'));
        $milliweber = $maxwell->to(MagneticFluxUnit::Milliweber);

        self::assertEqualsWithDelta(1.0, (float) $milliweber->getValue()->value(), 0.001);
    }

    public function testMicroweberToMaxwell(): void
    {
        // 1 μWb = 100 Mx
        $microweber = Microweber::of(NumberFactory::create('1'));
        $maxwell = $microweber->to(MagneticFluxUnit::Maxwell);

        self::assertEqualsWithDelta(100.0, (float) $maxwell->getValue()->value(), 0.001);
    }

    public function testMaxwellToMicroweber(): void
    {
        // 100 Mx = 1 μWb
        $maxwell = Maxwell::of(NumberFactory::create('100'));
        $microweber = $maxwell->to(MagneticFluxUnit::Microweber);

        self::assertEqualsWithDelta(1.0, (float) $microweber->getValue()->value(), 0.001);
    }

    // ========== Real-World Scenario Tests ==========

    public function testMRIMagnet(): void
    {
        // MRI magnet produces ~1.5 T field over ~0.5 m² area = ~0.75 Wb
        $flux = Weber::of(NumberFactory::create('0.75'));
        $milliweber = $flux->to(MagneticFluxUnit::Milliweber);

        self::assertEqualsWithDelta(750.0, (float) $milliweber->getValue()->value(), 0.001);
    }

    public function testTransformerCore(): void
    {
        // Typical transformer core: 50 mWb
        $flux = Milliweber::of(NumberFactory::create('50'));
        $weber = $flux->to(MagneticFluxUnit::Weber);

        self::assertEqualsWithDelta(0.05, (float) $weber->getValue()->value(), 0.001);
    }

    public function testSmallInductor(): void
    {
        // Small inductor flux: 100 μWb
        $flux = Microweber::of(NumberFactory::create('100'));
        $maxwell = $flux->to(MagneticFluxUnit::Maxwell);

        self::assertEqualsWithDelta(10000.0, (float) $maxwell->getValue()->value(), 0.001);
    }

    public function testEarthMagneticFlux(): void
    {
        // Earth's magnetic flux through 1 m² at equator: ~30 μWb
        $flux = Microweber::of(NumberFactory::create('30'));
        $milliweber = $flux->to(MagneticFluxUnit::Milliweber);

        self::assertEqualsWithDelta(0.03, (float) $milliweber->getValue()->value(), 0.001);
    }

    public function testHardDriveReadHead(): void
    {
        // Flux in hard drive read head: ~10,000 Mx
        $flux = Maxwell::of(NumberFactory::create('10000'));
        $microweber = $flux->to(MagneticFluxUnit::Microweber);

        self::assertEqualsWithDelta(100.0, (float) $microweber->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericMagneticFluxWithWeber(): void
    {
        $flux = MagneticFlux::of(
            NumberFactory::create('1.5'),
            MagneticFluxUnit::Weber,
        );

        self::assertEquals('1.5', $flux->getValue()->value());
        self::assertSame(MagneticFluxUnit::Weber, $flux->getUnit());
    }

    public function testGenericMagneticFluxWithMilliweber(): void
    {
        $flux = MagneticFlux::of(
            NumberFactory::create('100'),
            MagneticFluxUnit::Milliweber,
        );

        self::assertEquals('100', $flux->getValue()->value());
        self::assertSame(MagneticFluxUnit::Milliweber, $flux->getUnit());
    }

    public function testGenericMagneticFluxWithMicroweber(): void
    {
        $flux = MagneticFlux::of(
            NumberFactory::create('500'),
            MagneticFluxUnit::Microweber,
        );

        self::assertEquals('500', $flux->getValue()->value());
        self::assertSame(MagneticFluxUnit::Microweber, $flux->getUnit());
    }

    public function testGenericMagneticFluxWithMaxwell(): void
    {
        $flux = MagneticFlux::of(
            NumberFactory::create('1000'),
            MagneticFluxUnit::Maxwell,
        );

        self::assertEquals('1000', $flux->getValue()->value());
        self::assertSame(MagneticFluxUnit::Maxwell, $flux->getUnit());
    }

    public function testGenericMagneticFluxConversion(): void
    {
        $flux = MagneticFlux::of(
            NumberFactory::create('2.5'),
            MagneticFluxUnit::Milliweber,
        );

        $converted = $flux->to(MagneticFluxUnit::Microweber);
        self::assertEqualsWithDelta(2500.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Round-Trip Tests ==========

    public function testWeberRoundTrip(): void
    {
        $original = Weber::of(NumberFactory::create('0.5'));
        $toMilliweber = $original->to(MagneticFluxUnit::Milliweber);

        $mWbQuantity = Milliweber::of($toMilliweber->getValue());
        $backToWeber = $mWbQuantity->to(MagneticFluxUnit::Weber);

        self::assertEqualsWithDelta(0.5, (float) $backToWeber->getValue()->value(), 0.001);
    }

    public function testMilliweberRoundTrip(): void
    {
        $original = Milliweber::of(NumberFactory::create('100'));
        $toMicroweber = $original->to(MagneticFluxUnit::Microweber);

        $uWbQuantity = Microweber::of($toMicroweber->getValue());
        $backToMilliweber = $uWbQuantity->to(MagneticFluxUnit::Milliweber);

        self::assertEqualsWithDelta(100.0, (float) $backToMilliweber->getValue()->value(), 0.001);
    }

    public function testMaxwellRoundTrip(): void
    {
        $original = Maxwell::of(NumberFactory::create('50000'));
        $toMicroweber = $original->to(MagneticFluxUnit::Microweber);

        $uWbQuantity = Microweber::of($toMicroweber->getValue());
        $backToMaxwell = $uWbQuantity->to(MagneticFluxUnit::Maxwell);

        self::assertEqualsWithDelta(50000.0, (float) $backToMaxwell->getValue()->value(), 0.001);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $f1 = Milliweber::of(NumberFactory::create('100'));
        $f2 = Milliweber::of(NumberFactory::create('200'));

        $sum = $f1->add($f2);

        self::assertEqualsWithDelta(300.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $f1 = Microweber::of(NumberFactory::create('500'));
        $f2 = Microweber::of(NumberFactory::create('200'));

        $diff = $f1->subtract($f2);

        self::assertEqualsWithDelta(300.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $flux = Milliweber::of(NumberFactory::create('100'));
        $result = $flux->multiplyBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(300.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $flux = Microweber::of(NumberFactory::create('120'));
        $result = $flux->divideBy(NumberFactory::create('4'));

        self::assertEqualsWithDelta(30.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testAdditionAcrossUnits(): void
    {
        // 1 mWb + 500 μWb = 1.5 mWb
        $milliweber = Milliweber::of(NumberFactory::create('1'));
        $microweber = Microweber::of(NumberFactory::create('500'));

        $sum = $milliweber->add($microweber);

        // Result is in mWb (first operand's unit)
        self::assertEqualsWithDelta(1.5, (float) $sum->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $f1 = Milliweber::of(NumberFactory::create('100'));
        $f2 = Milliweber::of(NumberFactory::create('50'));

        self::assertTrue($f1->isGreaterThan($f2));
        self::assertFalse($f1->isLessThan($f2));
        self::assertFalse($f1->equals($f2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 mWb > 500 μWb
        $milliweber = Milliweber::of(NumberFactory::create('1'));
        $microweber = Microweber::of(NumberFactory::create('500'));

        self::assertTrue($milliweber->isGreaterThan($microweber));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 mWb = 1000 μWb
        $milliweber = Milliweber::of(NumberFactory::create('1'));
        $microweber = Microweber::of(NumberFactory::create('1000'));

        self::assertTrue($milliweber->equals($microweber));
    }

    public function testCrossUnitComparison(): void
    {
        // 1 mWb converted to μWb should equal 1000 μWb
        $mWbToUWb = Milliweber::of(NumberFactory::create('1'))->to(MagneticFluxUnit::Microweber);
        $direct = Microweber::of(NumberFactory::create('1000'));

        self::assertEqualsWithDelta(
            (float) $mWbToUWb->getValue()->value(),
            (float) $direct->getValue()->value(),
            0.001,
        );
    }

    // ========== Auto-Scale Tests ==========

    public function testAutoScaleFromMicroweber(): void
    {
        // 1000 μWb should auto-scale to 1 mWb
        $microweber = Microweber::of(NumberFactory::create('1000'));
        $scaled = $microweber->autoScale();

        self::assertSame(MagneticFluxUnit::Milliweber, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromMilliweber(): void
    {
        // 1000 mWb should auto-scale to 1 Wb
        $milliweber = Milliweber::of(NumberFactory::create('1000'));
        $scaled = $milliweber->autoScale();

        self::assertSame(MagneticFluxUnit::Weber, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromWeber(): void
    {
        // 0.001 Wb should auto-scale to 1 mWb
        $weber = Weber::of(NumberFactory::create('0.001'));
        $scaled = $weber->autoScale();

        self::assertSame(MagneticFluxUnit::Milliweber, $scaled->getUnit());
        self::assertEqualsWithDelta(1.0, (float) $scaled->getValue()->value(), 0.001);
    }

    public function testAutoScaleFromMaxwell(): void
    {
        // Maxwell is CGS, auto-scale stays within same system
        // 100,000 Mx remains as Maxwell (no other CGS units defined)
        $maxwell = Maxwell::of(NumberFactory::create('100000'));
        $scaled = $maxwell->autoScale();

        // Maxwell stays as Maxwell since it's the only CGS unit
        self::assertSame(MagneticFluxUnit::Maxwell, $scaled->getUnit());
        self::assertEqualsWithDelta(100000.0, (float) $scaled->getValue()->value(), 0.001);
    }
}
