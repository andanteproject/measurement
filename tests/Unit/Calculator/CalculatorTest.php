<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Calculator;

use Andante\Measurement\Calculator\Calculator;
use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Converter\Converter;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Exception\InvalidOperationException;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Math\RoundingMode;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use Andante\Measurement\Unit\UnitSystem;
use PHPUnit\Framework\TestCase;

/**
 * Mock quantity class for testing that implements QuantityFactoryInterface.
 */
class MockLengthQuantity implements QuantityInterface, QuantityFactoryInterface
{
    public function __construct(
        private NumberInterface $value,
        private UnitInterface $unit,
    ) {
    }

    public static function from(NumberInterface $value, UnitInterface $unit): QuantityInterface
    {
        return new self($value, $unit);
    }

    public function getValue(): NumberInterface
    {
        return $this->value;
    }

    public function getUnit(): UnitInterface
    {
        return $this->unit;
    }
}

final class CalculatorTest extends TestCase
{
    private Calculator $calculator;

    protected function setUp(): void
    {
        // Reset all singletons to ensure clean state
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
        ResultQuantityRegistry::reset();
        FormulaUnitRegistry::reset();
        Converter::reset();
        Calculator::reset();

        // Register conversion factors
        $conversionRegistry = ConversionFactorRegistry::global();
        $conversionRegistry->register(MetricLengthUnit::Meter, ConversionRule::factor(NumberFactory::create('1')));
        $conversionRegistry->register(MetricLengthUnit::Kilometer, ConversionRule::factor(NumberFactory::create('1000')));
        $conversionRegistry->register(MetricLengthUnit::Centimeter, ConversionRule::factor(NumberFactory::create('0.01')));

        // Register quantity class for units
        $unitRegistry = UnitRegistry::global();
        $unitRegistry->register(MetricLengthUnit::Meter, MockLengthQuantity::class);
        $unitRegistry->register(MetricLengthUnit::Kilometer, MockLengthQuantity::class);
        $unitRegistry->register(MetricLengthUnit::Centimeter, MockLengthQuantity::class);

        $this->calculator = new Calculator();
    }

    protected function tearDown(): void
    {
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
        ResultQuantityRegistry::reset();
        FormulaUnitRegistry::reset();
        Calculator::reset();
        Converter::reset();
    }

