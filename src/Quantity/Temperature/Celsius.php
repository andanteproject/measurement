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
 * Celsius quantity - temperature relative to the freezing point of water.
 *
 * The Celsius scale is defined by:
 * - 0°C = freezing point of water = 273.15 K
 * - 100°C = boiling point of water = 373.15 K
 *
 * Conversion: K = °C + 273.15
 *
 * Example:
 * ```php
 * $temp = Celsius::of(NumberFactory::create('25'));   // room temperature
 * $kelvin = $temp->to(TemperatureUnit::Kelvin);       // 298.15 K
 * ```
 */
final class Celsius implements TemperatureInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a Celsius quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, TemperatureUnit::Celsius);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not TemperatureUnit::Celsius
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (TemperatureUnit::Celsius !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, TemperatureUnit::Celsius, self::class);
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
