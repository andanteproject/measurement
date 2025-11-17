<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Area;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Area\ImperialAreaInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Area\ImperialAreaUnit;

/**
 * Generic imperial area quantity that can hold any imperial area unit.
 *
 * This is the "mid-level" class for imperial areas. Use this when you need
 * to work with any imperial area unit without knowing the specific unit at
 * compile time.
 *
 * For type-safe quantities with a specific unit, use the concrete classes:
 * - SquareFoot::of($number)
 * - SquareYard::of($number)
 * - Acre::of($number)
 *
 * Example:
 * ```php
 * $area = ImperialArea::of($number, ImperialAreaUnit::SquareFoot);
 * $area = ImperialArea::of($number, ImperialAreaUnit::Acre);
 * ```
 */
final class ImperialArea implements ImperialAreaInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an imperial area with the specified value and unit.
     */
    public static function of(NumberInterface $value, ImperialAreaUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not an ImperialAreaUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof ImperialAreaUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, ImperialAreaUnit::class, self::class);
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