    public function testAddSameUnit(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $m200 = new MockLengthQuantity(NumberFactory::create('200'), MetricLengthUnit::Meter);

        $result = $this->calculator->add($m100, $m200);

        self::assertEqualsWithDelta(300, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testAddDifferentUnits(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $km1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        $result = $this->calculator->add($m100, $km1);

        self::assertEqualsWithDelta(1100, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testAddResultInFirstOperandUnit(): void
    {
        $km1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);
        $m500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);

        $result = $this->calculator->add($km1, $m500);

        self::assertEqualsWithDelta(1.5, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Kilometer, $result->getUnit());
    }

    public function testSubtractSameUnit(): void
    {
        $m300 = new MockLengthQuantity(NumberFactory::create('300'), MetricLengthUnit::Meter);
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        $result = $this->calculator->subtract($m300, $m100);

        self::assertEqualsWithDelta(200, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testSubtractDifferentUnits(): void
    {
        $km2 = new MockLengthQuantity(NumberFactory::create('2'), MetricLengthUnit::Kilometer);
        $m500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);

        $result = $this->calculator->subtract($km2, $m500);

        self::assertEqualsWithDelta(1.5, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Kilometer, $result->getUnit());
    }

    public function testSubtractResultCanBeNegative(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $m300 = new MockLengthQuantity(NumberFactory::create('300'), MetricLengthUnit::Meter);

        $result = $this->calculator->subtract($m100, $m300);

        self::assertEqualsWithDelta(-200, (float) $result->getValue()->value(), 0.0001);
    }

    public function testMultiplyByScalar(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $scalar = NumberFactory::create('3');

        $result = $this->calculator->multiplyByScalar($m100, $scalar);

        self::assertEqualsWithDelta(300, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testMultiplyByScalarDecimal(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $scalar = NumberFactory::create('1.5');

        $result = $this->calculator->multiplyByScalar($m100, $scalar);

        self::assertEqualsWithDelta(150, (float) $result->getValue()->value(), 0.0001);
    }

    public function testDivideByScalar(): void
    {
        $m300 = new MockLengthQuantity(NumberFactory::create('300'), MetricLengthUnit::Meter);
        $scalar = NumberFactory::create('3');

        $result = $this->calculator->divideByScalar($m300, $scalar);

        self::assertEqualsWithDelta(100, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testDivideByScalarWithScale(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $scalar = NumberFactory::create('3');

        $result = $this->calculator->divideByScalar($m100, $scalar, 2);

        self::assertEqualsWithDelta(33.33, (float) $result->getValue()->value(), 0.01);
    }

    public function testDivideByScalarWithRoundingMode(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $scalar = NumberFactory::create('3');

        $resultDown = $this->calculator->divideByScalar($m100, $scalar, 2, RoundingMode::Down);
        $resultUp = $this->calculator->divideByScalar($m100, $scalar, 2, RoundingMode::Up);

        self::assertEqualsWithDelta(33.33, (float) $resultDown->getValue()->value(), 0.001);
        self::assertEqualsWithDelta(33.34, (float) $resultUp->getValue()->value(), 0.001);
    }

    public function testSum(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $m200 = new MockLengthQuantity(NumberFactory::create('200'), MetricLengthUnit::Meter);
        $m300 = new MockLengthQuantity(NumberFactory::create('300'), MetricLengthUnit::Meter);

        $result = $this->calculator->sum($m100, $m200, $m300);

        self::assertEqualsWithDelta(600, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testSumWithDifferentUnits(): void
    {
        $m500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);
        $km1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);
        $m200 = new MockLengthQuantity(NumberFactory::create('200'), MetricLengthUnit::Meter);

        $result = $this->calculator->sum($m500, $km1, $m200);

        self::assertEqualsWithDelta(1700, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testSumSingleQuantity(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        $result = $this->calculator->sum($m100);

        self::assertEqualsWithDelta(100, (float) $result->getValue()->value(), 0.0001);
    }

    public function testAverage(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $m200 = new MockLengthQuantity(NumberFactory::create('200'), MetricLengthUnit::Meter);
        $m300 = new MockLengthQuantity(NumberFactory::create('300'), MetricLengthUnit::Meter);

        $result = $this->calculator->average($m100, $m200, $m300);

        self::assertEqualsWithDelta(200, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testAverageWithDifferentUnits(): void
    {
        $km1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);
        $km2 = new MockLengthQuantity(NumberFactory::create('2'), MetricLengthUnit::Kilometer);
        $m1500 = new MockLengthQuantity(NumberFactory::create('1500'), MetricLengthUnit::Meter);

        $result = $this->calculator->average($km1, $km2, $m1500);

        self::assertEqualsWithDelta(1.5, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Kilometer, $result->getUnit());
    }

    public function testAverageSingleQuantity(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        $result = $this->calculator->average($m100);

        self::assertEqualsWithDelta(100, (float) $result->getValue()->value(), 0.0001);
    }

    public function testAbs(): void
    {
        $mNeg100 = new MockLengthQuantity(NumberFactory::create('-100'), MetricLengthUnit::Meter);

        $result = $this->calculator->abs($mNeg100);

        self::assertEqualsWithDelta(100, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testAbsPositiveStaysPositive(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        $result = $this->calculator->abs($m100);

        self::assertEqualsWithDelta(100, (float) $result->getValue()->value(), 0.0001);
    }

    public function testNegate(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        $result = $this->calculator->negate($m100);

        self::assertEqualsWithDelta(-100, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testNegateNegativeBecomesPositive(): void
    {
        $mNeg100 = new MockLengthQuantity(NumberFactory::create('-100'), MetricLengthUnit::Meter);

        $result = $this->calculator->negate($mNeg100);

        self::assertEqualsWithDelta(100, (float) $result->getValue()->value(), 0.0001);
    }

    public function testAddThrowsExceptionForDifferentDimensions(): void
    {
        $length = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        // Mock a unit with a different dimension
        $massUnit = $this->createMock(UnitInterface::class);
        $massDimension = $this->createMock(DimensionInterface::class);
        $massDimension->method('getName')->willReturn('Mass');
        $massDimension->method('getFormula')->willReturn(
            new DimensionalFormula(0, 1, 0, 0, 0, 0, 0),
        );
        $massUnit->method('dimension')->willReturn($massDimension);

        $mass = $this->createMock(QuantityInterface::class);
        $mass->method('getValue')->willReturn(NumberFactory::create('50'));
        $mass->method('getUnit')->willReturn($massUnit);

        $this->expectException(InvalidOperationException::class);
        $this->expectExceptionMessage('Cannot perform arithmetic on different dimensions: Length and Mass');

        $this->calculator->add($length, $mass);
    }

    public function testSubtractThrowsExceptionForDifferentDimensions(): void
    {
        $length = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        // Mock a unit with a different dimension
        $massUnit = $this->createMock(UnitInterface::class);
        $massDimension = $this->createMock(DimensionInterface::class);
        $massDimension->method('getName')->willReturn('Mass');
        $massDimension->method('getFormula')->willReturn(
            new DimensionalFormula(0, 1, 0, 0, 0, 0, 0),
        );
        $massUnit->method('dimension')->willReturn($massDimension);

        $mass = $this->createMock(QuantityInterface::class);
        $mass->method('getValue')->willReturn(NumberFactory::create('50'));
        $mass->method('getUnit')->willReturn($massUnit);

        $this->expectException(InvalidOperationException::class);
        $this->expectExceptionMessage('Cannot perform arithmetic on different dimensions: Length and Mass');

        $this->calculator->subtract($length, $mass);
    }

    public function testRatioSameUnit(): void
    {
        $m200 = new MockLengthQuantity(NumberFactory::create('200'), MetricLengthUnit::Meter);
        $m50 = new MockLengthQuantity(NumberFactory::create('50'), MetricLengthUnit::Meter);

        $ratio = $this->calculator->ratio($m200, $m50);

        self::assertEqualsWithDelta(4.0, (float) $ratio->value(), 0.0001);
    }

    public function testRatioDifferentUnits(): void
    {
        $km2 = new MockLengthQuantity(NumberFactory::create('2'), MetricLengthUnit::Kilometer);
        $m500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);

        $ratio = $this->calculator->ratio($km2, $m500);

        // 2km = 2000m, 2000m / 500m = 4
        self::assertEqualsWithDelta(4.0, (float) $ratio->value(), 0.0001);
    }

    public function testRatioLessThanOne(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $km1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        $ratio = $this->calculator->ratio($m100, $km1);

        // 100m / 1000m = 0.1
        self::assertEqualsWithDelta(0.1, (float) $ratio->value(), 0.0001);
    }

    public function testRatioWithScale(): void
    {
        $m100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $m300 = new MockLengthQuantity(NumberFactory::create('300'), MetricLengthUnit::Meter);

        $ratio = $this->calculator->ratio($m100, $m300, scale: 4);

        // 100 / 300 = 0.3333...
        self::assertSame('0.3333', $ratio->value());
    }

    public function testRatioThrowsExceptionForDifferentDimensions(): void
    {
        $length = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        // Mock a unit with a different dimension
        $massUnit = $this->createMock(UnitInterface::class);
        $massDimension = $this->createMock(DimensionInterface::class);
        $massDimension->method('getName')->willReturn('Mass');
        $massDimension->method('getFormula')->willReturn(
            new DimensionalFormula(0, 1, 0, 0, 0, 0, 0),
        );
        $massUnit->method('dimension')->willReturn($massDimension);

        $mass = $this->createMock(QuantityInterface::class);
        $mass->method('getValue')->willReturn(NumberFactory::create('50'));
        $mass->method('getUnit')->willReturn($massUnit);

        $this->expectException(InvalidOperationException::class);
        $this->expectExceptionMessage('Cannot perform arithmetic on different dimensions: Length and Mass');

        $this->calculator->ratio($length, $mass);
    }

    public function testPowerSquaredLength(): void
    {
        // Create area unit and dimension (L²)
        $areaFormula = new DimensionalFormula(length: 2);
        $areaDimension = $this->createMock(DimensionInterface::class);
        $areaDimension->method('getName')->willReturn('Area');
        $areaDimension->method('getFormula')->willReturn($areaFormula);

        $areaUnit = $this->createMock(UnitInterface::class);
        $areaUnit->method('dimension')->willReturn($areaDimension);
        $areaUnit->method('name')->willReturn('square_meter');
        $areaUnit->method('system')->willReturn(UnitSystem::Metric);

        // Register area unit in FormulaUnitRegistry
        FormulaUnitRegistry::global()->register($areaFormula, $areaUnit);

        // Register in ConversionFactorRegistry (1 m² = 1 base)
        ConversionFactorRegistry::global()->register($areaUnit, ConversionRule::factor(NumberFactory::create('1')));

        // Register quantity class for area unit
        UnitRegistry::global()->register($areaUnit, MockLengthQuantity::class);

        // Register result quantity mapping
        ResultQuantityRegistry::global()->register(
            MockLengthQuantity::class,
            $areaFormula,
            MockLengthQuantity::class,
        );

        $m3 = new MockLengthQuantity(NumberFactory::create('3'), MetricLengthUnit::Meter);

        $result = $this->calculator->power($m3, 2, $areaUnit);

        // 3² = 9
        self::assertEqualsWithDelta(9.0, (float) $result->getValue()->value(), 0.0001);
        self::assertSame($areaUnit, $result->getUnit());
    }

    public function testPowerCubedLength(): void
    {
        // Create volume unit and dimension (L³)
        $volumeFormula = new DimensionalFormula(length: 3);
        $volumeDimension = $this->createMock(DimensionInterface::class);
        $volumeDimension->method('getName')->willReturn('Volume');
        $volumeDimension->method('getFormula')->willReturn($volumeFormula);

        $volumeUnit = $this->createMock(UnitInterface::class);
        $volumeUnit->method('dimension')->willReturn($volumeDimension);
        $volumeUnit->method('name')->willReturn('cubic_meter');
        $volumeUnit->method('system')->willReturn(UnitSystem::Metric);

        // Register volume unit in FormulaUnitRegistry
        FormulaUnitRegistry::global()->register($volumeFormula, $volumeUnit);

        // Register in ConversionFactorRegistry (1 m³ = 1 base)
        ConversionFactorRegistry::global()->register($volumeUnit, ConversionRule::factor(NumberFactory::create('1')));

        // Register quantity class for volume unit
        UnitRegistry::global()->register($volumeUnit, MockLengthQuantity::class);

        // Register result quantity mapping
        ResultQuantityRegistry::global()->register(
            MockLengthQuantity::class,
            $volumeFormula,
            MockLengthQuantity::class,
        );

        $m2 = new MockLengthQuantity(NumberFactory::create('2'), MetricLengthUnit::Meter);

        $result = $this->calculator->power($m2, 3, $volumeUnit);

        // 2³ = 8
        self::assertEqualsWithDelta(8.0, (float) $result->getValue()->value(), 0.0001);
        self::assertSame($volumeUnit, $result->getUnit());
    }

    public function testPowerZeroReturnsDimensionless(): void
    {
        // Create dimensionless unit
        $dimensionlessFormula = DimensionalFormula::dimensionless();
        $dimensionlessDimension = $this->createMock(DimensionInterface::class);
        $dimensionlessDimension->method('getName')->willReturn('Dimensionless');
        $dimensionlessDimension->method('getFormula')->willReturn($dimensionlessFormula);

        $dimensionlessUnit = $this->createMock(UnitInterface::class);
        $dimensionlessUnit->method('dimension')->willReturn($dimensionlessDimension);
        $dimensionlessUnit->method('name')->willReturn('one');
        $dimensionlessUnit->method('system')->willReturn(UnitSystem::None);

        // Register dimensionless unit
        FormulaUnitRegistry::global()->register($dimensionlessFormula, $dimensionlessUnit);
        ConversionFactorRegistry::global()->register($dimensionlessUnit, ConversionRule::factor(NumberFactory::create('1')));
        UnitRegistry::global()->register($dimensionlessUnit, MockLengthQuantity::class);
        ResultQuantityRegistry::global()->register(
            MockLengthQuantity::class,
            $dimensionlessFormula,
            MockLengthQuantity::class,
        );

        $m5 = new MockLengthQuantity(NumberFactory::create('5'), MetricLengthUnit::Meter);

        $result = $this->calculator->power($m5, 0, $dimensionlessUnit);

        // Any number to the power 0 = 1
        self::assertEqualsWithDelta(1.0, (float) $result->getValue()->value(), 0.0001);
    }

    public function testSqrtOfArea(): void
    {
        // Create area unit and dimension (L²)
        $areaFormula = new DimensionalFormula(length: 2);
        $areaDimension = $this->createMock(DimensionInterface::class);
        $areaDimension->method('getName')->willReturn('Area');
        $areaDimension->method('getFormula')->willReturn($areaFormula);

        $areaUnit = $this->createMock(UnitInterface::class);
        $areaUnit->method('dimension')->willReturn($areaDimension);
        $areaUnit->method('name')->willReturn('square_meter');
        $areaUnit->method('system')->willReturn(UnitSystem::Metric);

        // Register area unit
        ConversionFactorRegistry::global()->register($areaUnit, ConversionRule::factor(NumberFactory::create('1')));
        UnitRegistry::global()->register($areaUnit, MockLengthQuantity::class);

        // Register length formula for result
        $lengthFormula = DimensionalFormula::length();
        FormulaUnitRegistry::global()->register($lengthFormula, MetricLengthUnit::Meter);
        ResultQuantityRegistry::global()->register(
            MockLengthQuantity::class,
            $lengthFormula,
            MockLengthQuantity::class,
        );

        $area9 = new MockLengthQuantity(NumberFactory::create('9'), $areaUnit);

        $result = $this->calculator->sqrt($area9, MetricLengthUnit::Meter);

        // √9 = 3
        self::assertEqualsWithDelta(3.0, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testSqrtPreservesArbitraryPrecision(): void
    {
        // Create L⁴ dimension (so sqrt gives L²)
        $l4Formula = new DimensionalFormula(length: 4);
        $l4Dimension = $this->createMock(DimensionInterface::class);
        $l4Dimension->method('getName')->willReturn('L4');
        $l4Dimension->method('getFormula')->willReturn($l4Formula);

        $l4Unit = $this->createMock(UnitInterface::class);
        $l4Unit->method('dimension')->willReturn($l4Dimension);
        $l4Unit->method('name')->willReturn('m4');
        $l4Unit->method('system')->willReturn(UnitSystem::Metric);

        // Create L² result unit
        $l2Formula = new DimensionalFormula(length: 2);
        $l2Dimension = $this->createMock(DimensionInterface::class);
        $l2Dimension->method('getName')->willReturn('L2');
        $l2Dimension->method('getFormula')->willReturn($l2Formula);

        $l2Unit = $this->createMock(UnitInterface::class);
        $l2Unit->method('dimension')->willReturn($l2Dimension);
        $l2Unit->method('name')->willReturn('m2');
        $l2Unit->method('system')->willReturn(UnitSystem::Metric);

        // Register units
        ConversionFactorRegistry::global()->register($l4Unit, ConversionRule::factor(NumberFactory::create('1')));
        ConversionFactorRegistry::global()->register($l2Unit, ConversionRule::factor(NumberFactory::create('1')));
        UnitRegistry::global()->register($l4Unit, MockLengthQuantity::class);
        UnitRegistry::global()->register($l2Unit, MockLengthQuantity::class);
        FormulaUnitRegistry::global()->register($l2Formula, $l2Unit);
        ResultQuantityRegistry::global()->register(
            MockLengthQuantity::class,
            $l2Formula,
            MockLengthQuantity::class,
        );

        $quantity = new MockLengthQuantity(NumberFactory::create('16'), $l4Unit);

        $result = $this->calculator->sqrt($quantity, $l2Unit, scale: 10);

        // √16 = 4
        self::assertEqualsWithDelta(4.0, (float) $result->getValue()->value(), 0.0001);
    }

    public function testSqrtThrowsExceptionForOddExponent(): void
    {
        $m3 = new MockLengthQuantity(NumberFactory::create('8'), MetricLengthUnit::Meter);

        $this->expectException(InvalidOperationException::class);
        $this->expectExceptionMessage('Cannot take square root: length exponent 1 is not divisible by 2');

        $this->calculator->sqrt($m3);
    }

    public function testSqrtOfDimensionlessReturnsDimensionless(): void
    {
        // Create dimensionless unit
        $dimensionlessFormula = DimensionalFormula::dimensionless();
        $dimensionlessDimension = $this->createMock(DimensionInterface::class);
        $dimensionlessDimension->method('getName')->willReturn('Dimensionless');
        $dimensionlessDimension->method('getFormula')->willReturn($dimensionlessFormula);

        $dimensionlessUnit = $this->createMock(UnitInterface::class);
        $dimensionlessUnit->method('dimension')->willReturn($dimensionlessDimension);
        $dimensionlessUnit->method('name')->willReturn('one');
        $dimensionlessUnit->method('system')->willReturn(UnitSystem::None);

        // Register dimensionless unit
        FormulaUnitRegistry::global()->register($dimensionlessFormula, $dimensionlessUnit);
        ConversionFactorRegistry::global()->register($dimensionlessUnit, ConversionRule::factor(NumberFactory::create('1')));
        UnitRegistry::global()->register($dimensionlessUnit, MockLengthQuantity::class);
        ResultQuantityRegistry::global()->register(
            MockLengthQuantity::class,
            $dimensionlessFormula,
            MockLengthQuantity::class,
        );

        $four = new MockLengthQuantity(NumberFactory::create('4'), $dimensionlessUnit);

        $result = $this->calculator->sqrt($four, $dimensionlessUnit);

        // √4 = 2
        self::assertEqualsWithDelta(2.0, (float) $result->getValue()->value(), 0.0001);
        self::assertSame($dimensionlessUnit, $result->getUnit());
    }

    public function testRoundDefaultPrecision(): void
    {
        $m = new MockLengthQuantity(NumberFactory::create('123.456'), MetricLengthUnit::Meter);

        $result = $this->calculator->round($m);

        self::assertSame('123', $result->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testRoundWithPrecision(): void
    {
        $m = new MockLengthQuantity(NumberFactory::create('123.456'), MetricLengthUnit::Meter);

        $result = $this->calculator->round($m, 2);

        self::assertSame('123.46', $result->getValue()->value());
    }

    public function testRoundWithRoundingMode(): void
    {
        $m = new MockLengthQuantity(NumberFactory::create('123.455'), MetricLengthUnit::Meter);

        $resultHalfUp = $this->calculator->round($m, 2, RoundingMode::HalfUp);
        $resultHalfDown = $this->calculator->round($m, 2, RoundingMode::HalfDown);

        self::assertSame('123.46', $resultHalfUp->getValue()->value());
        self::assertSame('123.45', $resultHalfDown->getValue()->value());
    }

    public function testFloorDefaultPrecision(): void
    {
        $m = new MockLengthQuantity(NumberFactory::create('123.999'), MetricLengthUnit::Meter);

        $result = $this->calculator->floor($m);

        self::assertSame('123', $result->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testFloorWithPrecision(): void
    {
        $m = new MockLengthQuantity(NumberFactory::create('123.459'), MetricLengthUnit::Meter);

        $result = $this->calculator->floor($m, 2);

        self::assertSame('123.45', $result->getValue()->value());
    }

    public function testFloorNegative(): void
    {
        $m = new MockLengthQuantity(NumberFactory::create('-123.001'), MetricLengthUnit::Meter);

        $result = $this->calculator->floor($m);

        // Floor towards negative infinity: -123.001 → -124
        self::assertSame('-124', $result->getValue()->value());
    }

    public function testCeilDefaultPrecision(): void
    {
        $m = new MockLengthQuantity(NumberFactory::create('123.001'), MetricLengthUnit::Meter);

        $result = $this->calculator->ceil($m);

        self::assertSame('124', $result->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testCeilWithPrecision(): void
    {
        $m = new MockLengthQuantity(NumberFactory::create('123.451'), MetricLengthUnit::Meter);

        $result = $this->calculator->ceil($m, 2);

        self::assertSame('123.46', $result->getValue()->value());
    }

    public function testCeilNegative(): void
    {
        $m = new MockLengthQuantity(NumberFactory::create('-123.999'), MetricLengthUnit::Meter);

        $result = $this->calculator->ceil($m);

        // Ceil towards positive infinity: -123.999 → -123
        self::assertSame('-123', $result->getValue()->value());
    }
}
