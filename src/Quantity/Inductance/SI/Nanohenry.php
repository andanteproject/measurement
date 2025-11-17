<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Inductance\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Inductance\InductanceInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Inductance\InductanceUnit;

/**
 * Nanohenry quantity.
 *
 * 1 nH = 0.000000001 H = 10⁻⁹ H
 */
final class Nanohenry implements InductanceInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a nanohenry quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, InductanceUnit::Nanohenry);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not InductanceUnit::Nanohenry
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (InductanceUnit::Nanohenry !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, InductanceUnit::Nanohenry, self::class);
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
