<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Energy;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Energy\EnergyInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Energy as EnergyDimension;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;

/**
 * Generic energy quantity that can hold any energy unit.
 *
 * This is the most generic energy class. Use this when you need to work with
 * any energy unit regardless of the measurement system.
 *
 * For system-specific types, use:
 * - SIEnergy::of($number, SIEnergyUnit::Joule)
 * - ElectricEnergy::of($number, ElectricEnergyUnit::KilowattHour)
 * - ThermalEnergy::of($number, ThermalEnergyUnit::Calorie)
 *
 * For unit-specific types, use:
 * - Joule::of($number)
 * - KilowattHour::of($number)
 *
 * Example:
 * ```php
 * $energy = Energy::of($number, SIEnergyUnit::Joule);
 * $energy = Energy::of($number, ElectricEnergyUnit::KilowattHour);
 * ```
 */
final class Energy implements EnergyInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an energy with the specified value and unit.
     */
    public static function of(NumberInterface $value, UnitInterface $unit): self
    {
        if ($unit->dimension() !== EnergyDimension::instance()) {
            throw InvalidUnitException::forInvalidDimension($unit, EnergyDimension::instance(), self::class);
        }

        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit's dimension is not Energy
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if ($unit->dimension() !== EnergyDimension::instance()) {
            throw InvalidUnitException::forInvalidDimension($unit, EnergyDimension::instance(), self::class);
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
