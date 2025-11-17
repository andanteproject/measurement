<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Volume;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all volume quantities.
 *
 * This interface represents any volume measurement regardless of unit system
 * (metric, imperial, gas measurement, etc.). Use this for type-hinting when
 * you need to accept any volume value.
 *
 * Example:
 * ```php
 * function calculateCapacity(VolumeInterface $volume): void
 * {
 *     // Works with liters, gallons, cubic meters, etc.
 * }
 * ```
 */
interface VolumeInterface extends QuantityInterface
{
}
