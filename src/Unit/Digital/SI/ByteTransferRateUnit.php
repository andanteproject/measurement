<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Digital\SI;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DataTransferRate;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * SI (decimal) Byte-based Data Transfer Rate units.
 *
 * Used for file transfer speeds.
 * 1 KB/s = 1000 B/s (decimal prefix)
 */
enum ByteTransferRateUnit implements UnitInterface
{
    case BytePerSecond;
    case KilobytePerSecond;
    case MegabytePerSecond;
    case GigabytePerSecond;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::BytePerSecond => 'B/s',
            self::KilobytePerSecond => 'KB/s',
            self::MegabytePerSecond => 'MB/s',
            self::GigabytePerSecond => 'GB/s',
        };
    }

    public function name(): string
    {
        return match ($this) {
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
