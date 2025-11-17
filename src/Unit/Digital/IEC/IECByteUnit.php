<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Digital\IEC;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DigitalInformation;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * IEC (binary) Byte units.
 *
 * Uses binary prefixes: kibi (2^10), mebi (2^20), gibi (2^30), tebi (2^40), pebi (2^50)
 */
enum IECByteUnit implements UnitInterface
{
    case Kibibyte;
    case Mebibyte;
    case Gibibyte;
    case Tebibyte;
    case Pebibyte;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Kibibyte => 'KiB',
            self::Mebibyte => 'MiB',
            self::Gibibyte => 'GiB',
            self::Tebibyte => 'TiB',
            self::Pebibyte => 'PiB',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Kibibyte => 'kibibyte',
            self::Mebibyte => 'mebibyte',
            self::Gibibyte => 'gibibyte',
            self::Tebibyte => 'tebibyte',
            self::Pebibyte => 'pebibyte',
        };
    }

    public function dimension(): DimensionInterface
    {
        return DigitalInformation::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::IEC;
    }
}
