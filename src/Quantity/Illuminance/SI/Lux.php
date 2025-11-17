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
 * Lux quantity (SI derived unit for illuminance).
 *
 * 1 lx = 1 lm/m² (lumen per square meter)
 * The lux measures how much luminous flux is spread over a given area.
 */
final class Lux implements IlluminanceInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a lux quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, IlluminanceUnit::Lux);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not IlluminanceUnit::Lux
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (IlluminanceUnit::Lux !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, IlluminanceUnit::Lux, self::class);
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
