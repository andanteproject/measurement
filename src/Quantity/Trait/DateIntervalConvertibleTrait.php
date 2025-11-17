<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Trait;

use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Unit\Time\TimeUnit;

/**
 * Trait that provides conversion to/from PHP DateInterval.
 *
 * This trait expects the class to implement QuantityInterface and use ConvertibleTrait.
 *
 * @phpstan-require-implements QuantityInterface
 */
trait DateIntervalConvertibleTrait
{
    /**
     * Convert this time quantity to a PHP DateInterval.
     *
     * The conversion first converts the value to seconds, then breaks it down
     * into days, hours, minutes, seconds, and microseconds.
     */
    public function toPhpDateInterval(): \DateInterval
    {
        // Convert to seconds first
        $seconds = $this->to(TimeUnit::Second);
        $totalSeconds = (float) $seconds->getValue()->value();

        // Handle negative values
        $isNegative = 0 > $totalSeconds;
        $totalSeconds = \abs($totalSeconds);

        // Calculate components
        $days = (int) \floor($totalSeconds / 86400);
        $remaining = $totalSeconds - ($days * 86400);

        $hours = (int) \floor($remaining / 3600);
        $remaining -= $hours * 3600;

        $minutes = (int) \floor($remaining / 60);
        $remaining -= $minutes * 60;

        $wholeSeconds = (int) \floor($remaining);
        $microseconds = $remaining - $wholeSeconds;

        // Create DateInterval
        $interval = new \DateInterval('PT0S');
        $interval->d = $days;
        $interval->h = $hours;
        $interval->i = $minutes;
        $interval->s = $wholeSeconds;
        $interval->f = $microseconds;
        $interval->invert = $isNegative ? 1 : 0;

        return $interval;
    }

    /**
     * Calculate total seconds from a DateInterval.
     *
     * Note: This method ignores years and months as they don't have fixed durations.
     * Only days, hours, minutes, seconds, and microseconds are considered.
     *
     * @return numeric-string The total seconds as a numeric string (for arbitrary precision)
     */
    protected static function dateIntervalToSeconds(\DateInterval $interval): string
    {
        // Calculate total seconds from components
        // Note: We ignore $interval->y and $interval->m as they don't have fixed durations
        $totalSeconds = ($interval->d * 86400)
            + ($interval->h * 3600)
            + ($interval->i * 60)
            + $interval->s
            + $interval->f;

        // Handle negative intervals
        if (1 === $interval->invert) {
            $totalSeconds = -$totalSeconds;
        }

        // Return as string to preserve precision
        return (string) $totalSeconds;
    }
}
