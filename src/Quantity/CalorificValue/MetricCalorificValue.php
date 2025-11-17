<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\CalorificValue;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\CalorificValue\MetricCalorificValueInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\CalorificValue\MetricCalorificValueUnit;

/**
 * Mid-level metric calorific value quantity - accepts any metric calorific value unit.
 *
 * Use this class when you need to ensure metric units (J/m³, kJ/m³, MJ/m³, etc.)
 * but don't want to restrict to a specific unit.
 *
 * Example:
 * ```php
 * $cv = MetricCalorificValue::of(NumberFactory::create('38.5'), MetricCalorificValueUnit::MegajoulePerCubicMeter);
 * ```
 */
final class MetricCalorificValue implements MetricCalorificValueInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a metric calorific value quantity.
     *
     * @throws InvalidUnitException If unit is not a metric calorific value unit
     */
    public static function of(NumberInterface $value, MetricCalorificValueUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not a metric calorific value unit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof MetricCalorificValueUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, MetricCalorificValueUnit::class, self::class);
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
