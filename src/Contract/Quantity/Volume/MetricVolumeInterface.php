<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Volume;

/**
 * Interface for metric volume quantities.
 *
 * This interface represents volume measurements in the metric system
 * (cubic meters, liters, milliliters, etc.). Use this for type-hinting
 * when you specifically need metric volumes.
 *
 * Example:
 * ```php
 * function processMetricVolume(MetricVolumeInterface $volume): void
 * {
 *     // Only accepts metric units (m³, L, mL, etc.)
 * }
 * ```
 */
interface MetricVolumeInterface extends VolumeInterface
{
}
