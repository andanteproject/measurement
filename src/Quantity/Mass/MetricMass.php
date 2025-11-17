<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Mass;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Mass\MetricMassInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Mass\MetricMassUnit;

/**
 * Generic metric mass quantity that can hold any metric mass unit.
 *
 * This is the "mid-level" class for metric masses. Use this when you need
 * to work with any metric mass unit without knowing the specific unit at
 * compile time.
 *
 * For type-safe quantities with a specific unit, use the concrete classes:
 * - Kilogram::of($number)
 * - Gram::of($number)
 * - Milligram::of($number)
 *
 * Example:
 * ```php
 * $mass = MetricMass::of($number, MetricMassUnit::Kilogram);
 * $mass = MetricMass::of($number, MetricMassUnit::Gram);
 * ```
 */
final class MetricMass implements MetricMassInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a metric mass with the specified value and unit.
     */
    public static function of(NumberInterface $value, MetricMassUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not a MetricMassUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof MetricMassUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, MetricMassUnit::class, self::class);
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
