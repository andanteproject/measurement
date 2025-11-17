<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\CalorificValue;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\CalorificValue\ImperialCalorificValueInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\CalorificValue\ImperialCalorificValueUnit;

/**
 * Mid-level imperial calorific value quantity - accepts any imperial calorific value unit.
 *
 * Use this class when you need to ensure imperial units (BTU/ft³, therm/ft³, etc.)
 * but don't want to restrict to a specific unit.
 *
 * Example:
 * ```php
 * $cv = ImperialCalorificValue::of(NumberFactory::create('1000'), ImperialCalorificValueUnit::BTUPerCubicFoot);
 * ```
 */
final class ImperialCalorificValue implements ImperialCalorificValueInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
{
    use ConvertibleTrait;
    use ComparableTrait;
    use CalculableTrait;
    use AutoScalableTrait;

    private function __construct(
        private readonly NumberInterface $value,
        private readonly UnitInterface $unit,
    ) {
    }

    /**
     * Create an imperial calorific value quantity.
     *
     * @throws InvalidUnitException If unit is not an imperial calorific value unit
     */
    public static function of(NumberInterface $value, ImperialCalorificValueUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not an imperial calorific value unit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof ImperialCalorificValueUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, ImperialCalorificValueUnit::class, self::class);
        }

        return new self($value, $unit);
    }

    public function getValue(): NumberInterface
    {
        return $this->value;
    }

    public function getUnit(): UnitInterface
    {
        return $this->unit;
    }
}
