<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Digital\IEC;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DataTransferRate;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * IEC (binary) Data Transfer Rate units.
 *
 * Includes both bit-based and byte-based transfer rates with binary prefixes.
 * 1 Kibps = 1024 bps, 1 KiB/s = 1024 B/s
 *
 * Supports multiple notations for bit-based units:
 * - Default: "Kibps", "Mibps", etc. (most common)
 * - IEEE: "Kibit/s", "Mibit/s", etc. (standards-compliant)
 */
enum IECTransferRateUnit implements UnitInterface
{
    // Bit-based
    case KibibitPerSecond;
    case MebibitPerSecond;
    case GibibitPerSecond;

    // Byte-based
    case KibibytePerSecond;
    case MebibytePerSecond;
    case GibibytePerSecond;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::KibibitPerSecond => match ($notation) {
                SymbolNotation::IEEE, SymbolNotation::Unicode => 'Kibit/s',
                default => 'Kibps',
            },
            self::MebibitPerSecond => match ($notation) {
                SymbolNotation::IEEE, SymbolNotation::Unicode => 'Mibit/s',
                default => 'Mibps',
            },
            self::GibibitPerSecond => match ($notation) {
                SymbolNotation::IEEE, SymbolNotation::Unicode => 'Gibit/s',
                default => 'Gibps',
            },
            self::KibibytePerSecond => 'KiB/s',
            self::MebibytePerSecond => 'MiB/s',
            self::GibibytePerSecond => 'GiB/s',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::KibibitPerSecond => 'kibibit per second',
            self::MebibitPerSecond => 'mebibit per second',
            self::GibibitPerSecond => 'gibibit per second',
            self::KibibytePerSecond => 'kibibyte per second',
            self::MebibytePerSecond => 'mebibyte per second',
            self::GibibytePerSecond => 'gibibyte per second',
        };
    }

    public function dimension(): DimensionInterface
    {
        return DataTransferRate::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::IEC;
    }
}
