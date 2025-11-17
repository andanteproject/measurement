<?php

declare(strict_types=1);

namespace Andante\Measurement\Exception;

/**
 * Thrown when unit conversion fails.
 *
 * Examples:
 * - Converting between incompatible dimensions
 * - Missing conversion factor
 * - Conversion precision issues
 */
final class ConversionException extends QuantityException
{
}
