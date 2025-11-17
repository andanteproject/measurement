<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\CalorificValue;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\CalorificValue\CalorificValueInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\CalorificValue as CalorificValueDimension;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;

/**
 * Generic calorific value quantity - accepts any calorific value unit.
 *
 * Use this class when you need to work with any calorific value unit
 * regardless of system (metric or imperial).
 *
 * Example:
 * ```php
 * $cv = CalorificValue::of(NumberFactory::create('38.5'), MetricCalorificValueUnit::MegajoulePerCubicMeter);
 * ```
 */
final class CalorificValue implements CalorificValueInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a calorific value quantity with any calorific value unit.
     */
    public static function of(NumberInterface $value, UnitInterface $unit): self
    {
        if ($unit->dimension() !== CalorificValueDimension::instance()) {
            throw InvalidUnitException::forInvalidDimension($unit, CalorificValueDimension::instance(), self::class);
        }

        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        return self::of($value, $unit);
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
