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
 * Foot per second quantity.
 *
 * 1 ft/s = 0.3048 m/s = 1.09728 km/h = 0.68182 mph
 */
final class FootPerSecond implements ImperialVelocityInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a foot per second quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ImperialVelocityUnit::FootPerSecond);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ImperialVelocityUnit::FootPerSecond
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ImperialVelocityUnit::FootPerSecond !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ImperialVelocityUnit::FootPerSecond, self::class);
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
