<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Digital\DigitalInformation;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Digital\DigitalInformationInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DigitalInformation as DigitalInformationDimension;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;

/**
 * Generic digital information quantity that can hold any digital unit.
 *
 * Use this class when you need to work with digital information values
 * where the specific unit may vary or is determined at runtime.
 *
 * Example:
 * ```php
 * $size = DigitalInformation::of(NumberFactory::create('500'), DigitalUnit::Megabyte);
 * $inGb = $size->to(DigitalUnit::Gigabyte); // 0.5 GB
 * ```
 */
final class DigitalInformation implements DigitalInformationInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a DigitalInformation quantity with a specific unit.
     *
     * @throws InvalidUnitException If unit is not a digital information unit
     */
    public static function of(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit->dimension()->isCompatibleWith(DigitalInformationDimension::instance())) {
            throw InvalidUnitException::forInvalidDimension($unit, DigitalInformationDimension::instance(), self::class);
        }

        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not a digital information unit
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        return self::of($value, $unit);
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
