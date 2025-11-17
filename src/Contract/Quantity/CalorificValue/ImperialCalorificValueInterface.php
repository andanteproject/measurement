<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\CalorificValue;

/**
 * Interface for imperial calorific value quantities.
 *
 * Used for type-hinting when you need to ensure imperial units
 * (BTU/ft³, therm/ft³, etc.).
 */
interface ImperialCalorificValueInterface extends CalorificValueInterface
{
}
