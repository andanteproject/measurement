<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Density\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Density\SIDensityInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Density\SIDensityUnit;

/**
 * Gram per cubic meter quantity.
 *
 * 1 g/m³ = 0.001 kg/m³
 */
final class GramPerCubicMeter implements SIDensityInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a gram per cubic meter quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, SIDensityUnit::GramPerCubicMeter);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not SIDensityUnit::GramPerCubicMeter
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (SIDensityUnit::GramPerCubicMeter !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, SIDensityUnit::GramPerCubicMeter, self::class);
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
