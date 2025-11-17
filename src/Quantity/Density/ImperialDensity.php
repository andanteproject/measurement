<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Density;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Density\ImperialDensityInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Density\ImperialDensityUnit;

/**
 * Mid-level imperial density quantity - accepts any imperial density unit.
 *
 * Use this class when you need to ensure imperial units (lb/ft³, lb/in³, etc.)
 * but don't want to restrict to a specific unit.
 *
 * Example:
 * ```php
 * $density = ImperialDensity::of(NumberFactory::create('62.4'), ImperialDensityUnit::PoundPerCubicFoot);
 * ```
 */
final class ImperialDensity implements ImperialDensityInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an imperial density quantity.
     *
     * @throws InvalidUnitException If unit is not an imperial density unit
     */
    public static function of(NumberInterface $value, ImperialDensityUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not an imperial density unit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof ImperialDensityUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, ImperialDensityUnit::class, self::class);
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
