<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\ElectricPotential;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all electric potential (voltage) quantities.
 *
 * Electric Potential [L²M¹T⁻³I⁻¹] is a derived dimension representing
 * the work done per unit charge.
 * Common units: V, MV, kV, mV, μV
 */
interface ElectricPotentialInterface extends QuantityInterface
{
}
