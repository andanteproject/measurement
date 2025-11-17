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
 * British Thermal Unit (BTU) quantity.
 *
 * 1 BTU = 1055.06 J (ISO BTU)
 *
 * The BTU is commonly used in the United States for measuring
 * heating and cooling energy, as well as natural gas energy content.
 */
final class BritishThermalUnit implements ThermalEnergyInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a BritishThermalUnit quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ThermalEnergyUnit::BritishThermalUnit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ThermalEnergyUnit::BritishThermalUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ThermalEnergyUnit::BritishThermalUnit !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ThermalEnergyUnit::BritishThermalUnit, self::class);
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
