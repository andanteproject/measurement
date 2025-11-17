<?php

declare(strict_types=1);

namespace Andante\Measurement\Exception;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;

/**
 * Thrown when an invalid or unknown unit is encountered.
 *
 * Examples:
 * - Attempting to use a non-existent unit class
 * - Invalid unit symbol in parsing
 * - Unit configuration errors
 * - Wrong unit type for quantity class
 */
final class InvalidUnitException extends QuantityException
{
    /**
     * Create exception for wrong exact unit (unit-specific classes like Meter, Foot).
     *
     * @param class-string $quantityClass
     */
    public static function forInvalidUnit(
        UnitInterface $provided,
        UnitInterface $expected,
        string $quantityClass,
    ): self {
        return new self(\sprintf(
            'Cannot create %s with unit "%s", expected "%s"',
            $quantityClass,
            $provided->name(),
            $expected->name(),
        ));
    }

    /**
     * Create exception for wrong unit type (mid-level classes like MetricLength).
     *
     * @param class-string $expectedType
     * @param class-string $quantityClass
     */
    public static function forInvalidUnitType(
        UnitInterface $provided,
        string $expectedType,
        string $quantityClass,
    ): self {
        return new self(\sprintf(
            'Cannot create %s with unit of type %s, expected %s',
            $quantityClass,
            $provided::class,
            $expectedType,
        ));
    }

    /**
     * Create exception for wrong dimension (generic classes like Length).
     *
     * @param class-string $quantityClass
     */
    public static function forInvalidDimension(
        UnitInterface $provided,
        DimensionInterface $expected,
        string $quantityClass,
    ): self {
        return new self(\sprintf(
            'Cannot create %s with unit "%s" (dimension: %s), expected dimension: %s',
            $quantityClass,
            $provided->name(),
            $provided->dimension()::class,
            $expected::class,
        ));
    }
}
