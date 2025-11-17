<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Power\Imperial;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Power\ImperialPowerInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Power\ImperialPowerUnit;

/**
 * Metric horsepower quantity (PS - Pferdestärke).
 *
 * 1 PS = 735.49875 W
 *
 * Used in Europe for automotive power ratings.
 * Also known as CV (cavallo vapore), ch (cheval-vapeur),
 * or pk (paardenkracht).
 */
final class MetricHorsepower implements ImperialPowerInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a metric horsepower quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ImperialPowerUnit::MetricHorsepower);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ImperialPowerUnit::MetricHorsepower
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ImperialPowerUnit::MetricHorsepower !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ImperialPowerUnit::MetricHorsepower, self::class);
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
