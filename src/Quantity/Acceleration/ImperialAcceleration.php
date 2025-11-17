<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Acceleration;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Acceleration\ImperialAccelerationInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Acceleration\ImperialAccelerationUnit;

/**
 * Mid-level imperial acceleration quantity - accepts any imperial acceleration unit.
 *
 * Use this class when you need to ensure imperial units (ft/s², in/s²)
 * but don't want to restrict to a specific unit.
 *
 * Example:
 * ```php
 * $acceleration = ImperialAcceleration::of(NumberFactory::create('32.174'), ImperialAccelerationUnit::FootPerSecondSquared);
 * ```
 */
final class ImperialAcceleration implements ImperialAccelerationInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an imperial acceleration quantity.
     *
     * @throws InvalidUnitException If unit is not an imperial acceleration unit
     */
    public static function of(NumberInterface $value, ImperialAccelerationUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not an imperial acceleration unit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof ImperialAccelerationUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, ImperialAccelerationUnit::class, self::class);
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
