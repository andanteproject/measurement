<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Volume\Gas;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Volume\GasVolumeInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Volume\GasVolumeUnit;

/**
 * Standard cubic meter (Smc) quantity.
 *
 * Gas volume at standard conditions: 15°C, 101.325 kPa.
 * Commonly used in Europe (especially Italy) for natural gas billing.
 *
 * Note: 1 Smc ≈ 1.0548 Nmc (due to different reference temperatures)
 */
final class StandardCubicMeter implements GasVolumeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a StandardCubicMeter quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, GasVolumeUnit::StandardCubicMeter);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not GasVolumeUnit::StandardCubicMeter
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (GasVolumeUnit::StandardCubicMeter !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, GasVolumeUnit::StandardCubicMeter, self::class);
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
