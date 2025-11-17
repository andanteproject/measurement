<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Velocity;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Velocity\ImperialVelocityInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Velocity\ImperialVelocityUnit;

/**
 * Mid-level imperial velocity quantity - accepts any imperial velocity unit.
 *
 * Use this class when you need to ensure imperial units (mph, ft/s, knot)
 * but don't want to restrict to a specific unit.
 *
 * Example:
 * ```php
 * $velocity = ImperialVelocity::of(NumberFactory::create('60'), ImperialVelocityUnit::MilePerHour);
 * ```
 */
final class ImperialVelocity implements ImperialVelocityInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an imperial velocity quantity.
     *
     * @throws InvalidUnitException If unit is not an imperial velocity unit
     */
    public static function of(NumberInterface $value, ImperialVelocityUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not an imperial velocity unit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof ImperialVelocityUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, ImperialVelocityUnit::class, self::class);
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
