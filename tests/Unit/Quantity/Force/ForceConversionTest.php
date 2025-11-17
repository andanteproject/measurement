<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Force;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Force\Force;
use Andante\Measurement\Quantity\Force\Imperial\Kip;
use Andante\Measurement\Quantity\Force\Imperial\OunceForce;
use Andante\Measurement\Quantity\Force\Imperial\Poundal;
use Andante\Measurement\Quantity\Force\Imperial\PoundForce;
use Andante\Measurement\Quantity\Force\ImperialForce;
use Andante\Measurement\Quantity\Force\SI\Dyne;
use Andante\Measurement\Quantity\Force\SI\Kilonewton;
use Andante\Measurement\Quantity\Force\SI\Meganewton;
use Andante\Measurement\Quantity\Force\SI\Micronewton;
use Andante\Measurement\Quantity\Force\SI\Millinewton;
use Andante\Measurement\Quantity\Force\SI\Newton;
use Andante\Measurement\Quantity\Force\SIForce;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Force\ImperialForceUnit;
use Andante\Measurement\Unit\Force\SIForceUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for force conversions.
 *
 * Force [L¹M¹T⁻²] represents the interaction that changes motion.
 * Base unit: newton (N), defined as kg⋅m/s²
 *
 * Common conversions:
 * - 1 kN = 1000 N
 * - 1 MN = 1,000,000 N
 * - 1 mN = 0.001 N
 * - 1 μN = 0.000001 N
 * - 1 dyn = 10⁻⁵ N (CGS unit)
 * - 1 lbf ≈ 4.448 N
 * - 1 ozf ≈ 0.278 N (1/16 lbf)
 * - 1 kip = 1000 lbf ≈ 4448 N
 * - 1 pdl ≈ 0.138 N (FPS unit)
 */
