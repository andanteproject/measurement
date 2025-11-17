<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Time;

use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Quantity\Time\TimeInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Quantity\Trait\DateIntervalConvertibleTrait;
use Andante\Measurement\Unit\Time\TimeUnit;

/**
 * Microsecond quantity.
 *
 * 1 μs = 0.000001 s = 10⁻⁶ s
 */
final class Microsecond implements TimeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
{
    use ConvertibleTrait;
    use ComparableTrait;
    use CalculableTrait;
    use AutoScalableTrait;
    use DateIntervalConvertibleTrait;

    private function __construct(
        private readonly NumberInterface $value,
        private readonly UnitInterface $unit,
    ) {
    }

    /**
     * Create a Microsecond quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, TimeUnit::Microsecond);
    }

    /**
     * Create a Microsecond quantity from a PHP DateInterval.
     *
     * Note: Years and months are ignored as they don't have fixed durations.
     */
    public static function ofPhpDateInterval(\DateInterval $interval): self
    {
        $totalSeconds = self::dateIntervalToSeconds($interval);
        $microseconds = (float) $totalSeconds * 1000000;

        return self::of(NumberFactory::create((string) $microseconds));
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not TimeUnit::Microsecond
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (TimeUnit::Microsecond !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, TimeUnit::Microsecond, self::class);
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
