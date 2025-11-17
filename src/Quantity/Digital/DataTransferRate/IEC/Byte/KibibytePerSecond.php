<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Digital\DataTransferRate\IEC\Byte;

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
 * Kibibyte per second quantity.
 *
 * 1 KiB/s = 1024 B/s = 8192 bps (binary prefix)
 */
final class KibibytePerSecond implements IECByteTransferRateInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a KibibytePerSecond quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, IECByteTransferRateUnit::KibibytePerSecond);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not IECByteTransferRateUnit::KibibytePerSecond
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (IECByteTransferRateUnit::KibibytePerSecond !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, IECByteTransferRateUnit::KibibytePerSecond, self::class);
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
