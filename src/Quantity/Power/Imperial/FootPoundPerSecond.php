<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Power\Imperial;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Power\ImperialPowerInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Unit\Power\ImperialPowerUnit;

/**
 * Foot-pound-force per second quantity (ft⋅lbf/s).
 *
 * 1 ft⋅lbf/s = 1.3558179483314 W
 *
 * Traditional imperial unit of power.
 * 1 hp (mechanical) = 550 ft⋅lbf/s
 */
final class FootPoundPerSecond implements ImperialPowerInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a foot-pound-force per second quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, ImperialPowerUnit::FootPoundPerSecond);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not ImperialPowerUnit::FootPoundPerSecond
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ImperialPowerUnit::FootPoundPerSecond !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ImperialPowerUnit::FootPoundPerSecond, self::class);
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
