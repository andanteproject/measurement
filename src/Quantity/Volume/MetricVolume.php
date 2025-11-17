<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Volume;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Volume\MetricVolumeInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Volume\MetricVolumeUnit;

/**
 * Generic metric volume quantity that can hold any metric volume unit.
 *
 * This is the "mid-level" class for metric volumes. Use this when you need
 * to work with any metric volume unit without knowing the specific unit at
 * compile time.
 *
 * For type-safe quantities with a specific unit, use the concrete classes:
 * - Liter::of($number)
 * - CubicMeter::of($number)
 * - Milliliter::of($number)
 *
 * Example:
 * ```php
 * $volume = MetricVolume::of($number, MetricVolumeUnit::Liter);
 * $volume = MetricVolume::of($number, MetricVolumeUnit::CubicMeter);
 * ```
 */
final class MetricVolume implements MetricVolumeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a metric volume with the specified value and unit.
     */
    public static function of(NumberInterface $value, MetricVolumeUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not a MetricVolumeUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof MetricVolumeUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, MetricVolumeUnit::class, self::class);
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
