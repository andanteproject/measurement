<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\CalorificValue\Imperial;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\CalorificValue\ImperialCalorificValueInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\CalorificValue\ImperialCalorificValueUnit;

/**
 * Therm per cubic foot quantity.
 *
 * 1 therm = 100,000 BTU
 * 1 therm/ft³ = 100,000 BTU/ft³ ≈ 3,725,894,600 J/m³
 */
final class ThermPerCubicFoot implements ImperialCalorificValueInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a therm per cubic foot quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ImperialCalorificValueUnit::ThermPerCubicFoot);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ImperialCalorificValueUnit::ThermPerCubicFoot
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ImperialCalorificValueUnit::ThermPerCubicFoot !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ImperialCalorificValueUnit::ThermPerCubicFoot, self::class);
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
