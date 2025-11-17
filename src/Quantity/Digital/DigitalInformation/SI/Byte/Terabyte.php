<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Digital\DigitalInformation\SI\Byte;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Digital\SIDigitalInformationInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Digital\SI\SIDigitalUnit;

/**
 * Terabyte quantity.
 *
 * 1 TB = 1000 GB = 1,000,000,000,000 bytes (decimal/SI prefix)
 */
final class Terabyte implements SIDigitalInformationInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a Terabyte quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, SIDigitalUnit::Terabyte);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not SIDigitalUnit::Terabyte
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (SIDigitalUnit::Terabyte !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, SIDigitalUnit::Terabyte, self::class);
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
