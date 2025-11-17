<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Digital\SI;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DataTransferRate;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * SI (decimal) Data Transfer Rate units.
 *
 * Includes both bit-based and byte-based transfer rates with decimal prefixes.
 * 1 kbps = 1000 bps, 1 KB/s = 1000 B/s
 *
 * Supports multiple notations for bit-based units:
 * - Default: "Gbps", "Mbps", etc. (most common)
 * - IEEE: "Gbit/s", "Mbit/s", etc. (standards-compliant)
 */
enum SITransferRateUnit implements UnitInterface
{
    // Bit-based
    case BitPerSecond;
    case KilobitPerSecond;
    case MegabitPerSecond;
    case GigabitPerSecond;

    // Byte-based
    case BytePerSecond;
    case KilobytePerSecond;
    case MegabytePerSecond;
    case GigabytePerSecond;

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
            self::BytePerSecond => 'B/s',
            self::KilobytePerSecond => 'KB/s',
            self::MegabytePerSecond => 'MB/s',
            self::GigabytePerSecond => 'GB/s',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::BitPerSecond => 'bit per second',
            self::KilobitPerSecond => 'kilobit per second',
            self::MegabitPerSecond => 'megabit per second',
            self::GigabitPerSecond => 'gigabit per second',
            self::BytePerSecond => 'byte per second',
            self::KilobytePerSecond => 'kilobyte per second',
            self::MegabytePerSecond => 'megabyte per second',
            self::GigabytePerSecond => 'gigabyte per second',
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
