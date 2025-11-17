<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Volume;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Volume\GasVolumeInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Volume\GasVolumeUnit;

/**
 * Generic gas volume quantity that can hold any gas volume unit.
 *
 * This is the "mid-level" class for gas volumes. Use this when you need
 * to work with any gas volume unit without knowing the specific unit at
 * compile time.
 *
 * For type-safe quantities with a specific unit, use the concrete classes:
 * - StandardCubicMeter::of($number)
 * - NormalCubicMeter::of($number)
 *
 * Example:
 * ```php
 * $volume = GasVolume::of($number, GasVolumeUnit::StandardCubicMeter);
 * $volume = GasVolume::of($number, GasVolumeUnit::ThousandCubicFeet);
 * ```
 */
final class GasVolume implements GasVolumeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a gas volume with the specified value and unit.
     */
    public static function of(NumberInterface $value, GasVolumeUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not a GasVolumeUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof GasVolumeUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, GasVolumeUnit::class, self::class);
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
