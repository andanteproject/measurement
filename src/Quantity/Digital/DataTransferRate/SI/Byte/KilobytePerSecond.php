<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Digital\DataTransferRate\SI\Byte;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Digital\ByteTransferRateInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Digital\SI\ByteTransferRateUnit;

/**
 * Kilobyte per second quantity.
 *
 * 1 KB/s = 1000 B/s = 8000 bps
 */
final class KilobytePerSecond implements ByteTransferRateInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a KilobytePerSecond quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ByteTransferRateUnit::KilobytePerSecond);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ByteTransferRateUnit::KilobytePerSecond
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ByteTransferRateUnit::KilobytePerSecond !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ByteTransferRateUnit::KilobytePerSecond, self::class);
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
