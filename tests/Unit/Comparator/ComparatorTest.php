<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Comparator;

use Andante\Measurement\Comparator\Comparator;
use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Exception\InvalidOperationException;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use PHPUnit\Framework\TestCase;

// Mock quantity implementations for testing
class MockLengthQuantity implements QuantityInterface
{
    public function __construct(
        private NumberInterface $value,
        private UnitInterface $unit,
    ) {
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

final class ComparatorTest extends TestCase
{
    private Comparator $comparator;

    protected function setUp(): void
    {
        // Register conversion factors for testing
        $registry = ConversionFactorRegistry::global();
        $registry->register(MetricLengthUnit::Meter, ConversionRule::factor(NumberFactory::create('1')));
        $registry->register(MetricLengthUnit::Kilometer, ConversionRule::factor(NumberFactory::create('1000')));
        $registry->register(MetricLengthUnit::Centimeter, ConversionRule::factor(NumberFactory::create('0.01')));

        $this->comparator = new Comparator();
    }

    protected function tearDown(): void
    {
        ConversionFactorRegistry::reset();
    }

    public function testCompareEqualQuantities(): void
    {
        $meter1 = new MockLengthQuantity(NumberFactory::create('1000'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        $result = $this->comparator->compare($meter1, $kilometer1);

        self::assertSame(0, $result);
    }

    public function testCompareFirstGreaterThanSecond(): void
    {
        $meter2000 = new MockLengthQuantity(NumberFactory::create('2000'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        $result = $this->comparator->compare($meter2000, $kilometer1);

        self::assertSame(1, $result);
    }

    public function testCompareFirstLessThanSecond(): void
    {
        $meter500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        $result = $this->comparator->compare($meter500, $kilometer1);

        self::assertSame(-1, $result);
    }

    public function testCompareThrowsExceptionForDifferentDimensions(): void
    {
        $length = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        // Mock a unit with a different dimension (Mass)
        $massFormula = new DimensionalFormula(0, 1, 0, 0, 0, 0, 0);
        $massDimension = $this->createMock(DimensionInterface::class);
        $massDimension->method('getName')->willReturn('Mass');
        $massDimension->method('getFormula')->willReturn($massFormula);

        $massUnit = $this->createMock(UnitInterface::class);
        $massUnit->method('dimension')->willReturn($massDimension);

        $mass = $this->createMock(QuantityInterface::class);
        $mass->method('getValue')->willReturn(NumberFactory::create('50'));
        $mass->method('getUnit')->willReturn($massUnit);

        $this->expectException(InvalidOperationException::class);
        $this->expectExceptionMessage('Cannot compare different dimensions: Length and Mass');

        $this->comparator->compare($length, $mass);
    }

    public function testEquals(): void
    {
        $meter1000 = new MockLengthQuantity(NumberFactory::create('1000'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        $result = $this->comparator->equals($meter1000, $kilometer1);

        self::assertTrue($result);
    }

    public function testEqualsWithTolerance(): void
    {
        $meter1000 = new MockLengthQuantity(NumberFactory::create('1000'), MetricLengthUnit::Meter);
        $meter1001 = new MockLengthQuantity(NumberFactory::create('1001'), MetricLengthUnit::Meter);

        // Without tolerance - not equal
        self::assertFalse($this->comparator->equals($meter1000, $meter1001));

        // With tolerance of 2 meters - equal
        $tolerance = NumberFactory::create('2');
        self::assertTrue($this->comparator->equals($meter1000, $meter1001, $tolerance));
    }

    public function testEqualsDifferentDimensionsReturnsFalse(): void
    {
        $length = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        // Mock a unit with a different dimension (Mass)
        $massFormula = new DimensionalFormula(0, 1, 0, 0, 0, 0, 0);
        $massDimension = $this->createMock(DimensionInterface::class);
        $massDimension->method('getName')->willReturn('Mass');
        $massDimension->method('getFormula')->willReturn($massFormula);

        $massUnit = $this->createMock(UnitInterface::class);
        $massUnit->method('dimension')->willReturn($massDimension);

        $mass = $this->createMock(QuantityInterface::class);
        $mass->method('getValue')->willReturn(NumberFactory::create('100'));
        $mass->method('getUnit')->willReturn($massUnit);

        $result = $this->comparator->equals($length, $mass);

        self::assertFalse($result);
    }

    public function testIsGreaterThan(): void
    {
        $meter2000 = new MockLengthQuantity(NumberFactory::create('2000'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        self::assertTrue($this->comparator->isGreaterThan($meter2000, $kilometer1));
        self::assertFalse($this->comparator->isGreaterThan($kilometer1, $meter2000));
    }

    public function testIsGreaterThanOrEqual(): void
    {
        $meter1000 = new MockLengthQuantity(NumberFactory::create('1000'), MetricLengthUnit::Meter);
        $meter2000 = new MockLengthQuantity(NumberFactory::create('2000'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        self::assertTrue($this->comparator->isGreaterThanOrEqual($meter2000, $kilometer1));
        self::assertTrue($this->comparator->isGreaterThanOrEqual($meter1000, $kilometer1)); // Equal
        self::assertFalse($this->comparator->isGreaterThanOrEqual($kilometer1, $meter2000));
    }

    public function testIsLessThan(): void
    {
        $meter500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        self::assertTrue($this->comparator->isLessThan($meter500, $kilometer1));
        self::assertFalse($this->comparator->isLessThan($kilometer1, $meter500));
    }

    public function testIsLessThanOrEqual(): void
    {
        $meter500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);
        $meter1000 = new MockLengthQuantity(NumberFactory::create('1000'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        self::assertTrue($this->comparator->isLessThanOrEqual($meter500, $kilometer1));
        self::assertTrue($this->comparator->isLessThanOrEqual($meter1000, $kilometer1)); // Equal
        self::assertFalse($this->comparator->isLessThanOrEqual($kilometer1, $meter500));
    }

    public function testMin(): void
    {
        $meter500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        $min = $this->comparator->min($meter500, $kilometer1);

        self::assertSame($meter500, $min);
    }

    public function testMax(): void
    {
        $meter500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        $max = $this->comparator->max($meter500, $kilometer1);

        self::assertSame($kilometer1, $max);
    }

    public function testMinWithMultipleQuantities(): void
    {
        $meter500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);
        $meter1000 = new MockLengthQuantity(NumberFactory::create('1000'), MetricLengthUnit::Meter);
        $meter200 = new MockLengthQuantity(NumberFactory::create('200'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        $min = $this->comparator->min($meter500, $meter1000, $meter200, $kilometer1);

        self::assertSame($meter200, $min);
    }

    public function testMaxWithMultipleQuantities(): void
    {
        $meter500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);
        $meter1000 = new MockLengthQuantity(NumberFactory::create('1000'), MetricLengthUnit::Meter);
        $meter2000 = new MockLengthQuantity(NumberFactory::create('2000'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        $max = $this->comparator->max($meter500, $meter1000, $meter2000, $kilometer1);

        self::assertSame($meter2000, $max);
    }

    public function testMinWithSingleQuantity(): void
    {
        $meter500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);

        $min = $this->comparator->min($meter500);

        self::assertSame($meter500, $min);
    }

    public function testMaxWithSingleQuantity(): void
    {
        $meter500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);

        $max = $this->comparator->max($meter500);

        self::assertSame($meter500, $max);
    }

    public function testCompareSameUnitOptimization(): void
    {
        // When comparing quantities with the same unit, no conversion should happen
        $meter1000 = new MockLengthQuantity(NumberFactory::create('1000'), MetricLengthUnit::Meter);
        $meter2000 = new MockLengthQuantity(NumberFactory::create('2000'), MetricLengthUnit::Meter);

        $result = $this->comparator->compare($meter1000, $meter2000);

        self::assertSame(-1, $result);
    }

    public function testEqualsSameUnitOptimization(): void
    {
        // When comparing quantities with the same unit, no conversion should happen
        $meter1000a = new MockLengthQuantity(NumberFactory::create('1000'), MetricLengthUnit::Meter);
        $meter1000b = new MockLengthQuantity(NumberFactory::create('1000'), MetricLengthUnit::Meter);

        self::assertTrue($this->comparator->equals($meter1000a, $meter1000b));
    }

    public function testIsBetweenTrue(): void
    {
        $meter500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);
        $meter100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        self::assertTrue($this->comparator->isBetween($meter500, $meter100, $kilometer1));
    }

    public function testIsBetweenFalseBelowMin(): void
    {
        $meter50 = new MockLengthQuantity(NumberFactory::create('50'), MetricLengthUnit::Meter);
        $meter100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        self::assertFalse($this->comparator->isBetween($meter50, $meter100, $kilometer1));
    }

    public function testIsBetweenFalseAboveMax(): void
    {
        $meter2000 = new MockLengthQuantity(NumberFactory::create('2000'), MetricLengthUnit::Meter);
        $meter100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        self::assertFalse($this->comparator->isBetween($meter2000, $meter100, $kilometer1));
    }

    public function testIsBetweenInclusiveAtBounds(): void
    {
        $meter100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        // At lower bound
        self::assertTrue($this->comparator->isBetween($meter100, $meter100, $kilometer1));

        // At upper bound
        self::assertTrue($this->comparator->isBetween($kilometer1, $meter100, $kilometer1));
    }

    public function testClampReturnsValueWhenInRange(): void
    {
        $meter500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);
        $meter100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        $result = $this->comparator->clamp($meter500, $meter100, $kilometer1);

        self::assertSame($meter500, $result);
    }

    public function testClampReturnsMinWhenBelowRange(): void
    {
        $meter50 = new MockLengthQuantity(NumberFactory::create('50'), MetricLengthUnit::Meter);
        $meter100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        $result = $this->comparator->clamp($meter50, $meter100, $kilometer1);

        self::assertSame($meter100, $result);
    }

    public function testClampReturnsMaxWhenAboveRange(): void
    {
        $meter2000 = new MockLengthQuantity(NumberFactory::create('2000'), MetricLengthUnit::Meter);
        $meter100 = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $kilometer1 = new MockLengthQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        $result = $this->comparator->clamp($meter2000, $meter100, $kilometer1);

        self::assertSame($kilometer1, $result);
    }

    public function testClampWithDifferentUnits(): void
    {
        // Value in km, bounds in meters - should still work
        $km2 = new MockLengthQuantity(NumberFactory::create('2'), MetricLengthUnit::Kilometer);
        $meter500 = new MockLengthQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);
        $meter3000 = new MockLengthQuantity(NumberFactory::create('3000'), MetricLengthUnit::Meter);

        // 2km = 2000m, which is between 500m and 3000m
        $result = $this->comparator->clamp($km2, $meter500, $meter3000);

        self::assertSame($km2, $result);
    }

    public function testIsZeroTrue(): void
    {
        $zero = new MockLengthQuantity(NumberFactory::create('0'), MetricLengthUnit::Meter);

        self::assertTrue($this->comparator->isZero($zero));
    }

    public function testIsZeroFalsePositive(): void
    {
        $positive = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        self::assertFalse($this->comparator->isZero($positive));
    }

    public function testIsZeroFalseNegative(): void
    {
        $negative = new MockLengthQuantity(NumberFactory::create('-100'), MetricLengthUnit::Meter);

        self::assertFalse($this->comparator->isZero($negative));
    }

    public function testIsPositiveTrue(): void
    {
        $positive = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        self::assertTrue($this->comparator->isPositive($positive));
    }

    public function testIsPositiveFalseZero(): void
    {
        $zero = new MockLengthQuantity(NumberFactory::create('0'), MetricLengthUnit::Meter);

        self::assertFalse($this->comparator->isPositive($zero));
    }

    public function testIsPositiveFalseNegative(): void
    {
        $negative = new MockLengthQuantity(NumberFactory::create('-100'), MetricLengthUnit::Meter);

        self::assertFalse($this->comparator->isPositive($negative));
    }

    public function testIsNegativeTrue(): void
    {
        $negative = new MockLengthQuantity(NumberFactory::create('-100'), MetricLengthUnit::Meter);

        self::assertTrue($this->comparator->isNegative($negative));
    }

    public function testIsNegativeFalseZero(): void
    {
        $zero = new MockLengthQuantity(NumberFactory::create('0'), MetricLengthUnit::Meter);

        self::assertFalse($this->comparator->isNegative($zero));
    }

    public function testIsNegativeFalsePositive(): void
    {
        $positive = new MockLengthQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        self::assertFalse($this->comparator->isNegative($positive));
    }
}
