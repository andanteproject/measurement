<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Digital\IEC;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DataTransferRate;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * IEC (binary) Byte-based Data Transfer Rate units.
 *
 * Used when binary prefixes are needed for byte-based transfer rates.
 * 1 KiB/s = 1024 B/s (binary prefix)
 */
enum IECByteTransferRateUnit implements UnitInterface
{
    case KibibytePerSecond;
    case MebibytePerSecond;
    case GibibytePerSecond;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::KibibytePerSecond => 'KiB/s',
            self::MebibytePerSecond => 'MiB/s',
            self::GibibytePerSecond => 'GiB/s',
        };
    }

    public function name(): string
    {
        return match ($this) {
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
