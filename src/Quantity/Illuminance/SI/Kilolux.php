<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Illuminance\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Illuminance\IlluminanceInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Illuminance\IlluminanceUnit;

/**
 * Kilolux quantity.
 *
 * 1 klx = 1000 lx
 * Used for very bright conditions like direct sunlight (~100 klx).
 */
final class Kilolux implements IlluminanceInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a kilolux quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, IlluminanceUnit::Kilolux);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not IlluminanceUnit::Kilolux
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (IlluminanceUnit::Kilolux !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, IlluminanceUnit::Kilolux, self::class);
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
