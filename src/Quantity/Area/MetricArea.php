<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Area;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Area\MetricAreaInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Area\MetricAreaUnit;

/**
 * Generic metric area quantity that can hold any metric area unit.
 *
 * This is the "mid-level" class for metric areas. Use this when you need
 * to work with any metric area unit without knowing the specific unit at
 * compile time.
 *
 * For type-safe quantities with a specific unit, use the concrete classes:
 * - SquareMeter::of($number)
 * - SquareKilometer::of($number)
 * - Hectare::of($number)
 *
 * Example:
 * ```php
 * $area = MetricArea::of($number, MetricAreaUnit::SquareMeter);
 * $area = MetricArea::of($number, MetricAreaUnit::Hectare);
 * ```
 */
final class MetricArea implements MetricAreaInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a metric area with the specified value and unit.
     */
    public static function of(NumberInterface $value, MetricAreaUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not a MetricAreaUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof MetricAreaUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, MetricAreaUnit::class, self::class);
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
