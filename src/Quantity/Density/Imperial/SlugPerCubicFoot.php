<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Density\Imperial;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Density\ImperialDensityInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Density\ImperialDensityUnit;

/**
 * Slug per cubic foot quantity.
 *
 * 1 slug/ft³ = 515.379 kg/m³
 * Used in engineering and fluid dynamics.
 * The slug is the imperial unit of mass in the foot-pound-second system.
 */
final class SlugPerCubicFoot implements ImperialDensityInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a slug per cubic foot quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ImperialDensityUnit::SlugPerCubicFoot);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ImperialDensityUnit::SlugPerCubicFoot
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ImperialDensityUnit::SlugPerCubicFoot !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ImperialDensityUnit::SlugPerCubicFoot, self::class);
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
