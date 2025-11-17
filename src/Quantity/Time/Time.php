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
use Andante\Measurement\Dimension\Time as TimeDimension;
use Andante\Measurement\Exception\InvalidUnitException;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Quantity\Trait\DateIntervalConvertibleTrait;
use Andante\Measurement\Unit\Time\TimeUnit;

/**
 * Generic time quantity that can hold any time unit.
 *
 * Use this class when you need to work with time values where the specific
 * unit may vary or is determined at runtime.
 *
 * Example:
 * ```php
 * $duration = Time::of(NumberFactory::create('30'), TimeUnit::Minute);
 * $interval = $duration->toPhpDateInterval(); // 30 minutes
 * ```
 */
final class Time implements TimeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a Time quantity with a specific unit.
     *
     * @throws InvalidUnitException If unit is not a time unit
     */
    public static function of(NumberInterface $value, UnitInterface $unit): self
    {
        if (!$unit->dimension()->isCompatibleWith(TimeDimension::instance())) {
            throw InvalidUnitException::forInvalidDimension($unit, TimeDimension::instance(), self::class);
        }

        return new self($value, $unit);
    }

    /**
     * Create a Time quantity from a PHP DateInterval.
     *
     * Returns a Time in seconds.
     * Note: Years and months are ignored as they don't have fixed durations.
     */
    public static function ofPhpDateInterval(\DateInterval $interval): self
    {
        $totalSeconds = self::dateIntervalToSeconds($interval);

        return self::of(NumberFactory::create($totalSeconds), TimeUnit::Second);
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not a time unit
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
