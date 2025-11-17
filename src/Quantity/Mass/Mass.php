<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Mass;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Mass\MassInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Mass as MassDimension;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;

/**
 * Generic mass quantity that can hold any mass unit (metric or imperial).
 *
 * This is the most generic mass class. Use this when you need to work with
 * any mass unit regardless of the measurement system.
 *
 * For system-specific types, use:
 * - MetricMass::of($number, MetricMassUnit::Kilogram)
 * - ImperialMass::of($number, ImperialMassUnit::Pound)
 *
 * For unit-specific types, use:
 * - Kilogram::of($number)
 * - Pound::of($number)
 *
 * Example:
 * ```php
 * $mass = Mass::of($number, MetricMassUnit::Kilogram);
 * $mass = Mass::of($number, ImperialMassUnit::Pound);
 * ```
 */
final class Mass implements MassInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a mass with the specified value and unit.
     */
    public static function of(NumberInterface $value, UnitInterface $unit): self
    {
        if ($unit->dimension() !== MassDimension::instance()) {
            throw InvalidUnitException::forInvalidDimension($unit, MassDimension::instance(), self::class);
        }

        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit's dimension is not Mass
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if ($unit->dimension() !== MassDimension::instance()) {
            throw InvalidUnitException::forInvalidDimension($unit, MassDimension::instance(), self::class);
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
