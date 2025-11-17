<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Time;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Time;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Time units.
 *
 * The SI base unit of time is the second (s).
 * Time units span from nanoseconds to weeks and beyond.
 */
enum TimeUnit implements UnitInterface
{
    // SI prefixed units
    case Nanosecond;
    case Microsecond;
    case Millisecond;
    case Second;

    // Derived time units
    case Minute;
    case Hour;
    case Day;
    case Week;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Nanosecond => 'ns',
            self::Microsecond => match ($notation) {
                SymbolNotation::ASCII => 'us',
                default => 'μs',
            },
            self::Millisecond => 'ms',
            self::Second => 's',
            self::Minute => 'min',
            self::Hour => 'h',
            self::Day => 'd',
            self::Week => 'wk',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Nanosecond => 'nanosecond',
            self::Microsecond => 'microsecond',
            self::Millisecond => 'millisecond',
            self::Second => 'second',
            self::Minute => 'minute',
            self::Hour => 'hour',
            self::Day => 'day',
            self::Week => 'week',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Time::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
