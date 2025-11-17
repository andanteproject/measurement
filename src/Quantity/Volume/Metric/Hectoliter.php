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
 * Hectoliter quantity.
 *
 * 1 hL = 100 L = 0.1 m³
 */
final class Hectoliter implements MetricVolumeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a Hectoliter quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, MetricVolumeUnit::Hectoliter);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not MetricVolumeUnit::Hectoliter
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (MetricVolumeUnit::Hectoliter !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, MetricVolumeUnit::Hectoliter, self::class);
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
