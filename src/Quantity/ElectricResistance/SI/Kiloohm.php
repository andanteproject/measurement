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
 * Kiloohm quantity.
 *
 * 1 kΩ = 1000 Ω
 * Common unit for resistors in electronics.
 */
final class Kiloohm implements ElectricResistanceInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a kiloohm quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ElectricResistanceUnit::Kiloohm);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ElectricResistanceUnit::Kiloohm
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ElectricResistanceUnit::Kiloohm !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ElectricResistanceUnit::Kiloohm, self::class);
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
