<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Frequency;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Frequency;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Frequency units.
 *
 * Base unit is hertz (Hz), the SI unit for frequency.
 * 1 Hz = 1 cycle per second = 1 s⁻¹
 */
enum FrequencyUnit implements UnitInterface
{
    case Hertz;
    case Millihertz;
    case Kilohertz;
    case Megahertz;
    case Gigahertz;
    case Terahertz;
    case RevolutionPerMinute;
    case RevolutionPerSecond;
    case BeatsPerMinute;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Hertz => 'Hz',
            self::Millihertz => 'mHz',
            self::Kilohertz => 'kHz',
            self::Megahertz => 'MHz',
            self::Gigahertz => 'GHz',
            self::Terahertz => 'THz',
            self::RevolutionPerMinute => 'rpm',
            self::RevolutionPerSecond => 'rps',
            self::BeatsPerMinute => 'bpm',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Hertz => 'hertz',
            self::Millihertz => 'millihertz',
            self::Kilohertz => 'kilohertz',
            self::Megahertz => 'megahertz',
            self::Gigahertz => 'gigahertz',
            self::Terahertz => 'terahertz',
            self::RevolutionPerMinute => 'revolution_per_minute',
            self::RevolutionPerSecond => 'revolution_per_second',
            self::BeatsPerMinute => 'beats_per_minute',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Frequency::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
