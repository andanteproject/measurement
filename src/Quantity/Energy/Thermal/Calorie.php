<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Energy\Thermal;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Energy\ThermalEnergyInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Energy\ThermalEnergyUnit;

/**
 * Calorie quantity.
 *
 * 1 cal = 4.184 J (thermochemical calorie)
 *
 * The calorie is a unit of energy commonly used in chemistry
 * and for measuring food energy content.
 */
final class Calorie implements ThermalEnergyInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a Calorie quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ThermalEnergyUnit::Calorie);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ThermalEnergyUnit::Calorie
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ThermalEnergyUnit::Calorie !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ThermalEnergyUnit::Calorie, self::class);
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
