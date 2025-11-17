<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Power\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Power\SIPowerInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Power\SIPowerUnit;

/**
 * Gigawatt quantity.
 *
 * 1 GW = 1,000,000,000 W
 *
 * Used for national power grid capacity and large power
 * generation facilities.
 */
final class Gigawatt implements SIPowerInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a gigawatt quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, SIPowerUnit::Gigawatt);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not SIPowerUnit::Gigawatt
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (SIPowerUnit::Gigawatt !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, SIPowerUnit::Gigawatt, self::class);
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
