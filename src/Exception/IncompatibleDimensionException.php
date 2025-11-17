<?php

declare(strict_types=1);

namespace Andante\Measurement\Exception;

/**
 * Thrown when attempting operations on incompatible dimensions.
 *
 * Examples:
 * - Adding length to mass: 5m + 10kg
 * - Comparing energy to power: 100J > 50W
 * - Converting between incompatible units: meters to kilograms
 *
 * This exception indicates a dimensional analysis violation.
 */
final class IncompatibleDimensionException extends QuantityException
{
}
