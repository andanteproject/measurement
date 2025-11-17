<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Power;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Power\PowerInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Power as PowerDimension;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;

/**
 * Generic power quantity - accepts any power unit.
 *
 * Use this class when you need to work with any power unit
 * regardless of system (SI or imperial).
 *
 * Example:
 * ```php
 * $power = Power::of(NumberFactory::create('1000'), SIPowerUnit::Watt);
 * ```
 */
final class Power implements PowerInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a power quantity with any power unit.
     */
    public static function of(NumberInterface $value, UnitInterface $unit): self
    {
        if ($unit->dimension() !== PowerDimension::instance()) {
            throw InvalidUnitException::forInvalidDimension($unit, PowerDimension::instance(), self::class);
        }

        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        return self::of($value, $unit);
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
