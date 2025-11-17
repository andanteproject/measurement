<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Math\Adapter;

use Andante\Measurement\Contract\Math\MathAdapterInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Exception\InvalidOperationException;
use Andante\Measurement\Math\Number;
use Andante\Measurement\Math\RoundingMode;
use PHPUnit\Framework\TestCase;

/**
 * Abstract test case for MathAdapter implementations.
 *
 * This ensures all adapters behave identically, validating our abstraction.
 * Concrete test classes must implement createAdapter() to provide the adapter under test.
 */
abstract class MathAdapterTestCase extends TestCase
{
    protected MathAdapterInterface $adapter;

    abstract protected function createAdapter(): MathAdapterInterface;

    protected function setUp(): void
    {
        $this->adapter = $this->createAdapter();
    }

    protected function number(string|int|float $value): NumberInterface
    {
        return new Number($value, $this->adapter);
    }

    public function testAddition(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('5.25');

        $result = $this->adapter->add($a, $b);

        self::assertEquals('15.75', $result->value());
    }

    public function testAdditionWithNegativeNumbers(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('-5.25');

        $result = $this->adapter->add($a, $b);

        self::assertEquals('5.25', $result->value());
    }

    public function testSubtraction(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('5.25');

        $result = $this->adapter->subtract($a, $b);

        self::assertEquals('5.25', $result->value());
    }

    public function testSubtractionResultingInNegative(): void
    {
        $a = $this->number('5.25');
        $b = $this->number('10.5');

        $result = $this->adapter->subtract($a, $b);

        self::assertEquals('-5.25', $result->value());
    }

    public function testMultiplication(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('2');

        $result = $this->adapter->multiply($a, $b);

        // Allow trailing zeros
        self::assertEqualsWithDelta(21.0, (float) $result->value(), 0.0001);
    }

    public function testMultiplicationWithDecimals(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('1.5');

        $result = $this->adapter->multiply($a, $b);

        self::assertEquals('15.75', $result->value());
    }

    public function testDivision(): void
    {
        $a = $this->number('10');
        $b = $this->number('2');

        $result = $this->adapter->divide($a, $b, 2, RoundingMode::HalfUp);

        self::assertEquals('5.00', $result->value());
    }

    public function testDivisionWithRemainder(): void
    {
        $a = $this->number('10');
        $b = $this->number('3');

        $result = $this->adapter->divide($a, $b, 5, RoundingMode::HalfUp);

        self::assertEquals('3.33333', $result->value());
    }

    public function testDivisionByZeroThrowsException(): void
    {
        $this->expectException(InvalidOperationException::class);
        $this->expectExceptionMessage('Division by zero');

        $a = $this->number('10');
        $b = $this->number('0');

        $this->adapter->divide($a, $b, 2, RoundingMode::HalfUp);
    }

    public function testPowerWithIntegerExponent(): void
    {
        $base = $this->number('2');
        $exponent = $this->number('3');

        $result = $this->adapter->power($base, $exponent);

        // Allow trailing zeros
        self::assertEqualsWithDelta(8.0, (float) $result->value(), 0.0001);
    }

    public function testPowerWithNegativeExponent(): void
    {
        $base = $this->number('2');
        $exponent = $this->number('-2');

        $result = $this->adapter->power($base, $exponent);

        // Result should be 0.25
        self::assertEqualsWithDelta(0.25, (float) $result->value(), 0.0001);
    }

    public function testSquareRoot(): void
    {
        $value = $this->number('16');

        $result = $this->adapter->sqrt($value, 2);

        self::assertEquals('4.00', $result->value());
    }

    public function testSquareRootWithDecimals(): void
    {
        $value = $this->number('2');

        $result = $this->adapter->sqrt($value, 5);

        // sqrt(2) ≈ 1.41421
        self::assertEqualsWithDelta(1.41421, (float) $result->value(), 0.00001);
    }

    public function testSquareRootOfZero(): void
    {
        $value = $this->number('0');

        $result = $this->adapter->sqrt($value, 2);

        // Allow trailing zeros
        self::assertEqualsWithDelta(0.0, (float) $result->value(), 0.0001);
    }

    public function testSquareRootOfNegativeThrowsException(): void
    {
        $this->expectException(InvalidOperationException::class);
        $this->expectExceptionMessage('Cannot calculate square root of negative number');

        $value = $this->number('-4');

        $this->adapter->sqrt($value, 2);
    }

    public function testAbsoluteValueOfPositive(): void
    {
        $value = $this->number('10.5');

        $result = $this->adapter->abs($value);

        self::assertEquals('10.5', $result->value());
    }

    public function testAbsoluteValueOfNegative(): void
    {
        $value = $this->number('-10.5');

        $result = $this->adapter->abs($value);

        self::assertEquals('10.5', $result->value());
    }

