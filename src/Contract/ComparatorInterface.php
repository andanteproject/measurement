<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Contract\Math\NumberInterface;

/**
 * Interface for quantity comparison operations.
 */
interface ComparatorInterface
{
    /**
     * Compare two quantities.
     *
     * @return int -1 if $a < $b, 0 if $a == $b, 1 if $a > $b
     */
    public function compare(QuantityInterface $a, QuantityInterface $b): int;

    /**
     * Check if two quantities are equal (within optional tolerance).
     */
    public function equals(QuantityInterface $a, QuantityInterface $b, ?NumberInterface $tolerance = null): bool;

    /**
     * Check if first quantity is greater than second.
     */
    public function isGreaterThan(QuantityInterface $a, QuantityInterface $b): bool;

    /**
     * Check if first quantity is greater than or equal to second.
     */
    public function isGreaterThanOrEqual(QuantityInterface $a, QuantityInterface $b): bool;

    /**
     * Check if first quantity is less than second.
     */
    public function isLessThan(QuantityInterface $a, QuantityInterface $b): bool;

    /**
     * Check if first quantity is less than or equal to second.
     */
    public function isLessThanOrEqual(QuantityInterface $a, QuantityInterface $b): bool;

    /**
     * Get the minimum of multiple quantities.
     */
    public function min(QuantityInterface $first, QuantityInterface ...$quantities): QuantityInterface;

    /**
     * Get the maximum of multiple quantities.
     */
    public function max(QuantityInterface $first, QuantityInterface ...$quantities): QuantityInterface;

    /**
     * Check if a quantity is between two bounds (inclusive).
     */
    public function isBetween(QuantityInterface $value, QuantityInterface $min, QuantityInterface $max): bool;

    /**
     * Clamp a quantity to a range.
     */
    public function clamp(QuantityInterface $value, QuantityInterface $min, QuantityInterface $max): QuantityInterface;

    /**
     * Check if a quantity's value is zero.
     */
    public function isZero(QuantityInterface $quantity): bool;

    /**
     * Check if a quantity's value is positive (greater than zero).
     */
    public function isPositive(QuantityInterface $quantity): bool;

    /**
     * Check if a quantity's value is negative (less than zero).
     */
    public function isNegative(QuantityInterface $quantity): bool;
}
