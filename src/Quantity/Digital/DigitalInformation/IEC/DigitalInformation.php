<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Digital\DigitalInformation\IEC;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Digital\IECDigitalInformationInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Digital\IEC\IECDigitalUnit;

/**
 * IEC (binary) digital information quantity that can hold any IEC digital unit.
 *
 * This is the "mid-level" class for IEC digital information. Use this when you need
 * to work with any IEC digital unit without knowing the specific unit at compile time.
 *
 * For type-safe quantities with a specific unit, use the concrete classes:
 * - Kibibyte::of($number)
 * - Mebibyte::of($number)
 * - Gibibyte::of($number)
 *
 * Example:
 * ```php
 * $size = IECDigitalInformation::of($number, IECDigitalUnit::Mebibyte);
 * $size = IECDigitalInformation::of($number, IECDigitalUnit::Gibibyte);
 * ```
 */
final class DigitalInformation implements IECDigitalInformationInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an IEC digital information quantity with the specified value and unit.
     */
    public static function of(NumberInterface $value, IECDigitalUnit $unit): self
    {
        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not an IECDigitalUnit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit instanceof IECDigitalUnit) {
            throw InvalidUnitException::forInvalidUnitType($unit, IECDigitalUnit::class, self::class);
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
