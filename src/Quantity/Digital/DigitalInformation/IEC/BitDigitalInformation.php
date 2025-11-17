<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Digital\DigitalInformation\IEC;

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
 * IEC bit-based digital information quantity that can hold any IEC bit unit.
 *
 * This is the "mid-level" class for IEC bit-based digital information. Use this when you need
 * to work with any IEC bit unit without knowing the specific unit at compile time.
 *
 * For type-safe quantities with a specific unit, use the concrete classes:
 * - Kibibit::of($number)
 * - Mebibit::of($number)
 * - Gibibit::of($number)
 *
 * Example:
 * ```php
 * $info = BitDigitalInformation::of($number, IECBitUnit::Mebibit);
 * $info = BitDigitalInformation::of($number, IECBitUnit::Gibibit);
 * ```
 */
final class BitDigitalInformation implements IECBitDigitalInformationInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an IEC bit digital information quantity with the specified value and unit.
     */
    public static function of(NumberInterface $value, IECBitUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not an IECBitUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof IECBitUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, IECBitUnit::class, self::class);
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
