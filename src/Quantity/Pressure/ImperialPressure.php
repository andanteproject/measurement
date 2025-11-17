<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Pressure;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Pressure\ImperialPressureInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Pressure\ImperialPressureUnit;

/**
 * Mid-level imperial pressure quantity - accepts any imperial pressure unit.
 *
 * Use this class when you need to ensure imperial units (psi, psf, etc.)
 * but don't want to restrict to a specific unit.
 *
 * Example:
 * ```php
 * $pressure = ImperialPressure::of(NumberFactory::create('14.7'), ImperialPressureUnit::PoundPerSquareInch);
 * ```
 */
final class ImperialPressure implements ImperialPressureInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an imperial pressure quantity.
     *
     * @throws InvalidUnitException If unit is not an imperial pressure unit
     */
    public static function of(NumberInterface $value, ImperialPressureUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not an imperial pressure unit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof ImperialPressureUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, ImperialPressureUnit::class, self::class);
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
