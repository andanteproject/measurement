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
 * Turn quantity.
 *
 * 1 turn = 2π rad = 360° = 400 gon = 1 rev
 * Equivalent to revolution, used in some contexts.
 */
final class Turn implements AngleInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a turn quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, AngleUnit::Turn);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not AngleUnit::Turn
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (AngleUnit::Turn !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, AngleUnit::Turn, self::class);
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
