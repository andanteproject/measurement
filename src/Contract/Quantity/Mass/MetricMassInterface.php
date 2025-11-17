<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Mass;

/**
 * Interface for metric mass quantities.
 *
 * This interface represents mass measurements in the metric system
 * (kilograms, grams, milligrams, etc.). Use this for type-hinting
 * when you specifically need metric masses.
 *
 * Example:
 * ```php
 * function processMetricMass(MetricMassInterface $mass): void
 * {
 *     // Only accepts metric units (kg, g, mg, etc.)
 * }
 * ```
 */
interface MetricMassInterface extends MassInterface
{
}
