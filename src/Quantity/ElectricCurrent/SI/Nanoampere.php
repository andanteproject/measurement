<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\ElectricCurrent\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\ElectricCurrent\ElectricCurrentInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\ElectricCurrent\ElectricCurrentUnit;

/**
 * Nanoampere quantity.
 *
 * 1 nA = 0.000000001 A = 10⁻⁹ A
 * Used for ultra-sensitive measurements and leakage currents.
 */
final class Nanoampere implements ElectricCurrentInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a nanoampere quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ElectricCurrentUnit::Nanoampere);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ElectricCurrentUnit::Nanoampere
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ElectricCurrentUnit::Nanoampere !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ElectricCurrentUnit::Nanoampere, self::class);
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
