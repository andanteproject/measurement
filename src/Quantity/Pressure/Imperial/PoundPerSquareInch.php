<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Pressure\Imperial;

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
 * Pound per square inch quantity (psi).
 *
 * 1 psi = 6894.757... Pa
 *
 * The most common imperial pressure unit, used for tire pressure,
 * hydraulic systems, and compressed gas.
 */
final class PoundPerSquareInch implements ImperialPressureInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a psi quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ImperialPressureUnit::PoundPerSquareInch);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ImperialPressureUnit::PoundPerSquareInch
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ImperialPressureUnit::PoundPerSquareInch !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ImperialPressureUnit::PoundPerSquareInch, self::class);
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
