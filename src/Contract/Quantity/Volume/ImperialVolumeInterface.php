<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Volume;

/**
 * Interface for imperial volume quantities.
 *
 * This interface represents volume measurements in the imperial/US customary system
 * (cubic feet, gallons, pints, fluid ounces, etc.). Use this for type-hinting
 * when you specifically need imperial volumes.
 *
 * Example:
 * ```php
 * function processImperialVolume(ImperialVolumeInterface $volume): void
 * {
 *     // Only accepts imperial units (ft³, gal, pt, fl oz, etc.)
 * }
 * ```
 */
interface ImperialVolumeInterface extends VolumeInterface
{
}
