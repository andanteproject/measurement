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
 * Farad quantity (SI derived unit for electric capacitance).
 *
 * 1 F = 1 C/V = 1 A⋅s/V = 1 A²⋅s⁴/(kg⋅m²)
 * The farad is a large unit; most practical capacitors are measured in
 * microfarads (μF), nanofarads (nF), or picofarads (pF).
 */
final class Farad implements ElectricCapacitanceInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a farad quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ElectricCapacitanceUnit::Farad);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ElectricCapacitanceUnit::Farad
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ElectricCapacitanceUnit::Farad !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ElectricCapacitanceUnit::Farad, self::class);
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
