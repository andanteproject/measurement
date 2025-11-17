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
 * Day quantity.
 *
 * 1 d = 24 h = 1440 min = 86400 s
 */
final class Day implements TimeInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface, AutoScalableInterface
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
     * Create a Day quantity.
     */
    public static function of(NumberInterface $value): self
    {
        return new self($value, TimeUnit::Day);
    }

    /**
     * Create a Day quantity from a PHP DateInterval.
     *
     * Note: Years and months are ignored as they don't have fixed durations.
     */
    public static function ofPhpDateInterval(\DateInterval $interval): self
    {
        $totalSeconds = self::dateIntervalToSeconds($interval);
        $days = (float) $totalSeconds / 86400;

        return self::of(NumberFactory::create((string) $days));
    }

    /**
     * @internal Used by the library for conversions and calculations
     *
     * @throws InvalidUnitException If unit is not TimeUnit::Day
     */
    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (TimeUnit::Day !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, TimeUnit::Day, self::class);
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
