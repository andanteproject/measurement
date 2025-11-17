<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Illuminance;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all illuminance quantities.
 *
 * Illuminance [L⁻²J¹] represents the luminous flux incident on a surface
 * per unit area - how much light falls on a surface.
 * Common units: lx, klx, mlx, fc
 */
interface IlluminanceInterface extends QuantityInterface
{
}
