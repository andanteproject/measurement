<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Digital\SI;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DataTransferRate;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * SI (decimal) Bit-based Data Transfer Rate units.
 *
 * Used for network speeds and data transfer measurements.
 * 1 kbps = 1000 bps (decimal prefix)
 *
 * Supports multiple notations:
 * - Default: "Gbps", "Mbps", etc. (most common)
 * - IEEE: "Gbit/s", "Mbit/s", etc. (standards-compliant)
 */
enum BitTransferRateUnit implements UnitInterface
{
    case BitPerSecond;
    case KilobitPerSecond;
    case MegabitPerSecond;
    case GigabitPerSecond;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::BitPerSecond => match ($notation) {
                SymbolNotation::IEEE, SymbolNotation::Unicode => 'bit/s',
                default => 'bps',
            },
            self::KilobitPerSecond => match ($notation) {
                SymbolNotation::IEEE, SymbolNotation::Unicode => 'kbit/s',
                default => 'kbps',
            },
            self::MegabitPerSecond => match ($notation) {
                SymbolNotation::IEEE, SymbolNotation::Unicode => 'Mbit/s',
                default => 'Mbps',
            },
            self::GigabitPerSecond => match ($notation) {
                SymbolNotation::IEEE, SymbolNotation::Unicode => 'Gbit/s',
                default => 'Gbps',
            },
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::BitPerSecond => 'bit per second',
            self::KilobitPerSecond => 'kilobit per second',
            self::MegabitPerSecond => 'megabit per second',
            self::GigabitPerSecond => 'gigabit per second',
        };
    }

    public function dimension(): DimensionInterface
    {
        return DataTransferRate::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
