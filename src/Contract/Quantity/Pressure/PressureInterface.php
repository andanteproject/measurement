<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Pressure;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all pressure quantities.
 *
 * Pressure [L⁻¹M¹T⁻²] is force per unit area.
 * Common units: Pa, kPa, bar, atm, psi
 */
interface PressureInterface extends QuantityInterface
{
}
