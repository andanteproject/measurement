<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Acceleration;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all acceleration quantities.
 *
 * Acceleration [L¹T⁻²] represents the rate of change of velocity.
 * Common units: m/s², ft/s², g (standard gravity), Gal
 */
interface AccelerationInterface extends QuantityInterface
{
}
