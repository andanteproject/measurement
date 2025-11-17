<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Temperature;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all temperature quantities.
 *
 * This interface represents any temperature measurement regardless of unit
 * (kelvin, celsius, fahrenheit). Use this for type-hinting when you
 * need to accept any temperature value.
 *
 * Note: Temperature is an "intensive" quantity - it doesn't make physical
 * sense to add or multiply temperatures. However, temperature differences
 * (ΔT) are additive. This library allows basic arithmetic but users should
 * be aware of the physical implications.
 *
 * Example:
 * ```php
 * function isBoiling(TemperatureInterface $temp): bool
 * {
 *     return $temp->to(TemperatureUnit::Celsius)->getValue()->compareTo(
 *         NumberFactory::create('100')
 *     ) >= 0;
 * }
 * ```
 */
interface TemperatureInterface extends QuantityInterface
{
}