    public function testNegatePositive(): void
    {
        $value = $this->number('10.5');

        $result = $this->adapter->negate($value);

        self::assertEquals('-10.5', $result->value());
    }

    public function testNegateNegative(): void
    {
        $value = $this->number('-10.5');

        $result = $this->adapter->negate($value);

        self::assertEquals('10.5', $result->value());
    }

    public function testCompareEqual(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('10.5');

        $result = $this->adapter->compare($a, $b);

        self::assertEquals(0, $result);
    }

    public function testCompareLessThan(): void
    {
        $a = $this->number('5.5');
        $b = $this->number('10.5');

        $result = $this->adapter->compare($a, $b);

        self::assertEquals(-1, $result);
    }

    public function testCompareGreaterThan(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('5.5');

        $result = $this->adapter->compare($a, $b);

        self::assertEquals(1, $result);
    }

    public function testEqualsWithoutTolerance(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('10.5');

        $result = $this->adapter->equals($a, $b);

        self::assertTrue($result);
    }

    public function testNotEqualsWithoutTolerance(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('10.50001');

        $result = $this->adapter->equals($a, $b);

        self::assertFalse($result);
    }

    public function testEqualsWithTolerance(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('10.50001');
        $tolerance = $this->number('0.001');

        $result = $this->adapter->equals($a, $b, $tolerance);

        self::assertTrue($result);
    }

    public function testNotEqualsWithTolerance(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('10.51');
        $tolerance = $this->number('0.001');

        $result = $this->adapter->equals($a, $b, $tolerance);

        self::assertFalse($result);
    }

    public function testRoundHalfUp(): void
    {
        $value = $this->number('10.555');

        $result = $this->adapter->round($value, 2, RoundingMode::HalfUp);

        self::assertEquals('10.56', $result->value());
    }

    public function testRoundHalfDown(): void
    {
        $value = $this->number('10.555');

        $result = $this->adapter->round($value, 2, RoundingMode::HalfDown);

        self::assertEquals('10.55', $result->value());
    }

    public function testRoundCeiling(): void
    {
        $value = $this->number('10.551');

        $result = $this->adapter->round($value, 2, RoundingMode::Ceiling);

        self::assertEquals('10.56', $result->value());
    }

    public function testRoundFloor(): void
    {
        $value = $this->number('10.559');

        $result = $this->adapter->round($value, 2, RoundingMode::Floor);

        self::assertEquals('10.55', $result->value());
    }

    public function testRoundDown(): void
    {
        $value = $this->number('10.559');

        $result = $this->adapter->round($value, 2, RoundingMode::Down);

        self::assertEquals('10.55', $result->value());
    }

    public function testRoundUp(): void
    {
        $value = $this->number('10.551');

        $result = $this->adapter->round($value, 2, RoundingMode::Up);

        self::assertEquals('10.56', $result->value());
    }

    public function testIsZeroTrue(): void
    {
        $value = $this->number('0');

        $result = $this->adapter->isZero($value);

        self::assertTrue($result);
    }

    public function testIsZeroFalse(): void
    {
        $value = $this->number('0.001');

        $result = $this->adapter->isZero($value);

        self::assertFalse($result);
    }

    public function testIsPositiveTrue(): void
    {
        $value = $this->number('10.5');

        $result = $this->adapter->isPositive($value);

        self::assertTrue($result);
    }

    public function testIsPositiveFalse(): void
    {
        $value = $this->number('-10.5');

        $result = $this->adapter->isPositive($value);

        self::assertFalse($result);
    }

    public function testIsPositiveFalseForZero(): void
    {
        $value = $this->number('0');

        $result = $this->adapter->isPositive($value);

        self::assertFalse($result);
    }

    public function testIsNegativeTrue(): void
    {
        $value = $this->number('-10.5');

        $result = $this->adapter->isNegative($value);

        self::assertTrue($result);
    }

    public function testIsNegativeFalse(): void
    {
        $value = $this->number('10.5');

        $result = $this->adapter->isNegative($value);

        self::assertFalse($result);
    }

    public function testIsNegativeFalseForZero(): void
    {
        $value = $this->number('0');

        $result = $this->adapter->isNegative($value);

        self::assertFalse($result);
    }

    public function testMin(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('5.5');

        $result = $this->adapter->min($a, $b);

        self::assertEquals('5.5', $result->value());
    }

    public function testMinWithNegative(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('-5.5');

        $result = $this->adapter->min($a, $b);

        self::assertEquals('-5.5', $result->value());
    }

    public function testMax(): void
    {
        $a = $this->number('10.5');
        $b = $this->number('5.5');

        $result = $this->adapter->max($a, $b);

        self::assertEquals('10.5', $result->value());
    }

    public function testMaxWithNegative(): void
    {
        $a = $this->number('-10.5');
        $b = $this->number('-5.5');

        $result = $this->adapter->max($a, $b);

        self::assertEquals('-5.5', $result->value());
    }
}
