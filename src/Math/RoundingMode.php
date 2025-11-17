<?php

declare(strict_types=1);

namespace Andante\Measurement\Math;

use Andante\Measurement\Contract\Math\RoundingModeInterface;

/**
 * Rounding mode for numeric operations.
 *
 * Defines how rounding should be performed when a result cannot be
 * represented exactly (e.g., division, rounding to precision).
 */
enum RoundingMode: int implements RoundingModeInterface
{
    /**
     * Round towards positive infinity.
     * Examples: 2.3 → 3, -2.3 → -2.
     */
    case Ceiling = 1;

    /**
     * Round towards negative infinity.
     * Examples: 2.7 → 2, -2.3 → -3.
     */
    case Floor = 2;

    /**
     * Round towards zero (truncate).
     * Examples: 2.7 → 2, -2.7 → -2.
     */
    case Down = 3;

    /**
     * Round away from zero.
     * Examples: 2.3 → 3, -2.3 → -3.
     */
    case Up = 4;

    /**
     * Round to nearest neighbor. If equidistant, round up.
     * Examples: 2.5 → 3, 2.4 → 2, -2.5 → -3.
     *
     * This is the most common rounding mode.
     */
    case HalfUp = 5;

    /**
     * Round to nearest neighbor. If equidistant, round down.
     * Examples: 2.5 → 2, 2.6 → 3, -2.5 → -2.
     */
    case HalfDown = 6;

    /**
     * Round to nearest neighbor. If equidistant, round towards even.
     * Examples: 2.5 → 2, 3.5 → 4, -2.5 → -2.
     *
     * Also known as "Banker's Rounding" - minimizes cumulative error.
     */
    case HalfEven = 7;

    /**
     * Round to nearest neighbor. If equidistant, round towards odd.
     * Examples: 2.5 → 3, 3.5 → 3, -2.5 → -3.
     */
    case HalfOdd = 8;

    /**
     * Get the integer value representing this rounding mode.
     */
    public function value(): int
    {
        return $this->value;
    }

    /**
     * Get a human-readable name for this rounding mode.
     */
    public function getName(): string
    {
        return match ($this) {
            self::Ceiling => 'Ceiling (towards +∞)',
            self::Floor => 'Floor (towards -∞)',
            self::Down => 'Down (towards zero)',
            self::Up => 'Up (away from zero)',
            self::HalfUp => 'Half Up (round 0.5 up)',
            self::HalfDown => 'Half Down (round 0.5 down)',
            self::HalfEven => 'Half Even (banker\'s rounding)',
            self::HalfOdd => 'Half Odd (round 0.5 to odd)',
        };
    }
}
