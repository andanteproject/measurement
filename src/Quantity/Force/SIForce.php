<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Force;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Force\SIForceInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Force\SIForceUnit;

/**
 * Mid-level SI force quantity - accepts any SI force unit.
 *
 * Use this class when you need to ensure SI units (N, kN, etc.)
 * but don't want to restrict to a specific unit.
 *
 * Example:
 * ```php
 * $force = SIForce::of(NumberFactory::create('1000'), SIForceUnit::Newton);
 * ```
 */
final class SIForce implements SIForceInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an SI force quantity.
     *
     * @throws InvalidUnitException If unit is not an SI force unit
     */
    public static function of(NumberInterface $value, SIForceUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not an SI force unit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof SIForceUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, SIForceUnit::class, self::class);
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
