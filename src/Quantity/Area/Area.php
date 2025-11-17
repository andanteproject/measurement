<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Area;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Area\AreaInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Area as AreaDimension;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;

/**
 * Generic area quantity that can hold any area unit (metric or imperial).
 *
 * This is the most generic area class. Use this when you need to work with
 * any area unit regardless of the measurement system.
 *
 * For system-specific types, use:
 * - MetricArea::of($number, MetricAreaUnit::SquareMeter)
 * - ImperialArea::of($number, ImperialAreaUnit::SquareFoot)
 *
 * For unit-specific types, use:
 * - SquareMeter::of($number)
 * - SquareFoot::of($number)
 *
 * Example:
 * ```php
 * $area = Area::of($number, MetricAreaUnit::SquareMeter);
 * $area = Area::of($number, ImperialAreaUnit::Acre);
 * ```
 */
final class Area implements AreaInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an area with the specified value and unit.
     */
    public static function of(NumberInterface $value, UnitInterface $unit): self
    {
        if ($unit->dimension() !== AreaDimension::instance()) {
            throw InvalidUnitException::forInvalidDimension($unit, AreaDimension::instance(), self::class);
        }

        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit's dimension is not Area
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if ($unit->dimension() !== AreaDimension::instance()) {
            throw InvalidUnitException::forInvalidDimension($unit, AreaDimension::instance(), self::class);
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
