<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\CalorificValue\Metric;

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
 * Joule per cubic meter quantity - the SI base unit for calorific value.
 *
 * 1 J/m³ = 1 kg·m⁻¹·s⁻²
 */
final class JoulePerCubicMeter implements MetricCalorificValueInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a joule per cubic meter quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, MetricCalorificValueUnit::JoulePerCubicMeter);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not MetricCalorificValueUnit::JoulePerCubicMeter
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (MetricCalorificValueUnit::JoulePerCubicMeter !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, MetricCalorificValueUnit::JoulePerCubicMeter, self::class);
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
