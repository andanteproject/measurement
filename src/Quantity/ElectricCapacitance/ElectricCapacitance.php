<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\ElectricCapacitance;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\ElectricCapacitance\ElectricCapacitanceInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\ElectricCapacitance as ElectricCapacitanceDimension;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;

/**
 * Generic electric capacitance quantity - accepts any electric capacitance unit.
 *
 * Use this class when you need to work with any electric capacitance unit.
 *
 * Example:
 * ```php
 * $capacitance = ElectricCapacitance::of(NumberFactory::create('100'), ElectricCapacitanceUnit::Microfarad);
 * ```
 */
final class ElectricCapacitance implements ElectricCapacitanceInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create an electric capacitance quantity with any electric capacitance unit.
     */
    public static function of(NumberInterface $value, UnitInterface $unit): self
    {
        if ($unit->dimension() !== ElectricCapacitanceDimension::instance()) {
            throw InvalidUnitException::forInvalidDimension($unit, ElectricCapacitanceDimension::instance(), self::class);
        }

        return new self($value, $unit);
    }

    /**
     * @internal Used by the library for conversions and calculations
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
