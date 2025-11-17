<?php

declare(strict_types=1);

namespace Andante\Measurement\Math;

use Andante\Measurement\Contract\Math\NumberInterface;

/**
 * Factory for creating NumericValue instances.
 *
 * This factory uses the configured MathAdapter from MathAdapterFactory
 * to create immutable numeric value objects.
 *
 * Example:
 * ```php
 * // Auto-uses configured MathAdapter
 * $value = NumericValueFactory::create('5.5');
 * $result = $value->add('3.2');  // Returns new NumericValue
 * ```
 */
final class NumberFactory
{
    /**
     * Create a NumericValue from a string or numeric type.
     *
     * @param numeric-string|int|float $value The value to wrap
     *
     * @return Number Immutable numeric value object
     *
     * @throws \Andante\Measurement\Exception\InvalidOperationException If no math adapter configured
     */
    public static function create(string|int|float $value): NumberInterface
    {
        $math = MathAdapterFactory::getAdapter();

        $stringValue = match (true) {
            \is_string($value) => $value,
            \is_int($value) => (string) $value,
            \is_float($value) => (string) $value,
        };

        return new Number($stringValue, $math);
    }

    /**
     * Create a NumericValue from a string, int, or float (alias for create).
     *
     * @param numeric-string|int|float $value The value to wrap
     */
    public static function of(string|int|float $value): NumberInterface
    {
        return self::create($value);
    }

    /**
     * Create a zero value.
     */
    public static function zero(): NumberInterface
    {
        return self::create('0');
    }

    /**
     * Create a one value.
     */
    public static function one(): NumberInterface
    {
        return self::create('1');
    }
}
