<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Energy;

/**
 * Interface for thermal energy quantities.
 *
 * This interface represents energy measurements in thermal units
 * (calorie, kilocalorie, BTU, therm, etc.). Use this for type-hinting
 * when you specifically need thermal energy units.
 *
 * Example:
 * ```php
 * function calculateHeating(ThermalEnergyInterface $heat): void
 * {
 *     // Only accepts thermal units (cal, kcal, BTU, thm)
 * }
 * ```
 */
interface ThermalEnergyInterface extends EnergyInterface
{
}
