<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Density\Imperial;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Density\ImperialDensityInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Density\ImperialDensityUnit;

/**
 * Pound per cubic inch quantity.
 *
 * 1 lb/in³ = 27,679.9 kg/m³
 * Used for dense materials like metals.
 */
final class PoundPerCubicInch implements ImperialDensityInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a pound per cubic inch quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ImperialDensityUnit::PoundPerCubicInch);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ImperialDensityUnit::PoundPerCubicInch
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ImperialDensityUnit::PoundPerCubicInch !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ImperialDensityUnit::PoundPerCubicInch, self::class);
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
