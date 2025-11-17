<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Temperature;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Temperature\TemperatureInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Temperature\TemperatureUnit;

/**
 * Kelvin quantity - the SI base unit of temperature.
 *
 * The kelvin is defined as 1/273.16 of the thermodynamic temperature
 * of the triple point of water.
 *
 * 0 K = -273.15°C = -459.67°F (absolute zero)
 *
 * Example:
 * ```php
 * $temp = Kelvin::of(NumberFactory::create('373.15')); // boiling point of water
 * $celsius = $temp->to(TemperatureUnit::Celsius);      // 100°C
 * ```
 */
final class Kelvin implements TemperatureInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a Kelvin quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, TemperatureUnit::Kelvin);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not TemperatureUnit::Kelvin
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (TemperatureUnit::Kelvin !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, TemperatureUnit::Kelvin, self::class);
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
