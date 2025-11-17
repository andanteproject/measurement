<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Exception\InvalidOperationException;

/**
 * Interface for quantities that can be compared to other quantities.
 *
 * Implementations should delegate to the Comparator service for the actual
 * comparison logic, ensuring consistent behavior across the library.
 *
 * Example:
 * ```php
 * $meter100 = Meter::from(NumberFactory::create('100'));
 * $km1 = Kilometer::from(NumberFactory::create('1'));
 *
 * $meter100->isLessThan($km1); // true (100m < 1000m)
 * $meter100->equals($km1); // false
 * ```
 */
interface ComparableInterface
{
    /**
     * Compare this quantity to another.
     *
     * @return int -1 if this < other, 0 if equal, 1 if this > other
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function compareTo(QuantityInterface $other): int;

    /**
     * Check if this quantity equals another (within optional tolerance).
     *
     * Returns false (not exception) if dimensions differ.
     *
     * @param QuantityInterface    $other     The quantity to compare with
     * @param NumberInterface|null $tolerance Optional tolerance for comparison
     */
    public function equals(QuantityInterface $other, ?NumberInterface $tolerance = null): bool;

    /**
     * Check if this quantity is greater than another.
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function isGreaterThan(QuantityInterface $other): bool;

    /**
     * Check if this quantity is greater than or equal to another.
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function isGreaterThanOrEqual(QuantityInterface $other): bool;

    /**
     * Check if this quantity is less than another.
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function isLessThan(QuantityInterface $other): bool;

    /**
     * Check if this quantity is less than or equal to another.
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function isLessThanOrEqual(QuantityInterface $other): bool;

    /**
     * Check if this quantity's value is zero.
     */
    public function isZero(): bool;

    /**
     * Check if this quantity's value is positive.
     */
    public function isPositive(): bool;

    /**
     * Check if this quantity's value is negative.
     */
    public function isNegative(): bool;

    /**
     * Check if this quantity is between two bounds (inclusive).
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function isBetween(QuantityInterface $min, QuantityInterface $max): bool;

    /**
     * Get the minimum between this quantity and others.
     *
     * @param QuantityInterface ...$others One or more quantities to compare with
     *
     * @return QuantityInterface The smallest quantity
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function min(QuantityInterface ...$others): QuantityInterface;

    /**
     * Get the maximum between this quantity and others.
     *
     * @param QuantityInterface ...$others One or more quantities to compare with
     *
     * @return QuantityInterface The largest quantity
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function max(QuantityInterface ...$others): QuantityInterface;

    /**
     * Clamp this quantity to a range.
     *
     * Returns $min if this < $min, $max if this > $max, otherwise this.
     *
     * @param QuantityInterface $min The minimum bound
     * @param QuantityInterface $max The maximum bound
     *
     * @return QuantityInterface The clamped quantity
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function clamp(QuantityInterface $min, QuantityInterface $max): QuantityInterface;
}
