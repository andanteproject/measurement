<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\Bit;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Digital\IECBitDigitalInformationInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Digital\IEC\IECBitUnit;

/**
 * Gibibit quantity.
 *
 * 1 gibibit = 1,073,741,824 bits (IEC binary prefix, 2^30)
 */
final class Gibibit implements IECBitDigitalInformationInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a Gibibit quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, IECBitUnit::Gibibit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not IECBitUnit::Gibibit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (IECBitUnit::Gibibit !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, IECBitUnit::Gibibit, self::class);
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
