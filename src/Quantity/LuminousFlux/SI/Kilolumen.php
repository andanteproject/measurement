<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\LuminousFlux\SI;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\LuminousFlux\LuminousFluxInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\LuminousFlux\LuminousFluxUnit;

/**
 * Kilolumen quantity.
 *
 * 1 klm = 1000 lm
 * Used for high-output light sources like stadium lighting.
 */
final class Kilolumen implements LuminousFluxInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a kilolumen quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, LuminousFluxUnit::Kilolumen);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not LuminousFluxUnit::Kilolumen
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (LuminousFluxUnit::Kilolumen !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, LuminousFluxUnit::Kilolumen, self::class);
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
