<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Acceleration;

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
 * Mid-level metric acceleration quantity - accepts any metric acceleration unit.
 *
 * Use this class when you need to ensure metric units (m/s², cm/s², etc.)
 * but don't want to restrict to a specific unit.
 *
 * Example:
 * ```php
 * $acceleration = MetricAcceleration::of(NumberFactory::create('9.81'), MetricAccelerationUnit::MeterPerSecondSquared);
 * ```
 */
final class MetricAcceleration implements MetricAccelerationInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a metric acceleration quantity.
     *
     * @throws InvalidUnitException If unit is not a metric acceleration unit
     */
    public static function of(NumberInterface $value, MetricAccelerationUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not a metric acceleration unit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof MetricAccelerationUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, MetricAccelerationUnit::class, self::class);
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
