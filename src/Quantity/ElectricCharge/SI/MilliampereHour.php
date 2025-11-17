<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\ElectricCharge\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\ElectricCharge\ElectricChargeInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\ElectricCharge\ElectricChargeUnit;

/**
 * Milliampere-hour quantity (common unit for small battery capacity).
 *
 * 1 mAh = 3.6 C (one milliampere flowing for one hour)
 * Commonly used for rating smartphone and small device batteries.
 */
final class MilliampereHour implements ElectricChargeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a milliampere-hour quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ElectricChargeUnit::MilliampereHour);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ElectricChargeUnit::MilliampereHour
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ElectricChargeUnit::MilliampereHour !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ElectricChargeUnit::MilliampereHour, self::class);
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
