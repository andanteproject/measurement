<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Angle;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Angle\AngleInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Angle as AngleDimension;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;

/**
 * Generic angle quantity - accepts any angle unit.
 *
 * Use this class when you need to work with any angle unit.
 *
 * Example:
 * ```php
 * $angle = Angle::of(NumberFactory::create('90'), AngleUnit::Degree);
 * ```
 */
final class Angle implements AngleInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an angle quantity with any angle unit.
     */
    public static function of(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit->dimension() instanceof AngleDimension) {
            throw InvalidUnitException::forInvalidDimension($unit, AngleDimension::instance(), self::class);
        }

        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        return self::of($value, $unit);
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
