<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Force\Imperial;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Force\ImperialForceInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Force\ImperialForceUnit;

/**
 * Poundal quantity (absolute unit in FPS system).
 *
 * 1 pdl = 0.138255 N (force to accelerate 1 lb at 1 ft/s²)
 *
 * The poundal is the force needed to accelerate 1 pound mass
 * at 1 foot per second squared.
 */
final class Poundal implements ImperialForceInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a poundal quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ImperialForceUnit::Poundal);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ImperialForceUnit::Poundal
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ImperialForceUnit::Poundal !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ImperialForceUnit::Poundal, self::class);
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
