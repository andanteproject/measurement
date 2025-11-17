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
 * Inch of mercury quantity (inHg).
 *
 * 1 inHg = 3386.389 Pa (at 0°C)
 *
 * Commonly used in aviation for barometric pressure and
 * in weather reports (US).
 */
final class InchOfMercury implements ImperialPressureInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an inch of mercury quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ImperialPressureUnit::InchOfMercury);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ImperialPressureUnit::InchOfMercury
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ImperialPressureUnit::InchOfMercury !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ImperialPressureUnit::InchOfMercury, self::class);
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
