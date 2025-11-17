<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Math;

/**
 * Interface for rounding modes used in numeric operations.
 *
 * This interface allows users to implement custom rounding modes
 * while maintaining compatibility with the library's built-in RoundingMode enum.
 */
interface RoundingModeInterface
{
    /**
     * Get the integer value representing this rounding mode.
     *
     * Standard values (used by the built-in RoundingMode enum):
     * - 1: Ceiling (towards +∞)
     * - 2: Floor (towards -∞)
     * - 3: Down (towards zero)
     * - 4: Up (away from zero)
     * - 5: Half Up (round 0.5 up)
     * - 6: Half Down (round 0.5 down)
     * - 7: Half Even (banker's rounding)
     * - 8: Half Odd (round 0.5 to odd)
     *
     * Custom implementations may use values >= 100 to avoid conflicts.
     */
    public function value(): int;
}
