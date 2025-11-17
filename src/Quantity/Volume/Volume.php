<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Volume;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Volume\VolumeInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Volume as VolumeDimension;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;

/**
 * Generic volume quantity that can hold any volume unit.
 *
 * This is the most generic volume class. Use this when you need to work with
 * any volume unit regardless of the measurement system.
 *
 * For system-specific types, use:
 * - MetricVolume::of($number, MetricVolumeUnit::Liter)
 * - ImperialVolume::of($number, ImperialVolumeUnit::USGallon)
 * - GasVolume::of($number, GasVolumeUnit::StandardCubicMeter)
 *
 * For unit-specific types, use:
 * - Liter::of($number)
 * - CubicMeter::of($number)
 *
 * Example:
 * ```php
 * $volume = Volume::of($number, MetricVolumeUnit::Liter);
 * $volume = Volume::of($number, ImperialVolumeUnit::USGallon);
 * ```
 */
final class Volume implements VolumeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a volume with the specified value and unit.
     */
    public static function of(NumberInterface $value, UnitInterface $unit): self
    {
        if ($unit->dimension() !== VolumeDimension::instance()) {
            throw InvalidUnitException::forInvalidDimension($unit, VolumeDimension::instance(), self::class);
        }

        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit's dimension is not Volume
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if ($unit->dimension() !== VolumeDimension::instance()) {
            throw InvalidUnitException::forInvalidDimension($unit, VolumeDimension::instance(), self::class);
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
