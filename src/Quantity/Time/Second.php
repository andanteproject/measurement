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
 * Second quantity - the SI base unit of time.
 *
 * 1 s is the duration of 9,192,631,770 periods of radiation corresponding
 * to the transition between two hyperfine levels of caesium-133.
 */
final class Second implements TimeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a Second quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, TimeUnit::Second);
    }

    /**
     * Create a Second quantity from a PHP DateInterval.
     *
     * Note: Years and months are ignored as they don't have fixed durations.
     *
     * Example:
     * ```php
     * $interval = new \DateInterval('PT1H30M');
     * $seconds = Second::ofPhpDateInterval($interval);
     * // $seconds->getValue()->value() = '5400'
     * ```
     */
    public static function ofPhpDateInterval(\DateInterval $interval): self
    {
        $totalSeconds = self::dateIntervalToSeconds($interval);

        return self::of(NumberFactory::create($totalSeconds));
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not TimeUnit::Second
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (TimeUnit::Second !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, TimeUnit::Second, self::class);
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
