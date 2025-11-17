<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Length;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Length\ImperialLengthInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Length\ImperialLengthUnit;

/**
 * Generic imperial length quantity that can hold any imperial length unit.
 *
 * This is the "mid-level" class for imperial lengths. Use this when you need
 * to work with any imperial length unit without knowing the specific unit at
 * compile time.
 *
 * For type-safe quantities with a specific unit, use the concrete classes:
 * - Foot::of($number)
 * - Inch::of($number)
 * - Mile::of($number)
 *
 * Example:
 * ```php
 * $length = ImperialLength::of($number, ImperialLengthUnit::Foot);
 * $length = ImperialLength::of($number, ImperialLengthUnit::Inch);
 * ```
 */
final class ImperialLength implements ImperialLengthInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an imperial length with the specified value and unit.
     */
    public static function of(NumberInterface $value, ImperialLengthUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not an ImperialLengthUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof ImperialLengthUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, ImperialLengthUnit::class, self::class);
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
