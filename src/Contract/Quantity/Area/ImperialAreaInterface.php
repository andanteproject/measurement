<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Area;

/**
 * Interface for imperial area quantities.
 *
 * This interface represents area measurements in the imperial/US system
 * (square feet, square yards, acres, etc.). Use this for type-hinting
 * when you specifically need imperial areas.
 *
 * Example:
 * ```php
 * function processImperialArea(ImperialAreaInterface $area): void
 * {
 *     // Only accepts imperial units (ft², yd², ac, etc.)
 * }
 * ```
 */
interface ImperialAreaInterface extends AreaInterface
{
}
