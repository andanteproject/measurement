<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Pressure\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Pressure\SIPressureInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Pressure\SIPressureUnit;

/**
 * Bar quantity.
 *
 * 1 bar = 100,000 Pa = 100 kPa
 *
 * Commonly used for tire pressure, atmospheric pressure, and gas systems.
 */
final class Bar implements SIPressureInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a bar quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, SIPressureUnit::Bar);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not SIPressureUnit::Bar
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (SIPressureUnit::Bar !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, SIPressureUnit::Bar, self::class);
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
