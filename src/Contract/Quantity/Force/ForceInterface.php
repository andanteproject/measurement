<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Force;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all force quantities.
 *
 * Force [L¹M¹T⁻²] represents the interaction that causes acceleration.
 * Common units: N (newton), kN, lbf (pound-force), dyn (dyne)
 */
interface ForceInterface extends QuantityInterface
{
}
