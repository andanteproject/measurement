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
 * Ampere-hour quantity (common unit for battery capacity).
 *
 * 1 Ah = 3600 C (one ampere flowing for one hour)
 * Commonly used for rating battery capacity.
 */
final class AmpereHour implements ElectricChargeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an ampere-hour quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ElectricChargeUnit::AmpereHour);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ElectricChargeUnit::AmpereHour
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ElectricChargeUnit::AmpereHour !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ElectricChargeUnit::AmpereHour, self::class);
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
