<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Velocity;

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
 * Mid-level metric velocity quantity - accepts any metric velocity unit.
 *
 * Use this class when you need to ensure metric units (m/s, km/h, etc.)
 * but don't want to restrict to a specific unit.
 *
 * Example:
 * ```php
 * $velocity = MetricVelocity::of(NumberFactory::create('100'), MetricVelocityUnit::KilometerPerHour);
 * ```
 */
final class MetricVelocity implements MetricVelocityInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a metric velocity quantity.
     *
     * @throws InvalidUnitException If unit is not a metric velocity unit
     */
    public static function of(NumberInterface $value, MetricVelocityUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not a metric velocity unit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof MetricVelocityUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, MetricVelocityUnit::class, self::class);
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
