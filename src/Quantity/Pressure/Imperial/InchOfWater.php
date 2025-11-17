<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Pressure\Imperial;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Pressure\ImperialPressureInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Pressure\ImperialPressureUnit;

/**
 * Inch of water quantity (inH₂O, iwg, wc).
 *
 * 1 inH₂O = 249.089 Pa (at 4°C)
 *
 * Used for low pressure differentials in HVAC systems,
 * duct pressure, and gas pressure measurement.
 */
final class InchOfWater implements ImperialPressureInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an inch of water quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ImperialPressureUnit::InchOfWater);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ImperialPressureUnit::InchOfWater
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ImperialPressureUnit::InchOfWater !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ImperialPressureUnit::InchOfWater, self::class);
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
