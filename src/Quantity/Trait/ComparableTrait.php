<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Trait;

use Andante\Measurement\Comparator\Comparator;
use Andante\Measurement\Contract\ComparatorInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\QuantityInterface;

/**
 * Trait that implements ComparableInterface using the global Comparator service.
 *
 * This trait expects the class to implement QuantityInterface.
 *
 * @phpstan-require-implements QuantityInterface
 */
trait ComparableTrait
{
    private static ?ComparatorInterface $comparator = null;

    /**
     * Get the Comparator instance to use.
     */
    private static function getComparator(): ComparatorInterface
    {
        return self::$comparator ?? Comparator::global();
    }

    /**
     * Set a custom Comparator instance.
     *
     * Useful for testing or custom configurations.
     */
    public static function setComparator(?ComparatorInterface $comparator): void
    {
        self::$comparator = $comparator;
    }

    /**
     * Reset the Comparator to use the default global instance.
     */
    public static function resetComparator(): void
    {
        self::$comparator = null;
    }

    /**
     * @see ComparableInterface::compareTo()
     */
    public function compareTo(QuantityInterface $other): int
    {
        return self::getComparator()->compare($this, $other);
    }

    /**
     * @see ComparableInterface::equals()
     */
    public function equals(QuantityInterface $other, ?NumberInterface $tolerance = null): bool
    {
        return self::getComparator()->equals($this, $other, $tolerance);
    }

    /**
     * @see ComparableInterface::isGreaterThan()
     */
    public function isGreaterThan(QuantityInterface $other): bool
    {
        return self::getComparator()->isGreaterThan($this, $other);
    }

    /**
     * @see ComparableInterface::isGreaterThanOrEqual()
     */
    public function isGreaterThanOrEqual(QuantityInterface $other): bool
    {
        return self::getComparator()->isGreaterThanOrEqual($this, $other);
    }

    /**
     * @see ComparableInterface::isLessThan()
     */
    public function isLessThan(QuantityInterface $other): bool
    {
        return self::getComparator()->isLessThan($this, $other);
    }

    /**
     * @see ComparableInterface::isLessThanOrEqual()
     */
    public function isLessThanOrEqual(QuantityInterface $other): bool
    {
        return self::getComparator()->isLessThanOrEqual($this, $other);
    }

    /**
     * @see ComparableInterface::isZero()
     */
    public function isZero(): bool
    {
        return self::getComparator()->isZero($this);
    }

    /**
     * @see ComparableInterface::isPositive()
     */
    public function isPositive(): bool
    {
        return self::getComparator()->isPositive($this);
    }

    /**
     * @see ComparableInterface::isNegative()
     */
    public function isNegative(): bool
    {
        return self::getComparator()->isNegative($this);
    }

    /**
     * @see ComparableInterface::isBetween()
     */
    public function isBetween(QuantityInterface $min, QuantityInterface $max): bool
    {
        return self::getComparator()->isBetween($this, $min, $max);
    }

    /**
     * @see ComparableInterface::min()
     */
    public function min(QuantityInterface ...$others): QuantityInterface
    {
        return self::getComparator()->min($this, ...$others);
    }

    /**
     * @see ComparableInterface::max()
     */
    public function max(QuantityInterface ...$others): QuantityInterface
    {
        return self::getComparator()->max($this, ...$others);
    }

    /**
     * @see ComparableInterface::clamp()
     */
    public function clamp(QuantityInterface $min, QuantityInterface $max): QuantityInterface
    {
        return self::getComparator()->clamp($this, $min, $max);
    }
}
