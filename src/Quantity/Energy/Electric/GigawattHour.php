<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Energy\Electric;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Energy\ElectricEnergyInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Energy\ElectricEnergyUnit;

/**
 * Gigawatt-hour quantity.
 *
 * 1 GWh = 1000 MWh = 1,000,000 kWh
 *
 * Used for measuring very large-scale electrical energy,
 * such as national or regional power consumption.
 */
final class GigawattHour implements ElectricEnergyInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a GigawattHour quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ElectricEnergyUnit::GigawattHour);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ElectricEnergyUnit::GigawattHour
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ElectricEnergyUnit::GigawattHour !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ElectricEnergyUnit::GigawattHour, self::class);
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
