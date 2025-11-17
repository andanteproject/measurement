<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Energy;

/**
 * Interface for electric energy quantities.
 *
 * This interface represents energy measurements in electric units
 * (watt-hour, kilowatt-hour, megawatt-hour, etc.). Use this for type-hinting
 * when you specifically need electric energy units.
 *
 * Example:
 * ```php
 * function calculateElectricBill(ElectricEnergyInterface $consumption): float
 * {
 *     // Only accepts electric units (Wh, kWh, MWh, etc.)
 * }
 * ```
 */
interface ElectricEnergyInterface extends EnergyInterface
{
}
