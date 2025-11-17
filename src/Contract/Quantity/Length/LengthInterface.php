<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Length;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all length quantities.
 *
 * This interface represents any length measurement regardless of unit system
 * (metric, imperial, etc.). Use this for type-hinting when you need to accept
 * any length value.
 *
 * Example:
 * ```php
 * function calculateArea(LengthInterface $width, LengthInterface $height): AreaInterface
 * {
 *     // Works with meters, feet, inches, etc.
 * }
 * ```
 */
interface LengthInterface extends QuantityInterface
{
}
