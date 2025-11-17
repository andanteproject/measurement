<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Volume\Metric;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Volume\MetricVolumeInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Volume\MetricVolumeUnit;

/**
 * Kiloliter quantity.
 *
 * 1 kL = 1000 L = 1 m³
 */
final class Kiloliter implements MetricVolumeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a Kiloliter quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, MetricVolumeUnit::Kiloliter);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not MetricVolumeUnit::Kiloliter
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (MetricVolumeUnit::Kiloliter !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, MetricVolumeUnit::Kiloliter, self::class);
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
