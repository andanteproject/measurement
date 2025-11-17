<?php

declare(strict_types=1);

namespace Andante\Measurement\Exception;

/**
 * Thrown when an invalid mathematical or logical operation is attempted.
 *
 * Examples:
 * - Division by zero
 * - Square root of negative number
 * - Invalid exponent operations
 * - Overflow/underflow in calculations
 */
final class InvalidOperationException extends QuantityException
{
}
