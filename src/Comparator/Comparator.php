<?php

declare(strict_types=1);

namespace Andante\Measurement\Comparator;

use Andante\Measurement\Contract\ComparatorInterface;
use Andante\Measurement\Contract\ConverterInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Converter\Converter;
use Andante\Measurement\Exception\InvalidOperationException;

/**
 * Handles comparison operations between quantities.
 *
 * All comparison operations require quantities to be of the same dimension.
 * The comparator automatically converts the second quantity to the first
 * quantity's unit before comparing.
 *
 * Example:
 * ```php
 * $comparator = new Comparator();
 * $meter = Meter::from(NumberFactory::create('1000'));
 * $kilometer = Kilometer::from(NumberFactory::create('1'));
 *
 * $comparator->equals($meter, $kilometer); // true
 * $comparator->isGreaterThan($meter, $kilometer); // false
 * ```
 */
final class Comparator implements ComparatorInterface
{
    private ConverterInterface $converter;

    private static ?self $instance = null;

    /**
     * Get the global Comparator instance.
     */
    public static function global(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Set a custom global Comparator instance.
     *
     * @internal Primarily for testing
     */
    public static function setGlobal(self $comparator): void
    {
        self::$instance = $comparator;
    }

    /**
     * Reset the global Comparator.
     *
     * @internal Primarily for testing
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    /**
     * Create a new comparator instance.
     *
     * @param ConverterInterface|null $converter The converter to use for unit conversions.
     *                                           If null, uses the global Converter.
     */
    public function __construct(?ConverterInterface $converter = null)
    {
        $this->converter = $converter ?? Converter::global();
    }

    /**
     * Compare two quantities.
     *
     * @param QuantityInterface $a First quantity
     * @param QuantityInterface $b Second quantity
     *
     * @return int -1 if $a < $b, 0 if $a == $b, 1 if $a > $b
     *
     * @throws InvalidOperationException If quantities are from different dimensions
     */
    public function compare(QuantityInterface $a, QuantityInterface $b): int
    {
        $this->validateSameDimension($a, $b);

        // No conversion needed if same unit
        if ($a->getUnit() === $b->getUnit()) {
            return $a->getValue()->compareTo($b->getValue());
        }

        // Convert b's value to a's unit
        $bValueConverted = $this->converter->convert(
            $b->getValue(),
            $b->getUnit(),
            $a->getUnit(),
        );

        return $a->getValue()->compareTo($bValueConverted);
    }

    /**
     * Check if two quantities are equal (within optional tolerance).
     *
     * @param QuantityInterface    $a         First quantity
     * @param QuantityInterface    $b         Second quantity
     * @param NumberInterface|null $tolerance Optional tolerance for comparison
     *
     * @return bool True if quantities are equal
     */
    public function equals(QuantityInterface $a, QuantityInterface $b, ?NumberInterface $tolerance = null): bool
    {
        // Different dimensions are never equal
        if (!$a->getUnit()->dimension()->isCompatibleWith($b->getUnit()->dimension())) {
            return false;
        }

        // No conversion needed if same unit
        if ($a->getUnit() === $b->getUnit()) {
            return $a->getValue()->equals($b->getValue(), $tolerance);
        }

        // Convert b's value to a's unit
        $bValueConverted = $this->converter->convert(
            $b->getValue(),
            $b->getUnit(),
            $a->getUnit(),
        );

        return $a->getValue()->equals($bValueConverted, $tolerance);
    }

    /**
     * Check if first quantity is greater than second.
     *
     * @throws InvalidOperationException If quantities are from different dimensions
     */
    public function isGreaterThan(QuantityInterface $a, QuantityInterface $b): bool
    {
        return 0 < $this->compare($a, $b);
    }

    /**
     * Check if first quantity is greater than or equal to second.
     *
     * @throws InvalidOperationException If quantities are from different dimensions
     */
    public function isGreaterThanOrEqual(QuantityInterface $a, QuantityInterface $b): bool
    {
        return 0 <= $this->compare($a, $b);
    }

    /**
     * Check if first quantity is less than second.
     *
     * @throws InvalidOperationException If quantities are from different dimensions
     */
    public function isLessThan(QuantityInterface $a, QuantityInterface $b): bool
    {
        return 0 > $this->compare($a, $b);
    }

    /**
     * Check if first quantity is less than or equal to second.
     *
     * @throws InvalidOperationException If quantities are from different dimensions
     */
    public function isLessThanOrEqual(QuantityInterface $a, QuantityInterface $b): bool
    {
        return 0 >= $this->compare($a, $b);
    }

    /**
     * Get the minimum of multiple quantities.
     *
     * Returns the quantity that is smallest among all provided quantities.
     *
     * @param QuantityInterface $first         First quantity (at least one required)
     * @param QuantityInterface ...$quantities Additional quantities to compare
     *
     * @return QuantityInterface The smallest quantity
     *
     * @throws InvalidOperationException If quantities are from different dimensions
     */
    public function min(QuantityInterface $first, QuantityInterface ...$quantities): QuantityInterface
    {
        $min = $first;

        foreach ($quantities as $quantity) {
            if (0 > $this->compare($quantity, $min)) {
                $min = $quantity;
            }
        }

        return $min;
    }

    /**
     * Get the maximum of multiple quantities.
     *
     * Returns the quantity that is largest among all provided quantities.
     *
     * @param QuantityInterface $first         First quantity (at least one required)
     * @param QuantityInterface ...$quantities Additional quantities to compare
     *
     * @return QuantityInterface The largest quantity
     *
     * @throws InvalidOperationException If quantities are from different dimensions
     */
    public function max(QuantityInterface $first, QuantityInterface ...$quantities): QuantityInterface
    {
        $max = $first;

        foreach ($quantities as $quantity) {
            if (0 < $this->compare($quantity, $max)) {
                $max = $quantity;
            }
        }

        return $max;
    }

    /**
     * Check if a quantity is between two bounds (inclusive).
     *
     * @param QuantityInterface $value The value to check
     * @param QuantityInterface $min   The lower bound
     * @param QuantityInterface $max   The upper bound
     *
     * @return bool True if $min <= $value <= $max
     *
     * @throws InvalidOperationException If quantities are from different dimensions
     */
    public function isBetween(QuantityInterface $value, QuantityInterface $min, QuantityInterface $max): bool
    {
        return $this->isGreaterThanOrEqual($value, $min) && $this->isLessThanOrEqual($value, $max);
    }

    /**
     * Clamp a quantity to a range.
     *
     * Returns the value if it's within the range, otherwise returns the
     * nearest bound. The returned quantity is one of the input quantities.
     *
     * @param QuantityInterface $value The value to clamp
     * @param QuantityInterface $min   The lower bound
     * @param QuantityInterface $max   The upper bound
     *
     * @return QuantityInterface The clamped value ($min, $value, or $max)
     *
     * @throws InvalidOperationException If quantities are from different dimensions
     */
    public function clamp(QuantityInterface $value, QuantityInterface $min, QuantityInterface $max): QuantityInterface
    {
        if ($this->isLessThan($value, $min)) {
            return $min;
        }

        if ($this->isGreaterThan($value, $max)) {
            return $max;
        }

        return $value;
    }

    /**
     * Check if a quantity's value is zero.
     */
    public function isZero(QuantityInterface $quantity): bool
    {
        return $quantity->getValue()->isZero();
    }

    /**
     * Check if a quantity's value is positive (greater than zero).
     */
    public function isPositive(QuantityInterface $quantity): bool
    {
        return $quantity->getValue()->isPositive();
    }

    /**
     * Check if a quantity's value is negative (less than zero).
     */
    public function isNegative(QuantityInterface $quantity): bool
    {
        return $quantity->getValue()->isNegative();
    }

    /**
     * Validate that two quantities have the same dimension.
     *
     * @throws InvalidOperationException If dimensions differ
     */
    private function validateSameDimension(QuantityInterface $a, QuantityInterface $b): void
    {
        $aDimension = $a->getUnit()->dimension();
        $bDimension = $b->getUnit()->dimension();

        if (!$aDimension->isCompatibleWith($bDimension)) {
            throw new InvalidOperationException(\sprintf('Cannot compare different dimensions: %s and %s', $aDimension->getName(), $bDimension->getName()));
        }
    }
}
