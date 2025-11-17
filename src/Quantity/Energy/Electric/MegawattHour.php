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
 * Megawatt-hour quantity.
 *
 * 1 MWh = 1000 kWh = 1,000,000 Wh = 3,600,000,000 J
 *
 * Commonly used for measuring large-scale electrical energy consumption
 * in industrial contexts or power generation.
 */
final class MegawattHour implements ElectricEnergyInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a MegawattHour quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ElectricEnergyUnit::MegawattHour);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ElectricEnergyUnit::MegawattHour
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ElectricEnergyUnit::MegawattHour !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ElectricEnergyUnit::MegawattHour, self::class);
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
