<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Volume\Imperial;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Volume\ImperialVolumeInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Volume\ImperialVolumeUnit;

/**
 * Imperial fluid ounce quantity.
 *
 * 1 imp fl oz = 28.4130625 mL = 0.0000284130625 m³
 */
final class ImperialFluidOunce implements ImperialVolumeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an ImperialFluidOunce quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ImperialVolumeUnit::ImperialFluidOunce);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ImperialVolumeUnit::ImperialFluidOunce
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ImperialVolumeUnit::ImperialFluidOunce !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ImperialVolumeUnit::ImperialFluidOunce, self::class);
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
