<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Area;

/**
 * Interface for metric area quantities.
 *
 * This interface represents area measurements in the metric system
 * (square meters, square kilometers, hectares, etc.). Use this for type-hinting
 * when you specifically need metric areas.
 *
 * Example:
 * ```php
 * function processMetricArea(MetricAreaInterface $area): void
 * {
 *     // Only accepts metric units (m², km², ha, etc.)
 * }
 * ```
 */
interface MetricAreaInterface extends AreaInterface
{
}
