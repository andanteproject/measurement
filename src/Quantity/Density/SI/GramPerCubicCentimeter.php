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
 * Gram per cubic centimeter quantity.
 *
 * 1 g/cm³ = 1000 kg/m³
 * Common for expressing density of solids and liquids.
 * Water has a density of approximately 1 g/cm³.
 */
final class GramPerCubicCentimeter implements SIDensityInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a gram per cubic centimeter quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, SIDensityUnit::GramPerCubicCentimeter);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not SIDensityUnit::GramPerCubicCentimeter
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (SIDensityUnit::GramPerCubicCentimeter !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, SIDensityUnit::GramPerCubicCentimeter, self::class);
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
