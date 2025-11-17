<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Digital\IEC;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DataTransferRate;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * IEC (binary) Bit-based Data Transfer Rate units.
 *
 * Used when binary prefixes are needed for bit-based transfer rates.
 * 1 Kibps = 1024 bps (binary prefix)
 *
 * Supports multiple notations:
 * - Default: "Kibps", "Mibps", etc. (most common)
 * - IEEE: "Kibit/s", "Mibit/s", etc. (standards-compliant)
 */
enum IECBitTransferRateUnit implements UnitInterface
{
    case KibibitPerSecond;
    case MebibitPerSecond;
    case GibibitPerSecond;

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
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::KibibitPerSecond => 'kibibit per second',
            self::MebibitPerSecond => 'mebibit per second',
            self::GibibitPerSecond => 'gibibit per second',
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
