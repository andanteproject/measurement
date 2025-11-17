<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Math;

use Andante\Measurement\Math\RoundingMode;

/**
 * Represents an arbitrary-precision numeric value.
 *
 * This interface wraps numeric operations, delegating to the configured
 * MathAdapter. It provides a value object approach to numeric calculations.
 *
 * All operations work only with other NumericValueInterface instances,
 * ensuring type safety and preventing scalar value mixing.
 */
interface NumberInterface
{
    /**
     * Get the internal string representation of this value.
     *
     * This is used internally for math operations and should not
     * be relied upon for display purposes. Use a formatter instead.
     */
    public function value(): string;

    /**
     * Get string representation for display.
     */
    public function __toString(): string;

    /**
     * Add another numeric value.
     */
    public function add(NumberInterface $other): NumberInterface;

    /**
     * Subtract another numeric value.
     */
    public function subtract(NumberInterface $other): NumberInterface;

    /**
     * Multiply by another numeric value.
     */
    public function multiply(NumberInterface $other): NumberInterface;

    /**
     * Divide by another numeric value.
     *
     * @param NumberInterface       $other        The divisor
     * @param int                   $scale        Number of decimal places in result
     * @param RoundingModeInterface $roundingMode Rounding mode (default: HalfUp)
     */
    public function divide(
        NumberInterface $other,
        int $scale,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): NumberInterface;

    /**
     * Raise to a power.
     */
    public function power(NumberInterface $exponent): NumberInterface;

    /**
     * Calculate square root.
     */
    public function sqrt(int $scale): NumberInterface;

    /**
     * Get absolute value.
     */
    public function abs(): NumberInterface;

    /**
     * Negate this value.
     */
    public function negate(): NumberInterface;

    /**
     * Compare to another numeric value.
     *
     * @return int -1 if less than, 0 if equal, 1 if greater than
     */
    public function compareTo(NumberInterface $other): int;

    /**
     * Check equality with optional tolerance.
     */
    public function equals(NumberInterface $other, ?NumberInterface $tolerance = null): bool;

    /**
     * Check if this value is zero.
     */
    public function isZero(): bool;

    /**
     * Check if this value is positive.
     */
    public function isPositive(): bool;

    /**
     * Check if this value is negative.
     */
    public function isNegative(): bool;

    /**
     * Round to specified precision.
     *
     * @param int                   $precision Number of decimal places
     * @param RoundingModeInterface $mode      Rounding mode (default: HalfUp)
     */
    public function round(
        int $precision,
        RoundingModeInterface $mode = RoundingMode::HalfUp,
    ): NumberInterface;

    /**
     * Get minimum of this and another value.
     */
    public function min(NumberInterface $other): NumberInterface;

    /**
     * Get maximum of this and another value.
     */
    public function max(NumberInterface $other): NumberInterface;
}
