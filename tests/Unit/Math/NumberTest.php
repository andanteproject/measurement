<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Math;

use Andante\Measurement\Math\Adapter\BrickMathAdapter;
use Andante\Measurement\Math\Number;
use Andante\Measurement\Math\RoundingMode;
use PHPUnit\Framework\TestCase;

/**
 * Test case for Number class.
 *
 * These tests ensure the Number class correctly delegates to its MathAdapter
 * and provides a convenient fluent interface for numeric operations.
 */
final class NumberTest extends TestCase
{
    private BrickMathAdapter $adapter;

    protected function setUp(): void
    {
        $this->adapter = new BrickMathAdapter();
    }

    private function number(string|int|float $value): Number
    {
        return new Number($value, $this->adapter);
    }

    public function testConstructionFromString(): void
    {
        $number = $this->number('10.5');

        self::assertEquals('10.5', $number->value());
        self::assertEquals('10.5', (string) $number);
    }

    public function testConstructionFromInt(): void
    {
        $number = $this->number(42);

        self::assertEquals('42', $number->value());
    }

    public function testConstructionFromFloat(): void
    {
        $number = $this->number(10.5);

        self::assertEquals('10.5', $number->value());
    }

    public function testAddMethod(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('5.25');

        $result = $a->add($b);

        self::assertEquals('15.75', $result->value());
        // Original numbers are immutable
        self::assertEquals('10.5', $a->value());
        self::assertEquals('5.25', $b->value());
    }

    public function testSubtractMethod(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('5.25');

        $result = $a->subtract($b);

        self::assertEquals('5.25', $result->value());
    }

    public function testMultiplyMethod(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('2');

        $result = $a->multiply($b);

        self::assertEqualsWithDelta(21.0, (float) $result->value(), 0.0001);
    }

    public function testDivideMethod(): void
    {
        $a = $this->number('10');
        $b = $this->number('3');

        $result = $a->divide($b, 5);

        self::assertEquals('3.33333', $result->value());
    }

    public function testDivideWithRoundingMode(): void
    {
        $a = $this->number('10');
        $b = $this->number('3');

        $result = $a->divide($b, 2, RoundingMode::HalfUp);

        self::assertEquals('3.33', $result->value());
    }

    public function testPowerMethod(): void
    {
        $base = $this->number('2');
        $exponent = $this->number('3');

        $result = $base->power($exponent);

        self::assertEqualsWithDelta(8.0, (float) $result->value(), 0.0001);
    }

    public function testSqrtMethod(): void
    {
        $value = $this->number('16');

        $result = $value->sqrt(2);

        self::assertEquals('4.00', $result->value());
    }

    public function testAbsMethod(): void
    {
        $negative = $this->number('-10.5');

        $result = $negative->abs();

        self::assertEquals('10.5', $result->value());
    }

    public function testNegateMethod(): void
    {
        $positive = $this->number('10.5');

        $result = $positive->negate();

        self::assertEquals('-10.5', $result->value());
    }

    public function testCompareToEqual(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('10.5');

        self::assertEquals(0, $a->compareTo($b));
    }

    public function testCompareToLessThan(): void
    {
        $a = $this->number('5.5');
        $b = $this->number('10.5');

        self::assertEquals(-1, $a->compareTo($b));
    }

    public function testCompareToGreaterThan(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('5.5');

        self::assertEquals(1, $a->compareTo($b));
    }

    public function testEquals(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('10.5');

        self::assertTrue($a->equals($b));
    }

    public function testNotEquals(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('10.6');

        self::assertFalse($a->equals($b));
    }

    public function testEqualsWithTolerance(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('10.50001');
        $tolerance = $this->number('0.001');

        self::assertTrue($a->equals($b, $tolerance));
    }

    public function testIsZero(): void
    {
        $zero = $this->number('0');

        self::assertTrue($zero->isZero());
    }

    public function testIsNotZero(): void
    {
        $nonZero = $this->number('0.001');

        self::assertFalse($nonZero->isZero());
    }

    public function testIsPositive(): void
    {
        $positive = $this->number('10.5');

        self::assertTrue($positive->isPositive());
    }

    public function testIsNotPositive(): void
    {
        $negative = $this->number('-10.5');

        self::assertFalse($negative->isPositive());
    }

    public function testIsNegative(): void
    {
        $negative = $this->number('-10.5');

        self::assertTrue($negative->isNegative());
    }

    public function testIsNotNegative(): void
    {
        $positive = $this->number('10.5');

        self::assertFalse($positive->isNegative());
    }

    public function testRoundMethod(): void
    {
        $value = $this->number('10.555');

        $result = $value->round(2);

        self::assertEquals('10.56', $result->value());
    }

    public function testRoundWithMode(): void
    {
        $value = $this->number('10.555');

        $result = $value->round(2, RoundingMode::HalfDown);

        self::assertEquals('10.55', $result->value());
    }

    public function testMinMethod(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('5.5');

        $result = $a->min($b);

        self::assertEquals('5.5', $result->value());
    }

    public function testMaxMethod(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('5.5');

        $result = $a->max($b);

        self::assertEquals('10.5', $result->value());
    }

    public function testImmutability(): void
    {
        $original = $this->number('10');

        $added = $original->add($this->number('5'));
        $multiplied = $original->multiply($this->number('2'));
        $negated = $original->negate();

        // Original is unchanged
        self::assertEquals('10', $original->value());

        // Each operation returns a new instance
        self::assertEquals('15', $added->value());
        self::assertEqualsWithDelta(20.0, (float) $multiplied->value(), 0.0001);
        self::assertEquals('-10', $negated->value());
    }

    public function testFluentInterface(): void
    {
        $result = $this->number('10')
            ->add($this->number('5'))
            ->multiply($this->number('2'))
            ->subtract($this->number('10'))
            ->divide($this->number('2'), 2);

        // (10 + 5) * 2 - 10 / 2 = 15 * 2 - 10 / 2 = 30 - 10 / 2 = 20 / 2 = 10
        self::assertEquals('10.00', $result->value());
    }
}
