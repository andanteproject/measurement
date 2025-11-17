<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Mass;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Mass\ImperialMassInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Mass\ImperialMassUnit;

/**
 * Generic imperial mass quantity that can hold any imperial mass unit.
 *
 * This is the "mid-level" class for imperial masses. Use this when you need
 * to work with any imperial mass unit without knowing the specific unit at
 * compile time.
 *
 * For type-safe quantities with a specific unit, use the concrete classes:
 * - Pound::of($number)
 * - Ounce::of($number)
 * - Stone::of($number)
 *
 * Example:
 * ```php
 * $mass = ImperialMass::of($number, ImperialMassUnit::Pound);
 * $mass = ImperialMass::of($number, ImperialMassUnit::Ounce);
 * ```
 */
final class ImperialMass implements ImperialMassInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an imperial mass with the specified value and unit.
     */
    public static function of(NumberInterface $value, ImperialMassUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not an ImperialMassUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof ImperialMassUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, ImperialMassUnit::class, self::class);
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
