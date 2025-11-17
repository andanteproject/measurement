<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Energy\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Energy\SIEnergyInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Energy\SIEnergyUnit;

/**
 * Joule quantity - the SI base unit of energy.
 *
 * 1 J = 1 kg·m²/s² = 1 N·m = 1 W·s
 */
final class Joule implements SIEnergyInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a Joule quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, SIEnergyUnit::Joule);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not SIEnergyUnit::Joule
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (SIEnergyUnit::Joule !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, SIEnergyUnit::Joule, self::class);
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
