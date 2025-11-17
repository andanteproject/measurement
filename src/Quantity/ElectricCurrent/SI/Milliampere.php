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
 * Milliampere quantity.
 *
 * 1 mA = 0.001 A
 * Common for small electronic devices and battery specifications.
 */
final class Milliampere implements ElectricCurrentInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a milliampere quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ElectricCurrentUnit::Milliampere);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ElectricCurrentUnit::Milliampere
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ElectricCurrentUnit::Milliampere !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ElectricCurrentUnit::Milliampere, self::class);
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
