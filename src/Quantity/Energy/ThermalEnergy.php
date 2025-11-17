<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Energy;

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
 * Generic thermal energy quantity that can hold any thermal energy unit.
 *
 * This is the "mid-level" class for thermal energies. Use this when you need
 * to work with any thermal energy unit without knowing the specific unit at
 * compile time.
 *
 * For type-safe quantities with a specific unit, use the concrete classes:
 * - Calorie::of($number)
 * - Kilocalorie::of($number)
 * - BritishThermalUnit::of($number)
 *
 * Example:
 * ```php
 * $energy = ThermalEnergy::of($number, ThermalEnergyUnit::Calorie);
 * $energy = ThermalEnergy::of($number, ThermalEnergyUnit::BritishThermalUnit);
 * ```
 */
final class ThermalEnergy implements ThermalEnergyInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a thermal energy with the specified value and unit.
     */
    public static function of(NumberInterface $value, ThermalEnergyUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not a ThermalEnergyUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof ThermalEnergyUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, ThermalEnergyUnit::class, self::class);
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
