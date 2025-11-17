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
 * Knot quantity (nautical mile per hour).
 *
 * 1 knot = 1 nautical mile/hour = 1.852 km/h = 0.51444 m/s = 1.15078 mph
 * Used in maritime and aviation navigation.
 */
final class Knot implements ImperialVelocityInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a knot quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ImperialVelocityUnit::Knot);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ImperialVelocityUnit::Knot
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ImperialVelocityUnit::Knot !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ImperialVelocityUnit::Knot, self::class);
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
