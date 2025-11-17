<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Energy;

/**
 * Interface for SI energy quantities.
 *
 * This interface represents energy measurements in SI units
 * (joule, kilojoule, megajoule, etc.). Use this for type-hinting
 * when you specifically need SI energy units.
 *
 * Example:
 * ```php
 * function processSIEnergy(SIEnergyInterface $energy): void
 * {
 *     // Only accepts SI units (J, kJ, MJ, etc.)
 * }
 * ```
 */
interface SIEnergyInterface extends EnergyInterface
{
}
