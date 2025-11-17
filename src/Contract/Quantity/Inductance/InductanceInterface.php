<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Inductance;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all inductance quantities.
 *
 * Inductance [L²M¹T⁻²I⁻²] is a derived dimension representing
 * the property of an electrical conductor to oppose changes in current.
 * Common units: H, mH, μH, nH
 */
interface InductanceInterface extends QuantityInterface
{
}
