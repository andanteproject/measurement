<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Digital\DataTransferRate\IEC;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Digital\IECByteTransferRateInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Digital\IEC\IECByteTransferRateUnit;

/**
 * IEC (binary) byte-based data transfer rate quantity that can hold any IEC byte rate unit.
 *
 * This is the "mid-level" class for IEC byte-based transfer rates. Use this when you need
 * to work with any IEC byte rate unit without knowing the specific unit at compile time.
 *
 * For type-safe quantities with a specific unit, use the concrete classes:
 * - KibibytePerSecond::of($number)
 * - MebibytePerSecond::of($number)
 * - GibibytePerSecond::of($number)
 *
 * Example:
 * ```php
 * $speed = ByteTransferRate::of($number, IECByteTransferRateUnit::MebibytePerSecond);
 * $speed = ByteTransferRate::of($number, IECByteTransferRateUnit::GibibytePerSecond);
 * ```
 */
final class ByteTransferRate implements IECByteTransferRateInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an IEC byte transfer rate quantity with the specified value and unit.
     */
    public static function of(NumberInterface $value, IECByteTransferRateUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not an IECByteTransferRateUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof IECByteTransferRateUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, IECByteTransferRateUnit::class, self::class);
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
