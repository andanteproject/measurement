<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Velocity\Imperial;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Velocity\ImperialVelocityInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Velocity\ImperialVelocityUnit;

/**
 * Mile per hour quantity.
 *
 * 1 mph = 1.60934 km/h = 0.44704 m/s
 * Standard unit for vehicle speeds in the US and UK.
 */
final class MilePerHour implements ImperialVelocityInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a mile per hour quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ImperialVelocityUnit::MilePerHour);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ImperialVelocityUnit::MilePerHour
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ImperialVelocityUnit::MilePerHour !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ImperialVelocityUnit::MilePerHour, self::class);
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
