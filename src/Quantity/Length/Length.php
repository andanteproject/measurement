<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Length;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Length\LengthInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Length as LengthDimension;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;

/**
 * Generic length quantity that can hold any length unit (metric or imperial).
 *
 * This is the most generic length class. Use this when you need to work with
 * any length unit regardless of the measurement system.
 *
 * For system-specific types, use:
 * - MetricLength::of($number, MetricLengthUnit::Meter)
 * - ImperialLength::of($number, ImperialLengthUnit::Foot)
 *
 * For unit-specific types, use:
 * - Meter::of($number)
 * - Foot::of($number)
 *
 * Example:
 * ```php
 * $length = Length::of($number, MetricLengthUnit::Meter);
 * $length = Length::of($number, ImperialLengthUnit::Foot);
 * ```
 */
final class Length implements LengthInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a length with the specified value and unit.
     */
    public static function of(NumberInterface $value, UnitInterface $unit): self
    {
        if ($unit->dimension() !== LengthDimension::instance()) {
            throw InvalidUnitException::forInvalidDimension($unit, LengthDimension::instance(), self::class);
        }

        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit's dimension is not Length
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if ($unit->dimension() !== LengthDimension::instance()) {
            throw InvalidUnitException::forInvalidDimension($unit, LengthDimension::instance(), self::class);
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
