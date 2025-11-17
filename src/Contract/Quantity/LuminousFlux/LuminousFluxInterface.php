<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\LuminousFlux;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all luminous flux quantities.
 *
 * Luminous Flux [J¹] represents the total perceived power of light,
 * weighted by the luminosity function for human eye sensitivity.
 * Common units: lm, klm, mlm
 */
interface LuminousFluxInterface extends QuantityInterface
{
}
