<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Trait;

use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use PHPUnit\Framework\TestCase;

/**
 * Test quantity class that uses all traits.
 */
class TestQuantity implements QuantityInterface, QuantityFactoryInterface, ComparableInterface, CalculableInterface, ConvertibleInterface
{
    use ComparableTrait;
    use CalculableTrait;
    use ConvertibleTrait;

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

final class QuantityTraitsTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset all global services to ensure clean state
        \Andante\Measurement\Converter\Converter::reset();
        \Andante\Measurement\Comparator\Comparator::reset();
        \Andante\Measurement\Calculator\Calculator::reset();
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();

        // Register conversion factors
        $conversionRegistry = ConversionFactorRegistry::global();
        $conversionRegistry->register(MetricLengthUnit::Meter, ConversionRule::factor(NumberFactory::create('1')));
        $conversionRegistry->register(MetricLengthUnit::Kilometer, ConversionRule::factor(NumberFactory::create('1000')));
        $conversionRegistry->register(MetricLengthUnit::Centimeter, ConversionRule::factor(NumberFactory::create('0.01')));

        // Register quantity class for units
        $unitRegistry = UnitRegistry::global();
        $unitRegistry->register(MetricLengthUnit::Meter, TestQuantity::class);
        $unitRegistry->register(MetricLengthUnit::Kilometer, TestQuantity::class);
        $unitRegistry->register(MetricLengthUnit::Centimeter, TestQuantity::class);
    }

    protected function tearDown(): void
    {
        TestQuantity::resetComparator();
        TestQuantity::resetCalculator();
        TestQuantity::resetConverter();
        \Andante\Measurement\Converter\Converter::reset();
        \Andante\Measurement\Comparator\Comparator::reset();
        \Andante\Measurement\Calculator\Calculator::reset();
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
    }

    // ComparableTrait tests

    public function testCompareTo(): void
    {
        $m100 = new TestQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $m200 = new TestQuantity(NumberFactory::create('200'), MetricLengthUnit::Meter);

        self::assertSame(-1, $m100->compareTo($m200));
        self::assertSame(1, $m200->compareTo($m100));
        self::assertSame(0, $m100->compareTo($m100));
    }

    public function testEquals(): void
    {
        $m1000 = new TestQuantity(NumberFactory::create('1000'), MetricLengthUnit::Meter);
        $km1 = new TestQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        self::assertTrue($m1000->equals($km1));
    }

    public function testIsGreaterThan(): void
    {
        $m100 = new TestQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $m50 = new TestQuantity(NumberFactory::create('50'), MetricLengthUnit::Meter);

        self::assertTrue($m100->isGreaterThan($m50));
        self::assertFalse($m50->isGreaterThan($m100));
    }

    public function testIsLessThan(): void
    {
        $m100 = new TestQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $km1 = new TestQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        self::assertTrue($m100->isLessThan($km1));
    }

    public function testIsZero(): void
    {
        $zero = new TestQuantity(NumberFactory::create('0'), MetricLengthUnit::Meter);
        $nonZero = new TestQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        self::assertTrue($zero->isZero());
        self::assertFalse($nonZero->isZero());
    }

    public function testIsPositive(): void
    {
        $positive = new TestQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $negative = new TestQuantity(NumberFactory::create('-100'), MetricLengthUnit::Meter);

        self::assertTrue($positive->isPositive());
        self::assertFalse($negative->isPositive());
    }

    public function testIsNegative(): void
    {
        $positive = new TestQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $negative = new TestQuantity(NumberFactory::create('-100'), MetricLengthUnit::Meter);

        self::assertFalse($positive->isNegative());
        self::assertTrue($negative->isNegative());
    }

    public function testIsBetween(): void
    {
        $m500 = new TestQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);
        $m100 = new TestQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $km1 = new TestQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        self::assertTrue($m500->isBetween($m100, $km1));
        self::assertFalse($m100->isBetween($m500, $km1));
    }

    // CalculableTrait tests

    public function testAdd(): void
    {
        $m100 = new TestQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $m200 = new TestQuantity(NumberFactory::create('200'), MetricLengthUnit::Meter);

        $result = $m100->add($m200);

        self::assertEqualsWithDelta(300.0, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testAddDifferentUnits(): void
    {
        $m100 = new TestQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $km1 = new TestQuantity(NumberFactory::create('1'), MetricLengthUnit::Kilometer);

        $result = $m100->add($km1);

        self::assertEqualsWithDelta(1100.0, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testSubtract(): void
    {
        $m300 = new TestQuantity(NumberFactory::create('300'), MetricLengthUnit::Meter);
        $m100 = new TestQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        $result = $m300->subtract($m100);

        self::assertEqualsWithDelta(200.0, (float) $result->getValue()->value(), 0.0001);
    }

    public function testMultiplyBy(): void
    {
        $m100 = new TestQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        $result = $m100->multiplyBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(300.0, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testDivideBy(): void
    {
        $m300 = new TestQuantity(NumberFactory::create('300'), MetricLengthUnit::Meter);

        $result = $m300->divideBy(NumberFactory::create('3'));

        self::assertEqualsWithDelta(100.0, (float) $result->getValue()->value(), 0.0001);
    }

    public function testAbs(): void
    {
        $negative = new TestQuantity(NumberFactory::create('-100'), MetricLengthUnit::Meter);

        $result = $negative->abs();

        self::assertEqualsWithDelta(100.0, (float) $result->getValue()->value(), 0.0001);
    }

    public function testNegate(): void
    {
        $positive = new TestQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        $result = $positive->negate();

        self::assertEqualsWithDelta(-100.0, (float) $result->getValue()->value(), 0.0001);
    }

    public function testRound(): void
    {
        $m = new TestQuantity(NumberFactory::create('123.456'), MetricLengthUnit::Meter);

        $result = $m->round(2);

        self::assertSame('123.46', $result->getValue()->value());
    }

    public function testFloor(): void
    {
        $m = new TestQuantity(NumberFactory::create('123.999'), MetricLengthUnit::Meter);

        $result = $m->floor();

        self::assertSame('123', $result->getValue()->value());
    }

    public function testCeil(): void
    {
        $m = new TestQuantity(NumberFactory::create('123.001'), MetricLengthUnit::Meter);

        $result = $m->ceil();

        self::assertSame('124', $result->getValue()->value());
    }

    public function testRatio(): void
    {
        $m200 = new TestQuantity(NumberFactory::create('200'), MetricLengthUnit::Meter);
        $m50 = new TestQuantity(NumberFactory::create('50'), MetricLengthUnit::Meter);

        $ratio = $m200->ratio($m50);

        self::assertEqualsWithDelta(4.0, (float) $ratio->value(), 0.0001);
    }

    // ConvertibleTrait tests

    public function testTo(): void
    {
        $m1000 = new TestQuantity(NumberFactory::create('1000'), MetricLengthUnit::Meter);

        $result = $m1000->to(MetricLengthUnit::Kilometer);

        self::assertEqualsWithDelta(1.0, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Kilometer, $result->getUnit());
    }

    public function testToSameUnit(): void
    {
        $m100 = new TestQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);

        $result = $m100->to(MetricLengthUnit::Meter);

        self::assertEqualsWithDelta(100.0, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testFluentChaining(): void
    {
        $m100 = new TestQuantity(NumberFactory::create('100'), MetricLengthUnit::Meter);
        $m50 = new TestQuantity(NumberFactory::create('50'), MetricLengthUnit::Meter);

        // Chain multiple operations
        $sum = $m100->add($m50);
        self::assertInstanceOf(TestQuantity::class, $sum);

        $multiplied = $sum->multiplyBy(NumberFactory::create('2'));
        self::assertInstanceOf(TestQuantity::class, $multiplied);

        $result = $multiplied->to(MetricLengthUnit::Kilometer);

        // (100 + 50) * 2 = 300m = 0.3km
        self::assertEqualsWithDelta(0.3, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Kilometer, $result->getUnit());
    }
}
