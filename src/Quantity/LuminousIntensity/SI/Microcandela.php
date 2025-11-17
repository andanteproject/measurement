<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\LuminousIntensity\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\LuminousIntensity\LuminousIntensityInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\LuminousIntensity\LuminousIntensityUnit;

/**
 * Microcandela quantity.
 *
 * 1 μcd = 0.000001 cd
 * Used for very dim light sources and sensors.
 */
final class Microcandela implements LuminousIntensityInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a microcandela quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, LuminousIntensityUnit::Microcandela);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not LuminousIntensityUnit::Microcandela
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (LuminousIntensityUnit::Microcandela !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, LuminousIntensityUnit::Microcandela, self::class);
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
