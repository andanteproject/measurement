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
use Andante\Measurement\Dimension\Temperature as TemperatureDimension;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;

/**
 * Generic temperature quantity that can hold any temperature unit.
 *
 * Use this class when you need to work with temperature values where the specific
 * unit may vary or is determined at runtime.
 *
 * Note: Temperature conversions are affine (not just multiplicative) because
 * they involve both scaling and offset.
 *
 * Example:
 * ```php
 * $temp = Temperature::of(NumberFactory::create('100'), TemperatureUnit::Celsius);
 * $fahrenheit = $temp->to(TemperatureUnit::Fahrenheit); // 212°F
 * ```
 */
final class Temperature implements TemperatureInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a Temperature quantity with a specific unit.
     *
     * @throws InvalidUnitException If unit is not a temperature unit
     */
    public static function of(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit->dimension()->isCompatibleWith(TemperatureDimension::instance())) {
            throw InvalidUnitException::forInvalidDimension($unit, TemperatureDimension::instance(), self::class);
        }

        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not a temperature unit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        return self::of($value, $unit);
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
