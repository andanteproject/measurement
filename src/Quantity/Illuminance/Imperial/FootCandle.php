<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Illuminance\Imperial;

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
 * Foot-candle quantity (imperial unit for illuminance).
 *
 * 1 fc = 1 lm/ft² (lumen per square foot)
 * 1 fc = 10.7639 lx
 * Commonly used in photography, film, and building codes in the US.
 */
final class FootCandle implements IlluminanceInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a foot-candle quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, IlluminanceUnit::FootCandle);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not IlluminanceUnit::FootCandle
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (IlluminanceUnit::FootCandle !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, IlluminanceUnit::FootCandle, self::class);
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
