<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Digital\DataTransferRate;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Digital\DataTransferRateInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DataTransferRate as DataTransferRateDimension;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;

/**
 * Generic data transfer rate quantity that can hold any rate unit.
 *
 * Use this class when you need to work with data transfer rate values
 * where the specific unit may vary or is determined at runtime.
 *
 * Example:
 * ```php
 * $speed = DataTransferRate::of(NumberFactory::create('100'), DataTransferRateUnit::MegabitPerSecond);
 * $inMBps = $speed->to(DataTransferRateUnit::MegabytePerSecond); // 12.5 MB/s
 * ```
 */
final class DataTransferRate implements DataTransferRateInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a DataTransferRate quantity with a specific unit.
     *
     * @throws InvalidUnitException If unit is not a data transfer rate unit
     */
    public static function of(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit->dimension()->isCompatibleWith(DataTransferRateDimension::instance())) {
            throw InvalidUnitException::forInvalidDimension($unit, DataTransferRateDimension::instance(), self::class);
        }

        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not a data transfer rate unit
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
