<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Area;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all area quantities.
 *
 * This interface represents any area measurement regardless of unit system
 * (metric, imperial, etc.). Use this for type-hinting when you need to accept
 * any area value.
 *
 * Example:
 * ```php
 * function calculateVolume(AreaInterface $area, LengthInterface $height): VolumeInterface
 * {
 *     // Works with square meters, square feet, acres, hectares, etc.
 * }
 * ```
 */
interface AreaInterface extends QuantityInterface
{
}
