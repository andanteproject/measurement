<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Energy;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all energy quantities.
 *
 * This interface represents any energy measurement regardless of unit system
 * (SI joules, electric watt-hours, thermal calories, etc.). Use this for
 * type-hinting when you need to accept any energy value.
 *
 * Example:
 * ```php
 * function calculateCost(EnergyInterface $energy, float $pricePerKwh): float
 * {
 *     // Works with joules, kWh, BTU, etc.
 * }
 * ```
 */
interface EnergyInterface extends QuantityInterface
{
}
