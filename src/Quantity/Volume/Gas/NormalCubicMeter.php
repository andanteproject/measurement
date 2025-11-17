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
 * Normal cubic meter (Nmc) quantity.
 *
 * Gas volume at normal conditions: 0°C, 101.325 kPa (ISO standard).
 *
 * Note: 1 Nmc ≈ 0.9481 Smc (due to different reference temperatures)
 */
final class NormalCubicMeter implements GasVolumeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a NormalCubicMeter quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, GasVolumeUnit::NormalCubicMeter);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not GasVolumeUnit::NormalCubicMeter
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (GasVolumeUnit::NormalCubicMeter !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, GasVolumeUnit::NormalCubicMeter, self::class);
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
