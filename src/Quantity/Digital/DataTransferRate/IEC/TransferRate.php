<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Digital\DataTransferRate\IEC;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Digital\IECTransferRateInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Digital\IEC\IECTransferRateUnit;

/**
 * IEC (binary) data transfer rate quantity that can hold any IEC transfer rate unit.
 *
 * This is the "mid-level" class for IEC transfer rates. Use this when you need
 * to work with any IEC transfer rate unit (bit or byte based) without knowing
 * the specific unit at compile time.
 *
 * For more specific mid-level classes:
 * - BitTransferRate - only IEC bit-based rates
 * - ByteTransferRate - only IEC byte-based rates
 *
 * Example:
 * ```php
 * $speed = TransferRate::of($number, IECTransferRateUnit::MebibitPerSecond);
 * $speed = TransferRate::of($number, IECTransferRateUnit::MebibytePerSecond);
 * ```
 */
final class TransferRate implements IECTransferRateInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an IEC transfer rate quantity with the specified value and unit.
     */
    public static function of(NumberInterface $value, IECTransferRateUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not an IECTransferRateUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof IECTransferRateUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, IECTransferRateUnit::class, self::class);
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
