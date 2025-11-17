<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Math;

/**
 * Adapter interface for mathematical operations on numeric values.
 *
 * This abstraction allows the library to delegate all arithmetic
 * to a pluggable implementation, enabling:
 * - Different precision libraries (brick/math, bcmath, gmp, etc.)
 * - Custom implementations for specific use cases
 * - Easy testing with mock implementations
 *
 * All operations work with NumericValueInterface for type safety.
 */
interface MathAdapterInterface
{
    /**
     * Add two numeric values.
     *
     * @throws \Andante\Measurement\Exception\InvalidOperationException
     */
    public function add(NumberInterface $a, NumberInterface $b): NumberInterface;

    /**
     * Subtract two numeric values.
     *
     * @throws \Andante\Measurement\Exception\InvalidOperationException
     */
    public function subtract(NumberInterface $a, NumberInterface $b): NumberInterface;

    /**
     * Multiply two numeric values.
     *
     * @throws \Andante\Measurement\Exception\InvalidOperationException
     */
    public function multiply(NumberInterface $a, NumberInterface $b): NumberInterface;

    /**
     * Divide two numeric values.
     *
     * @param NumberInterface       $a            Dividend
     * @param NumberInterface       $b            Divisor
     * @param int                   $scale        Number of decimal places in the result
     * @param RoundingModeInterface $roundingMode Rounding mode
     *
     * @throws \Andante\Measurement\Exception\InvalidOperationException If divisor is zero
     */
    public function divide(
        NumberInterface $a,
        NumberInterface $b,
        int $scale,
        RoundingModeInterface $roundingMode,
    ): NumberInterface;

    /**
     * Raise a numeric value to a power.
     *
     * @param NumberInterface $base     The base number
     * @param NumberInterface $exponent The exponent (can be negative or fractional)
     *
     * @throws \Andante\Measurement\Exception\InvalidOperationException
     */
    public function power(NumberInterface $base, NumberInterface $exponent): NumberInterface;

    /**
     * Calculate the square root.
     *
     * @param NumberInterface $value The value
     * @param int             $scale Number of decimal places in the result
     *
     * @throws \Andante\Measurement\Exception\InvalidOperationException If value is negative
     */
    public function sqrt(NumberInterface $value, int $scale): NumberInterface;

    /**
     * Get the absolute value.
     */
    public function abs(NumberInterface $value): NumberInterface;

    /**
     * Negate a numeric value.
     */
    public function negate(NumberInterface $value): NumberInterface;

    /**
     * Compare two numeric values.
     *
     * @return int Returns -1 if a < b, 0 if a == b, 1 if a > b
     */
    public function compare(NumberInterface $a, NumberInterface $b): int;

    /**
     * Check if two numeric values are equal (with optional tolerance).
     *
     * @param NumberInterface      $a         First operand
     * @param NumberInterface      $b         Second operand
     * @param NumberInterface|null $tolerance Optional tolerance for comparison
     */
    public function equals(NumberInterface $a, NumberInterface $b, ?NumberInterface $tolerance = null): bool;

    /**
     * Round a numeric value to a specified precision.
     *
     * @param NumberInterface       $value     The value to round
     * @param int                   $precision Number of decimal places
     * @param RoundingModeInterface $mode      Rounding mode
     */
    public function round(
        NumberInterface $value,
        int $precision,
        RoundingModeInterface $mode,
    ): NumberInterface;

    /**
     * Check if a value is zero.
     */
    public function isZero(NumberInterface $value): bool;

    /**
     * Check if a value is negative.
     */
    public function isNegative(NumberInterface $value): bool;

    /**
     * Check if a value is positive.
     */
    public function isPositive(NumberInterface $value): bool;

    /**
     * Get the minimum of two numeric values.
     */
    public function min(NumberInterface $a, NumberInterface $b): NumberInterface;

    /**
     * Get the maximum of two numeric values.
     */
    public function max(NumberInterface $a, NumberInterface $b): NumberInterface;
}
