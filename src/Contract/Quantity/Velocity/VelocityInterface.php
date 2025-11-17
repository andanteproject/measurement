<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Velocity;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all velocity quantities.
 *
 * Velocity [L¹T⁻¹] represents the rate of change of position.
 * Common units: m/s, km/h, mph, ft/s, knot
 */
interface VelocityInterface extends QuantityInterface
{
}
