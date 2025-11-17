<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\MagneticFlux\SI;

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
 * Weber quantity (SI derived unit for magnetic flux).
 *
 * 1 Wb = 1 V⋅s = 1 kg⋅m²/(A⋅s²)
 * The weber is the magnetic flux that, linking a circuit of one turn,
 * produces an electromotive force of one volt when reduced uniformly
 * to zero in one second.
 */
final class Weber implements MagneticFluxInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a weber quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, MagneticFluxUnit::Weber);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not MagneticFluxUnit::Weber
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (MagneticFluxUnit::Weber !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, MagneticFluxUnit::Weber, self::class);
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
