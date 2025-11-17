<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\ElectricCapacitance\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\ElectricCapacitance\ElectricCapacitanceInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\ElectricCapacitance\ElectricCapacitanceUnit;

/**
 * Microfarad quantity.
 *
 * 1 μF = 0.000001 F = 10⁻⁶ F
 * One of the most commonly used capacitance units in electronics.
 */
final class Microfarad implements ElectricCapacitanceInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a microfarad quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ElectricCapacitanceUnit::Microfarad);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ElectricCapacitanceUnit::Microfarad
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ElectricCapacitanceUnit::Microfarad !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ElectricCapacitanceUnit::Microfarad, self::class);
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
