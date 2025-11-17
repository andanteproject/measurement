<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Volume;

/**
 * Interface for gas volume quantities.
 *
 * This interface represents gas volume measurements at standard conditions
 * (Smc, Nmc, scf, Mcf). Use this for type-hinting when you specifically
 * need gas volume units.
 *
 * Example:
 * ```php
 * function calculateGasBill(GasVolumeInterface $consumption): float
 * {
 *     // Only accepts gas volume units (Smc, Nmc, scf, Mcf)
 * }
 * ```
 */
interface GasVolumeInterface extends VolumeInterface
{
}
