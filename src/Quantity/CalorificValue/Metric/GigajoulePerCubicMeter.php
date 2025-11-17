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
 * Gigajoule per cubic meter quantity.
 *
 * 1 GJ/m³ = 1,000,000,000 J/m³
 */
final class GigajoulePerCubicMeter implements MetricCalorificValueInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a gigajoule per cubic meter quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, MetricCalorificValueUnit::GigajoulePerCubicMeter);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not MetricCalorificValueUnit::GigajoulePerCubicMeter
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (MetricCalorificValueUnit::GigajoulePerCubicMeter !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, MetricCalorificValueUnit::GigajoulePerCubicMeter, self::class);
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
