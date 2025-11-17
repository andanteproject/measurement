<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\ElectricResistance\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\ElectricResistance\ElectricResistanceInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\ElectricResistance\ElectricResistanceUnit;

/**
 * Microohm quantity.
 *
 * 1 μΩ = 0.000001 Ω = 10⁻⁶ Ω
 * Used for measuring extremely low resistances like contact resistance.
 */
final class Microohm implements ElectricResistanceInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a microohm quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ElectricResistanceUnit::Microohm);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ElectricResistanceUnit::Microohm
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ElectricResistanceUnit::Microohm !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ElectricResistanceUnit::Microohm, self::class);
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
