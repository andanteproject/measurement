<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Mass;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all mass quantities.
 *
 * This interface represents any mass measurement regardless of unit system
 * (metric, imperial, etc.). Use this for type-hinting when you need to accept
 * any mass value.
 *
 * Example:
 * ```php
 * function calculateDensity(MassInterface $mass, VolumeInterface $volume): DensityInterface
 * {
 *     // Works with kilograms, pounds, ounces, etc.
 * }
 * ```
 */
interface MassInterface extends QuantityInterface
{
}
