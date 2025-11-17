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
 * Standard cubic foot (scf) quantity.
 *
 * Gas volume at US standard conditions: 60°F (15.56°C), 14.696 psi.
 * Common in US natural gas industry.
 *
 * 1 scf ≈ 0.02832 Smc
 */
final class StandardCubicFoot implements GasVolumeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a StandardCubicFoot quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, GasVolumeUnit::StandardCubicFoot);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not GasVolumeUnit::StandardCubicFoot
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (GasVolumeUnit::StandardCubicFoot !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, GasVolumeUnit::StandardCubicFoot, self::class);
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
