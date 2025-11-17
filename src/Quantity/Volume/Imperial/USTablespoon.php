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
 * US tablespoon quantity.
 *
 * 1 US tbsp = 14.78676478125 mL = 0.00001478676478125 m³
 */
final class USTablespoon implements ImperialVolumeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a USTablespoon quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ImperialVolumeUnit::USTablespoon);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ImperialVolumeUnit::USTablespoon
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ImperialVolumeUnit::USTablespoon !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ImperialVolumeUnit::USTablespoon, self::class);
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
