<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\ElectricPotential\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\ElectricPotential\ElectricPotentialInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\ElectricPotential\ElectricPotentialUnit;

/**
 * Megavolt quantity.
 *
 * 1 MV = 1,000,000 V = 10⁶ V
 * Used for high-voltage power transmission and lightning.
 */
final class Megavolt implements ElectricPotentialInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a megavolt quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ElectricPotentialUnit::Megavolt);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ElectricPotentialUnit::Megavolt
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ElectricPotentialUnit::Megavolt !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ElectricPotentialUnit::Megavolt, self::class);
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
