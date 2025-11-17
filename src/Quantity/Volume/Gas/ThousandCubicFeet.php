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
 * Thousand cubic feet (Mcf) quantity.
 *
 * 1 Mcf = 1000 scf (standard cubic feet)
 * Common unit for natural gas billing in the US.
 *
 * 1 Mcf ≈ 28.32 Smc
 */
final class ThousandCubicFeet implements GasVolumeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a ThousandCubicFeet quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, GasVolumeUnit::ThousandCubicFeet);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not GasVolumeUnit::ThousandCubicFeet
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (GasVolumeUnit::ThousandCubicFeet !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, GasVolumeUnit::ThousandCubicFeet, self::class);
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
