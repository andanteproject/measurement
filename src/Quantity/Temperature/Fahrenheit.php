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
 * Fahrenheit quantity - temperature scale commonly used in the United States.
 *
 * The Fahrenheit scale is defined by:
 * - 32°F = freezing point of water = 0°C = 273.15 K
 * - 212°F = boiling point of water = 100°C = 373.15 K
 *
 * Conversion: K = (°F + 459.67) × 5/9
 *
 * Example:
 * ```php
 * $temp = Fahrenheit::of(NumberFactory::create('77'));  // room temperature
 * $celsius = $temp->to(TemperatureUnit::Celsius);       // 25°C
 * ```
 */
final class Fahrenheit implements TemperatureInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a Fahrenheit quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, TemperatureUnit::Fahrenheit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not TemperatureUnit::Fahrenheit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (TemperatureUnit::Fahrenheit !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, TemperatureUnit::Fahrenheit, self::class);
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
