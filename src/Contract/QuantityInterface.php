<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Contract\Math\NumberInterface;

/**
 * Represents any physical quantity (value + unit).
 *
 * A quantity is an immutable value object combining a numerical value
 * with a unit of measurement.
 *
 * The dimension is accessible via getUnit()->dimension().
 */
interface QuantityInterface
{
    /**
     * Get the numerical value.
     *
     * Returns the value as a NumberInterface for arbitrary precision operations.
     */
    public function getValue(): NumberInterface;

    /**
     * Get the unit this quantity is expressed in.
     */
    public function getUnit(): UnitInterface;
}
