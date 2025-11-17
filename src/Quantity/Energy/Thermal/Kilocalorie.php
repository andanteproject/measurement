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
 * Kilocalorie quantity (also known as "food calorie" or "large calorie").
 *
 * 1 kcal = 1000 cal = 4184 J
 *
 * The kilocalorie is commonly used for measuring food energy content.
 * When you see "Calories" on a food label, it typically refers to kilocalories.
 */
final class Kilocalorie implements ThermalEnergyInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a Kilocalorie quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ThermalEnergyUnit::Kilocalorie);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ThermalEnergyUnit::Kilocalorie
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ThermalEnergyUnit::Kilocalorie !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ThermalEnergyUnit::Kilocalorie, self::class);
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
