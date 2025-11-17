<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\MagneticFlux;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all magnetic flux quantities.
 *
 * Magnetic Flux [L²M¹T⁻²I⁻¹] is a derived dimension representing
 * the total magnetic field passing through a surface.
 * Common units: Wb, mWb, μWb, Mx
 */
interface MagneticFluxInterface extends QuantityInterface
{
}
