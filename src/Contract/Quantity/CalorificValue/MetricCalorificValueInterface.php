<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\CalorificValue;

/**
 * Interface for metric calorific value quantities.
 *
 * Used for type-hinting when you need to ensure metric units
 * (J/m³, kJ/m³, MJ/m³, etc.).
 */
interface MetricCalorificValueInterface extends CalorificValueInterface
{
}
