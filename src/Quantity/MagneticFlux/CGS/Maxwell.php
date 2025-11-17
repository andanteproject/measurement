<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\MagneticFlux\CGS;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\MagneticFlux\MagneticFluxInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\MagneticFlux\MagneticFluxUnit;

/**
 * Maxwell quantity (CGS unit for magnetic flux).
 *
 * 1 Mx = 10⁻⁸ Wb
 * Named after James Clerk Maxwell, the maxwell is the CGS unit of magnetic flux.
 */
final class Maxwell implements MagneticFluxInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a maxwell quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, MagneticFluxUnit::Maxwell);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not MagneticFluxUnit::Maxwell
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (MagneticFluxUnit::Maxwell !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, MagneticFluxUnit::Maxwell, self::class);
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
