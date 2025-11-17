<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Digital\DataTransferRate\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Digital\BitTransferRateInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Digital\SI\BitTransferRateUnit;

/**
 * SI bit-based data transfer rate quantity that can hold any SI bit rate unit.
 *
 * This is the "mid-level" class for SI bit-based transfer rates. Use this when you need
 * to work with any SI bit rate unit without knowing the specific unit at compile time.
 *
 * For type-safe quantities with a specific unit, use the concrete classes:
 * - BitPerSecond::of($number)
 * - KilobitPerSecond::of($number)
 * - MegabitPerSecond::of($number)
 *
 * Example:
 * ```php
 * $speed = BitTransferRate::of($number, BitTransferRateUnit::MegabitPerSecond);
 * $speed = BitTransferRate::of($number, BitTransferRateUnit::GigabitPerSecond);
 * ```
 */
final class BitTransferRate implements BitTransferRateInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a bit transfer rate quantity with the specified value and unit.
     */
    public static function of(NumberInterface $value, BitTransferRateUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not a BitTransferRateUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof BitTransferRateUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, BitTransferRateUnit::class, self::class);
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
