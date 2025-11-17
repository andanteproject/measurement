<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Angle\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Angle\AngleInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Angle\AngleUnit;

/**
 * Radian quantity (SI unit for angle).
 *
 * 1 rad = the angle subtended at the center of a circle by an arc
 * equal in length to the radius.
 * 1 rad ≈ 57.2958°
 */
final class Radian implements AngleInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a radian quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, AngleUnit::Radian);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not AngleUnit::Radian
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (AngleUnit::Radian !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, AngleUnit::Radian, self::class);
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
