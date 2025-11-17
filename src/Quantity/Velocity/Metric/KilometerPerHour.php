<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Velocity\Metric;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Velocity\MetricVelocityInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Velocity\MetricVelocityUnit;

/**
 * Kilometer per hour quantity.
 *
 * 1 km/h = 0.27778 m/s = 0.62137 mph
 * Commonly used for vehicle speeds in metric countries.
 */
final class KilometerPerHour implements MetricVelocityInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a kilometer per hour quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, MetricVelocityUnit::KilometerPerHour);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not MetricVelocityUnit::KilometerPerHour
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (MetricVelocityUnit::KilometerPerHour !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, MetricVelocityUnit::KilometerPerHour, self::class);
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
