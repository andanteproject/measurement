<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\CalorificValue;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all calorific value quantities.
 *
 * Calorific value (energy density) represents the energy content per unit volume
 * of a fuel. This is used primarily in gas billing to convert gas volumes to
 * energy equivalents.
 *
 * Example:
 * ```php
 * function calculateEnergy(CalorificValueInterface $cv, VolumeInterface $volume): Energy
 * {
 *     // Works with J/m³, MJ/m³, BTU/ft³, etc.
 *     return $volume->multiply($cv);
 * }
 * ```
 */
interface CalorificValueInterface extends QuantityInterface
{
}
