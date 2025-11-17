<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Acceleration\Metric;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Acceleration\MetricAccelerationInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Acceleration\MetricAccelerationUnit;

/**
 * Millimeter per second squared quantity.
 *
 * 1 mm/s² = 0.001 m/s²
 */
final class MillimeterPerSecondSquared implements MetricAccelerationInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a millimeter per second squared quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, MetricAccelerationUnit::MillimeterPerSecondSquared);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not MetricAccelerationUnit::MillimeterPerSecondSquared
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (MetricAccelerationUnit::MillimeterPerSecondSquared !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, MetricAccelerationUnit::MillimeterPerSecondSquared, self::class);
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
