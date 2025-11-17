<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Time;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all time quantities.
 *
 * This interface represents any time duration measurement regardless of unit
 * (seconds, minutes, hours, days, etc.). Use this for type-hinting when you
 * need to accept any time value.
 *
 * All time quantities can be converted to PHP's native DateInterval.
 *
 * Example:
 * ```php
 * function scheduleTask(TimeInterface $delay): void
 * {
 *     $interval = $delay->toPhpDateInterval();
 *     // Use $interval with DateTime::add(), etc.
 * }
 * ```
 */
interface TimeInterface extends QuantityInterface
{
    /**
     * Convert this time quantity to a PHP DateInterval.
     *
     * The conversion will represent the time value as accurately as possible
     * within DateInterval's capabilities. Note that DateInterval stores
     * components separately (years, months, days, hours, minutes, seconds)
     * and doesn't automatically normalize between them.
     *
     * For sub-second precision, the microseconds (f) component is used.
     *
     * Example:
     * ```php
     * $duration = Hour::of(NumberFactory::create('2.5'));
     * $interval = $duration->toPhpDateInterval();
     * // $interval->h = 2, $interval->i = 30
     *
     * $ms = Millisecond::of(NumberFactory::create('500'));
     * $interval = $ms->toPhpDateInterval();
     * // $interval->f = 0.5
     * ```
     */
    public function toPhpDateInterval(): \DateInterval;
}
