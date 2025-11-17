<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Angle;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Angle\Angle;
use Andante\Measurement\Quantity\Angle\SI\Arcminute;
use Andante\Measurement\Quantity\Angle\SI\Arcsecond;
use Andante\Measurement\Quantity\Angle\SI\Degree;
use Andante\Measurement\Quantity\Angle\SI\Gradian;
use Andante\Measurement\Quantity\Angle\SI\Milliradian;
use Andante\Measurement\Quantity\Angle\SI\Radian;
use Andante\Measurement\Quantity\Angle\SI\Revolution;
use Andante\Measurement\Quantity\Angle\SI\Turn;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Angle\AngleUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for angle conversions.
 *
 * Angle [dimensionless] measures rotation or inclination.
 * Base unit: radian (rad)
 *
 * Common conversions:
 * - 1 rad ≈ 57.2958°
 * - 1° = π/180 rad ≈ 0.01745 rad
 * - 1 gon = π/200 rad = 0.9°
 * - 1 rev = 2π rad = 360° = 400 gon
 * - 1° = 60′ = 3600″
 * - 1 mrad = 0.001 rad
 */
final class AngleConversionTest extends TestCase
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

    // ========== Radian-based Tests ==========

    public function testRadianToDegree(): void
    {
        // 1 rad ≈ 57.2958°
        $rad = Radian::of(NumberFactory::create('1'));
        $deg = $rad->to(AngleUnit::Degree);

        self::assertEqualsWithDelta(57.2958, (float) $deg->getValue()->value(), 0.001);
    }

    public function testDegreeToRadian(): void
    {
        // 180° = π rad ≈ 3.14159 rad
        $deg = Degree::of(NumberFactory::create('180'));
        $rad = $deg->to(AngleUnit::Radian);

        self::assertEqualsWithDelta(3.14159, (float) $rad->getValue()->value(), 0.001);
    }

    public function testRadianToMilliradian(): void
    {
        // 1 rad = 1000 mrad
        $rad = Radian::of(NumberFactory::create('1'));
        $mrad = $rad->to(AngleUnit::Milliradian);

        self::assertEqualsWithDelta(1000.0, (float) $mrad->getValue()->value(), 0.001);
    }

    public function testMilliradianToRadian(): void
    {
        // 1000 mrad = 1 rad
        $mrad = Milliradian::of(NumberFactory::create('1000'));
        $rad = $mrad->to(AngleUnit::Radian);

        self::assertEqualsWithDelta(1.0, (float) $rad->getValue()->value(), 0.001);
    }

    public function testRadianToGradian(): void
    {
        // π rad = 200 gon
        $rad = Radian::of(NumberFactory::create('3.14159265358979'));
        $gon = $rad->to(AngleUnit::Gradian);

        self::assertEqualsWithDelta(200.0, (float) $gon->getValue()->value(), 0.001);
    }

    public function testGradianToRadian(): void
    {
        // 200 gon = π rad
        $gon = Gradian::of(NumberFactory::create('200'));
        $rad = $gon->to(AngleUnit::Radian);

        self::assertEqualsWithDelta(3.14159, (float) $rad->getValue()->value(), 0.001);
    }

    public function testRadianToRevolution(): void
    {
        // 2π rad = 1 rev
        $rad = Radian::of(NumberFactory::create('6.28318530717959'));
        $rev = $rad->to(AngleUnit::Revolution);

        self::assertEqualsWithDelta(1.0, (float) $rev->getValue()->value(), 0.001);
    }

    public function testRevolutionToRadian(): void
    {
        // 1 rev = 2π rad
        $rev = Revolution::of(NumberFactory::create('1'));
        $rad = $rev->to(AngleUnit::Radian);

        self::assertEqualsWithDelta(6.28318, (float) $rad->getValue()->value(), 0.001);
    }

    public function testRadianToTurn(): void
    {
        // 2π rad = 1 turn
        $rad = Radian::of(NumberFactory::create('6.28318530717959'));
        $turn = $rad->to(AngleUnit::Turn);

        self::assertEqualsWithDelta(1.0, (float) $turn->getValue()->value(), 0.001);
    }

    public function testTurnToRadian(): void
    {
        // 1 turn = 2π rad
        $turn = Turn::of(NumberFactory::create('1'));
        $rad = $turn->to(AngleUnit::Radian);

        self::assertEqualsWithDelta(6.28318, (float) $rad->getValue()->value(), 0.001);
    }

    // ========== Degree-based Tests ==========

    public function testDegreeToArcminute(): void
    {
        // 1° = 60′
        $deg = Degree::of(NumberFactory::create('1'));
        $arcmin = $deg->to(AngleUnit::Arcminute);

        self::assertEqualsWithDelta(60.0, (float) $arcmin->getValue()->value(), 0.001);
    }

    public function testArcminuteToDegree(): void
    {
        // 60′ = 1°
        $arcmin = Arcminute::of(NumberFactory::create('60'));
        $deg = $arcmin->to(AngleUnit::Degree);

        self::assertEqualsWithDelta(1.0, (float) $deg->getValue()->value(), 0.001);
    }

    public function testDegreeToArcsecond(): void
    {
        // 1° = 3600″
        $deg = Degree::of(NumberFactory::create('1'));
        $arcsec = $deg->to(AngleUnit::Arcsecond);

        self::assertEqualsWithDelta(3600.0, (float) $arcsec->getValue()->value(), 0.1);
    }

    public function testArcsecondToDegree(): void
    {
        // 3600″ = 1°
        $arcsec = Arcsecond::of(NumberFactory::create('3600'));
        $deg = $arcsec->to(AngleUnit::Degree);

        self::assertEqualsWithDelta(1.0, (float) $deg->getValue()->value(), 0.001);
    }

    public function testArcminuteToArcsecond(): void
    {
        // 1′ = 60″
        $arcmin = Arcminute::of(NumberFactory::create('1'));
        $arcsec = $arcmin->to(AngleUnit::Arcsecond);

        self::assertEqualsWithDelta(60.0, (float) $arcsec->getValue()->value(), 0.001);
    }

    public function testArcsecondToArcminute(): void
    {
        // 60″ = 1′
        $arcsec = Arcsecond::of(NumberFactory::create('60'));
        $arcmin = $arcsec->to(AngleUnit::Arcminute);

        self::assertEqualsWithDelta(1.0, (float) $arcmin->getValue()->value(), 0.001);
    }

    public function testDegreeToGradian(): void
    {
        // 90° = 100 gon
        $deg = Degree::of(NumberFactory::create('90'));
        $gon = $deg->to(AngleUnit::Gradian);

        self::assertEqualsWithDelta(100.0, (float) $gon->getValue()->value(), 0.001);
    }

    public function testGradianToDegree(): void
    {
        // 100 gon = 90°
        $gon = Gradian::of(NumberFactory::create('100'));
        $deg = $gon->to(AngleUnit::Degree);

        self::assertEqualsWithDelta(90.0, (float) $deg->getValue()->value(), 0.001);
    }

    public function testDegreeToRevolution(): void
    {
        // 360° = 1 rev
        $deg = Degree::of(NumberFactory::create('360'));
        $rev = $deg->to(AngleUnit::Revolution);

        self::assertEqualsWithDelta(1.0, (float) $rev->getValue()->value(), 0.001);
    }

    public function testRevolutionToDegree(): void
    {
        // 1 rev = 360°
        $rev = Revolution::of(NumberFactory::create('1'));
        $deg = $rev->to(AngleUnit::Degree);

        self::assertEqualsWithDelta(360.0, (float) $deg->getValue()->value(), 0.001);
    }

    // ========== Gradian Tests ==========

    public function testGradianToRevolution(): void
    {
        // 400 gon = 1 rev
        $gon = Gradian::of(NumberFactory::create('400'));
        $rev = $gon->to(AngleUnit::Revolution);

        self::assertEqualsWithDelta(1.0, (float) $rev->getValue()->value(), 0.001);
    }

    public function testRevolutionToGradian(): void
    {
        // 1 rev = 400 gon
        $rev = Revolution::of(NumberFactory::create('1'));
        $gon = $rev->to(AngleUnit::Gradian);

        self::assertEqualsWithDelta(400.0, (float) $gon->getValue()->value(), 0.001);
    }

    // ========== Real-World Scenario Tests ==========

    public function testRightAngle(): void
    {
        // 90° = π/2 rad = 100 gon = 0.25 rev
        $rightAngle = Degree::of(NumberFactory::create('90'));

        $rad = $rightAngle->to(AngleUnit::Radian);
        self::assertEqualsWithDelta(1.5708, (float) $rad->getValue()->value(), 0.001);

        $gon = $rightAngle->to(AngleUnit::Gradian);
        self::assertEqualsWithDelta(100.0, (float) $gon->getValue()->value(), 0.001);

        $rev = $rightAngle->to(AngleUnit::Revolution);
        self::assertEqualsWithDelta(0.25, (float) $rev->getValue()->value(), 0.001);
    }

    public function testStraightAngle(): void
    {
        // 180° = π rad = 200 gon = 0.5 rev
        $straightAngle = Degree::of(NumberFactory::create('180'));

        $rad = $straightAngle->to(AngleUnit::Radian);
        self::assertEqualsWithDelta(3.14159, (float) $rad->getValue()->value(), 0.001);

        $gon = $straightAngle->to(AngleUnit::Gradian);
        self::assertEqualsWithDelta(200.0, (float) $gon->getValue()->value(), 0.001);

        $rev = $straightAngle->to(AngleUnit::Revolution);
        self::assertEqualsWithDelta(0.5, (float) $rev->getValue()->value(), 0.001);
    }

    public function testFullCircle(): void
    {
        // 360° = 2π rad = 400 gon = 1 rev
        $fullCircle = Degree::of(NumberFactory::create('360'));

        $rad = $fullCircle->to(AngleUnit::Radian);
        self::assertEqualsWithDelta(6.28318, (float) $rad->getValue()->value(), 0.001);

        $gon = $fullCircle->to(AngleUnit::Gradian);
        self::assertEqualsWithDelta(400.0, (float) $gon->getValue()->value(), 0.001);

        $rev = $fullCircle->to(AngleUnit::Revolution);
        self::assertEqualsWithDelta(1.0, (float) $rev->getValue()->value(), 0.001);
    }

    public function testLatitudeCoordinates(): void
    {
        // Latitude: 45° 30′ 15″ to decimal degrees
        $degrees = 45;
        $arcmin = Arcminute::of(NumberFactory::create('30'));
        $arcsec = Arcsecond::of(NumberFactory::create('15'));

        $arcminDeg = (float) $arcmin->to(AngleUnit::Degree)->getValue()->value();
        $arcsecDeg = (float) $arcsec->to(AngleUnit::Degree)->getValue()->value();

        $totalDegrees = $degrees + $arcminDeg + $arcsecDeg;
        self::assertEqualsWithDelta(45.50417, $totalDegrees, 0.001);
    }

    public function testMilitaryMils(): void
    {
        // Military uses milliradians (approximately 6400 NATO mils in a circle)
        // 1 rad = 1000 mrad, 2π rad ≈ 6283.185 mrad
        $fullCircle = Radian::of(NumberFactory::create('6.28318530717959'));
        $mrad = $fullCircle->to(AngleUnit::Milliradian);

        self::assertEqualsWithDelta(6283.185, (float) $mrad->getValue()->value(), 0.1);
    }

    public function testSurveyingAngle(): void
    {
        // Surveyors often use gradians for ease of calculation
        // A right angle is exactly 100 gon
        $rightAngle = Gradian::of(NumberFactory::create('100'));

        $deg = $rightAngle->to(AngleUnit::Degree);
        self::assertEqualsWithDelta(90.0, (float) $deg->getValue()->value(), 0.001);
    }

    // ========== Generic Class Tests ==========

    public function testGenericAngleWithRadian(): void
    {
        $angle = Angle::of(
            NumberFactory::create('1.5708'),
            AngleUnit::Radian,
        );

        self::assertEquals('1.5708', $angle->getValue()->value());
        self::assertSame(AngleUnit::Radian, $angle->getUnit());
    }

    public function testGenericAngleWithDegree(): void
    {
        $angle = Angle::of(
            NumberFactory::create('45'),
            AngleUnit::Degree,
        );

        self::assertEquals('45', $angle->getValue()->value());
        self::assertSame(AngleUnit::Degree, $angle->getUnit());
    }

    public function testGenericAngleConversion(): void
    {
        $angle = Angle::of(
            NumberFactory::create('90'),
            AngleUnit::Degree,
        );

        $converted = $angle->to(AngleUnit::Radian);
        self::assertEqualsWithDelta(1.5708, (float) $converted->getValue()->value(), 0.001);
    }

    // ========== Round-Trip Tests ==========

    public function testRadianDegreeRoundTrip(): void
    {
        $original = Radian::of(NumberFactory::create('1'));
        $toDeg = $original->to(AngleUnit::Degree);

        $degQuantity = Degree::of($toDeg->getValue());
        $backToRad = $degQuantity->to(AngleUnit::Radian);

        self::assertEqualsWithDelta(1.0, (float) $backToRad->getValue()->value(), 0.001);
    }

    public function testDegreeGradianRoundTrip(): void
    {
        $original = Degree::of(NumberFactory::create('90'));
        $toGon = $original->to(AngleUnit::Gradian);

        $gonQuantity = Gradian::of($toGon->getValue());
        $backToDeg = $gonQuantity->to(AngleUnit::Degree);

        self::assertEqualsWithDelta(90.0, (float) $backToDeg->getValue()->value(), 0.001);
    }

    public function testRevolutionDegreeRoundTrip(): void
    {
        $original = Revolution::of(NumberFactory::create('0.5'));
        $toDeg = $original->to(AngleUnit::Degree);

        $degQuantity = Degree::of($toDeg->getValue());
        $backToRev = $degQuantity->to(AngleUnit::Revolution);

        self::assertEqualsWithDelta(0.5, (float) $backToRev->getValue()->value(), 0.001);
    }

    // ========== Arithmetic Tests ==========

    public function testAddition(): void
    {
        $a1 = Degree::of(NumberFactory::create('45'));
        $a2 = Degree::of(NumberFactory::create('45'));

        $sum = $a1->add($a2);

        self::assertEqualsWithDelta(90.0, (float) $sum->getValue()->value(), 0.001);
    }

    public function testSubtraction(): void
    {
        $a1 = Degree::of(NumberFactory::create('180'));
        $a2 = Degree::of(NumberFactory::create('90'));

        $diff = $a1->subtract($a2);

        self::assertEqualsWithDelta(90.0, (float) $diff->getValue()->value(), 0.001);
    }

    public function testMultiplication(): void
    {
        $angle = Degree::of(NumberFactory::create('30'));
        $result = $angle->multiplyBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(90.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testDivision(): void
    {
        $angle = Degree::of(NumberFactory::create('360'));
        $result = $angle->divideBy(NumberFactory::create('4'));

        self::assertEqualsWithDelta(90.0, (float) $result->getValue()->value(), 0.001);
    }

    public function testAdditionAcrossUnits(): void
    {
        // 90° + π/2 rad = 180°
        $deg = Degree::of(NumberFactory::create('90'));
        $rad = Radian::of(NumberFactory::create('1.5707963267949'));

        $sum = $deg->add($rad);

        self::assertEqualsWithDelta(180.0, (float) $sum->getValue()->value(), 0.01);
    }

    // ========== Comparison Tests ==========

    public function testComparison(): void
    {
        $a1 = Degree::of(NumberFactory::create('90'));
        $a2 = Degree::of(NumberFactory::create('45'));

        self::assertTrue($a1->isGreaterThan($a2));
        self::assertFalse($a1->isLessThan($a2));
        self::assertFalse($a1->equals($a2));
    }

    public function testComparisonAcrossUnits(): void
    {
        // 90° > 1 rad (≈57.3°)
        $deg = Degree::of(NumberFactory::create('90'));
        $rad = Radian::of(NumberFactory::create('1'));

        self::assertTrue($deg->isGreaterThan($rad));
    }

    public function testEqualityAcrossUnits(): void
    {
        // 180° = π rad
        $deg = Degree::of(NumberFactory::create('180'));
        $rad = Radian::of(NumberFactory::create('3.14159265358979'));

        self::assertTrue($deg->equals($rad));
    }

    public function testRevolutionTurnEquivalence(): void
    {
        // 1 rev = 1 turn
        $rev = Revolution::of(NumberFactory::create('1'));
        $turn = Turn::of(NumberFactory::create('1'));

        self::assertTrue($rev->equals($turn));
    }
}
