<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Length;

/**
 * Interface for metric length quantities.
 *
 * This interface represents length measurements in the metric system
 * (meters, kilometers, centimeters, etc.). Use this for type-hinting
 * when you specifically need metric lengths.
 *
 * Example:
 * ```php
 * function processMetricLength(MetricLengthInterface $length): void
 * {
 *     // Only accepts metric units (m, km, cm, mm, etc.)
 * }
 * ```
 */
interface MetricLengthInterface extends LengthInterface
{
}
