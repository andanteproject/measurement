<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Mass;

/**
 * Interface for imperial mass quantities.
 *
 * This interface represents mass measurements in the imperial system
 * (pounds, ounces, stone, etc.). Use this for type-hinting
 * when you specifically need imperial masses.
 *
 * Example:
 * ```php
 * function processImperialMass(ImperialMassInterface $mass): void
 * {
 *     // Only accepts imperial units (lb, oz, st, etc.)
 * }
 * ```
 */
interface ImperialMassInterface extends MassInterface
{
}
