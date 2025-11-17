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
 * Torr quantity (mmHg).
 *
 * 1 Torr = 1/760 atm = 133.322... Pa
 *
 * Named after Evangelista Torricelli. Commonly used in vacuum
 * measurements and blood pressure (mmHg).
 */
final class Torr implements SIPressureInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a torr quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, SIPressureUnit::Torr);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not SIPressureUnit::Torr
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (SIPressureUnit::Torr !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, SIPressureUnit::Torr, self::class);
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
