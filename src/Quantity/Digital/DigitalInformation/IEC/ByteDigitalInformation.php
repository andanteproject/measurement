<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Digital\DigitalInformation\IEC;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Digital\IECByteDigitalInformationInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Digital\IEC\IECByteUnit;

/**
 * IEC byte-based digital information quantity that can hold any IEC byte unit.
 *
 * This is the "mid-level" class for IEC byte-based digital information. Use this when you need
 * to work with any IEC byte unit without knowing the specific unit at compile time.
 *
 * For type-safe quantities with a specific unit, use the concrete classes:
 * - Kibibyte::of($number)
 * - Mebibyte::of($number)
 * - Gibibyte::of($number)
 *
 * Example:
 * ```php
 * $info = ByteDigitalInformation::of($number, IECByteUnit::Mebibyte);
 * $info = ByteDigitalInformation::of($number, IECByteUnit::Gibibyte);
 * ```
 */
final class ByteDigitalInformation implements IECByteDigitalInformationInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an IEC byte digital information quantity with the specified value and unit.
     */
    public static function of(NumberInterface $value, IECByteUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not an IECByteUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof IECByteUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, IECByteUnit::class, self::class);
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
