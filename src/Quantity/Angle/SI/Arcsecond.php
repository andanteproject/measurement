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
 * Arcsecond quantity (second of arc).
 *
 * 1″ = 1/60′ = 1/3600° = π/648000 rad
 * Used in astronomy and precision measurements.
 */
final class Arcsecond implements AngleInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an arcsecond quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, AngleUnit::Arcsecond);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not AngleUnit::Arcsecond
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (AngleUnit::Arcsecond !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, AngleUnit::Arcsecond, self::class);
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