final class ForceConversionTest extends TestCase
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

    public function testNewtonToKilonewton(): void
    {
        // 1000 N = 1 kN
        $newton = Newton::of(NumberFactory::create('1000'));
        $kN = $newton->to(SIForceUnit::Kilonewton);

        self::assertEqualsWithDelta(1.0, (float) $kN->getValue()->value(), 0.001);
    }

    public function testKilonewtonToNewton(): void
    {
        // 1 kN = 1000 N
        $kN = Kilonewton::of(NumberFactory::create('1'));
        $newton = $kN->to(SIForceUnit::Newton);

        self::assertEqualsWithDelta(1000.0, (float) $newton->getValue()->value(), 0.001);
    }

    public function testNewtonToMeganewton(): void
    {
        // 1,000,000 N = 1 MN
        $newton = Newton::of(NumberFactory::create('1000000'));
        $MN = $newton->to(SIForceUnit::Meganewton);

        self::assertEqualsWithDelta(1.0, (float) $MN->getValue()->value(), 0.001);
    }

    public function testMeganewtonToNewton(): void
    {
        // 1 MN = 1,000,000 N
        $MN = Meganewton::of(NumberFactory::create('1'));
        $newton = $MN->to(SIForceUnit::Newton);

        self::assertEqualsWithDelta(1000000.0, (float) $newton->getValue()->value(), 0.001);
    }

    public function testNewtonToMillinewton(): void
    {
        // 1 N = 1000 mN
        $newton = Newton::of(NumberFactory::create('1'));
        $mN = $newton->to(SIForceUnit::Millinewton);

        self::assertEqualsWithDelta(1000.0, (float) $mN->getValue()->value(), 0.001);
    }

    public function testMillinewtonToNewton(): void
    {
        // 1000 mN = 1 N
        $mN = Millinewton::of(NumberFactory::create('1000'));
        $newton = $mN->to(SIForceUnit::Newton);

        self::assertEqualsWithDelta(1.0, (float) $newton->getValue()->value(), 0.001);
    }

    public function testNewtonToMicronewton(): void
    {
        // 1 N = 1,000,000 μN
        $newton = Newton::of(NumberFactory::create('1'));
        $uN = $newton->to(SIForceUnit::Micronewton);

        self::assertEqualsWithDelta(1000000.0, (float) $uN->getValue()->value(), 0.001);
    }

    public function testMicronewtonToNewton(): void
    {
        // 1,000,000 μN = 1 N
        $uN = Micronewton::of(NumberFactory::create('1000000'));
        $newton = $uN->to(SIForceUnit::Newton);

        self::assertEqualsWithDelta(1.0, (float) $newton->getValue()->value(), 0.001);
    }

    public function testNewtonToDyne(): void
    {
        // 1 N = 100,000 dyn
        $newton = Newton::of(NumberFactory::create('1'));
        $dyn = $newton->to(SIForceUnit::Dyne);

        self::assertEqualsWithDelta(100000.0, (float) $dyn->getValue()->value(), 0.001);
    }

    public function testDyneToNewton(): void
    {
        // 100,000 dyn = 1 N
        $dyn = Dyne::of(NumberFactory::create('100000'));
        $newton = $dyn->to(SIForceUnit::Newton);

        self::assertEqualsWithDelta(1.0, (float) $newton->getValue()->value(), 0.001);
    }

    // ========== Imperial Unit Tests ==========

    public function testPoundForceToPoundal(): void
    {
        // 1 lbf ≈ 32.174 pdl
        $lbf = PoundForce::of(NumberFactory::create('1'));
        $pdl = $lbf->to(ImperialForceUnit::Poundal);

        self::assertEqualsWithDelta(32.174, (float) $pdl->getValue()->value(), 0.01);
    }

    public function testPoundalToPoundForce(): void
    {
        // 1 pdl ≈ 0.031081 lbf
        $pdl = Poundal::of(NumberFactory::create('1'));
        $lbf = $pdl->to(ImperialForceUnit::PoundForce);

        self::assertEqualsWithDelta(0.031081, (float) $lbf->getValue()->value(), 0.001);
    }

    public function testPoundForceToOunceForce(): void
    {
        // 1 lbf = 16 ozf
        $lbf = PoundForce::of(NumberFactory::create('1'));
        $ozf = $lbf->to(ImperialForceUnit::OunceForce);

        self::assertEqualsWithDelta(16.0, (float) $ozf->getValue()->value(), 0.001);
    }

    public function testOunceForceToPoundForce(): void
    {
        // 16 ozf = 1 lbf
        $ozf = OunceForce::of(NumberFactory::create('16'));
        $lbf = $ozf->to(ImperialForceUnit::PoundForce);

        self::assertEqualsWithDelta(1.0, (float) $lbf->getValue()->value(), 0.001);
    }

    public function testKipToPoundForce(): void
    {
        // 1 kip = 1000 lbf
        $kip = Kip::of(NumberFactory::create('1'));
        $lbf = $kip->to(ImperialForceUnit::PoundForce);

        self::assertEqualsWithDelta(1000.0, (float) $lbf->getValue()->value(), 0.001);
    }

    public function testPoundForceToKip(): void
    {
        // 1000 lbf = 1 kip
        $lbf = PoundForce::of(NumberFactory::create('1000'));
        $kip = $lbf->to(ImperialForceUnit::Kip);

        self::assertEqualsWithDelta(1.0, (float) $kip->getValue()->value(), 0.001);
    }

    // ========== Cross-System Conversions ==========

    public function testNewtonToPoundForce(): void
    {
        // 1 N ≈ 0.2248 lbf
        $newton = Newton::of(NumberFactory::create('1'));
        $lbf = $newton->to(ImperialForceUnit::PoundForce);

        self::assertEqualsWithDelta(0.2248, (float) $lbf->getValue()->value(), 0.001);
    }

    public function testPoundForceToNewton(): void
    {
        // 1 lbf = 4.448222 N
        $lbf = PoundForce::of(NumberFactory::create('1'));
        $newton = $lbf->to(SIForceUnit::Newton);

        self::assertEqualsWithDelta(4.4482216152605, (float) $newton->getValue()->value(), 0.0001);
    }

    public function testKilonewtonToPoundForce(): void
    {
        // 1 kN ≈ 224.8 lbf
        $kN = Kilonewton::of(NumberFactory::create('1'));
        $lbf = $kN->to(ImperialForceUnit::PoundForce);

        self::assertEqualsWithDelta(224.8, (float) $lbf->getValue()->value(), 0.5);
    }

    public function testKipToKilonewton(): void
    {
        // 1 kip ≈ 4.448 kN
        $kip = Kip::of(NumberFactory::create('1'));
        $kN = $kip->to(SIForceUnit::Kilonewton);

        self::assertEqualsWithDelta(4.448, (float) $kN->getValue()->value(), 0.001);
    }

    public function testDyneToPoundForce(): void
    {
        // 1 dyn = 10^-5 N ≈ 2.248×10^-6 lbf
        $dyn = Dyne::of(NumberFactory::create('1000000')); // 10 N
        $lbf = $dyn->to(ImperialForceUnit::PoundForce);

        self::assertEqualsWithDelta(2.248, (float) $lbf->getValue()->value(), 0.01);
    }

    public function testPoundalToNewton(): void
    {
        // 1 pdl = 0.138255 N
        $pdl = Poundal::of(NumberFactory::create('1'));
        $newton = $pdl->to(SIForceUnit::Newton);

        self::assertEqualsWithDelta(0.138255, (float) $newton->getValue()->value(), 0.001);
    }

    // ========== Real-World Scenario Tests ==========

    public function testHumanBodyWeight(): void
    {
        // A 70 kg person weighs about 686 N or 154 lbf
        $weight = Newton::of(NumberFactory::create('686.466'));
        $lbf = $weight->to(ImperialForceUnit::PoundForce);

        self::assertEqualsWithDelta(154.3, (float) $lbf->getValue()->value(), 0.5);
    }

    public function testCarEngineThrustForce(): void
    {
        // A typical car engine produces about 2000 N of thrust
        $thrust = Newton::of(NumberFactory::create('2000'));

        // Convert to kN for engineering discussions
        $kN = $thrust->to(SIForceUnit::Kilonewton);
        self::assertEqualsWithDelta(2.0, (float) $kN->getValue()->value(), 0.001);

        // Convert to lbf for US specifications
        $lbf = $thrust->to(ImperialForceUnit::PoundForce);
        self::assertEqualsWithDelta(449.6, (float) $lbf->getValue()->value(), 1.0);
    }

    public function testStructuralEngineering(): void
    {
        // Bridge pillar support: 500 kips
        $support = Kip::of(NumberFactory::create('500'));

        // Convert to MN for SI calculations
        $MN = $support->to(SIForceUnit::Meganewton);
        self::assertEqualsWithDelta(2.224, (float) $MN->getValue()->value(), 0.01);
    }

    public function testSpacecraftThruster(): void
    {
        // Ion thruster: 100 mN
        $thrust = Millinewton::of(NumberFactory::create('100'));
        $newton = $thrust->to(SIForceUnit::Newton);

        self::assertEqualsWithDelta(0.1, (float) $newton->getValue()->value(), 0.001);
    }

    public function testMicroelectromechanicalForce(): void
    {
        // MEMS device force: 50 μN
        $force = Micronewton::of(NumberFactory::create('50'));
        $dyn = $force->to(SIForceUnit::Dyne);

        // 50 μN = 0.00005 N = 5 dyn
        self::assertEqualsWithDelta(5.0, (float) $dyn->getValue()->value(), 0.1);
    }

    // ========== Mid-Level Class Tests ==========

    public function testSIForceCreation(): void
    {
        $force = SIForce::of(
            NumberFactory::create('100'),
            SIForceUnit::Newton,
        );

        self::assertEquals('100', $force->getValue()->value());
        self::assertSame(SIForceUnit::Newton, $force->getUnit());
    }

    public function testImperialForceCreation(): void
    {
        $force = ImperialForce::of(
            NumberFactory::create('150'),
            ImperialForceUnit::PoundForce,
        );

        self::assertEquals('150', $force->getValue()->value());
        self::assertSame(ImperialForceUnit::PoundForce, $force->getUnit());
    }

    public function testSIForceConversion(): void
    {
        $force = SIForce::of(
            NumberFactory::create('1000'),
            SIForceUnit::Newton,
        );

        $converted = $force->to(SIForceUnit::Kilonewton);
        self::assertEqualsWithDelta(1.0, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericForceWithSIUnit(): void
    {
        $force = Force::of(
            NumberFactory::create('500'),
            SIForceUnit::Newton,
        );

        self::assertEquals('500', $force->getValue()->value());
        self::assertSame(SIForceUnit::Newton, $force->getUnit());
    }

    public function testGenericForceWithImperialUnit(): void
    {
        $force = Force::of(
            NumberFactory::create('100'),
            ImperialForceUnit::PoundForce,
        );

        self::assertEquals('100', $force->getValue()->value());
        self::assertSame(ImperialForceUnit::PoundForce, $force->getUnit());
    }

    public function testGenericForceConversion(): void
    {
        $force = Force::of(
            NumberFactory::create('1'),
            ImperialForceUnit::Kip,
        );

        $converted = $force->to(SIForceUnit::Kilonewton);
        self::assertEqualsWithDelta(4.448, (float) $converted->getValue()->value(), 0.01);
    }

    // ========== Round-Trip Tests ==========

    public function testSIRoundTrip(): void
    {
        $original = Newton::of(NumberFactory::create('1000'));
        $toKN = $original->to(SIForceUnit::Kilonewton);

        $kNQuantity = Kilonewton::of($toKN->getValue());
        $backToN = $kNQuantity->to(SIForceUnit::Newton);

        self::assertEqualsWithDelta(1000.0, (float) $backToN->getValue()->value(), 0.001);
    }

    public function testImperialRoundTrip(): void
    {
        $original = PoundForce::of(NumberFactory::create('1000'));
        $toKip = $original->to(ImperialForceUnit::Kip);

        $kipQuantity = Kip::of($toKip->getValue());
        $backToLbf = $kipQuantity->to(ImperialForceUnit::PoundForce);

        self::assertEqualsWithDelta(1000.0, (float) $backToLbf->getValue()->value(), 0.001);
    }

    public function testCrossSystemRoundTrip(): void
    {
        $original = Newton::of(NumberFactory::create('100'));
        $toLbf = $original->to(ImperialForceUnit::PoundForce);

        $lbfQuantity = PoundForce::of($toLbf->getValue());
        $backToN = $lbfQuantity->to(SIForceUnit::Newton);

        self::assertEqualsWithDelta(100.0, (float) $backToN->getValue()->value(), 0.01);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $f1 = Newton::of(NumberFactory::create('500'));
        $f2 = Newton::of(NumberFactory::create('300'));

        $sum = $f1->add($f2);

        self::assertEqualsWithDelta(800.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $f1 = Newton::of(NumberFactory::create('500'));
        $f2 = Newton::of(NumberFactory::create('200'));

        $diff = $f1->subtract($f2);

        self::assertEqualsWithDelta(300.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $force = Newton::of(NumberFactory::create('100'));
        $result = $force->multiplyBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(300.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $force = Newton::of(NumberFactory::create('300'));
        $result = $force->divideBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(100.0, (float) $result->getValue()->value(), 0.001);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $f1 = Newton::of(NumberFactory::create('500'));
        $f2 = Newton::of(NumberFactory::create('400'));

        self::assertTrue($f1->isGreaterThan($f2));
        self::assertFalse($f1->isLessThan($f2));
        self::assertFalse($f1->equals($f2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 1 kN > 100 N
        $kN = Kilonewton::of(NumberFactory::create('1'));
        $newton = Newton::of(NumberFactory::create('100'));

        self::assertTrue($kN->isGreaterThan($newton));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 1 kN = 1000 N
        $kN = Kilonewton::of(NumberFactory::create('1'));
        $newton = Newton::of(NumberFactory::create('1000'));

        self::assertTrue($kN->equals($newton));
    }

    public function testCrossSystemEquality(): void
    {
        // 1 kN = 1000 N across systems should match when converted
        $kNtoN = Kilonewton::of(NumberFactory::create('1'))->to(SIForceUnit::Newton);
        $direct = Newton::of(NumberFactory::create('1000'));

        // Compare values which will convert to same base
        self::assertEqualsWithDelta(
            (float) $kNtoN->getValue()->value(),
            (float) $direct->getValue()->value(),
            0.001,
        );
    }
}
