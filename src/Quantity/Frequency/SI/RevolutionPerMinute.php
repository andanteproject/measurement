<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Frequency\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Frequency\FrequencyInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Frequency\FrequencyUnit;

/**
 * Revolution per minute (RPM) quantity.
 *
 * 1 rpm = 1/60 Hz
 * Common for rotational speeds of engines, motors, and hard drives.
 */
final class RevolutionPerMinute implements FrequencyInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a revolution per minute quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, FrequencyUnit::RevolutionPerMinute);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not FrequencyUnit::RevolutionPerMinute
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (FrequencyUnit::RevolutionPerMinute !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, FrequencyUnit::RevolutionPerMinute, self::class);
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
