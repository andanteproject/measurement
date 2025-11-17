<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Digital\SI;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DigitalInformation;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * SI (decimal) Byte units.
 *
 * Uses decimal prefixes: kilo (10^3), mega (10^6), giga (10^9), tera (10^12), peta (10^15)
 */
enum SIByteUnit implements UnitInterface
{
    case Byte;
    case Kilobyte;
    case Megabyte;
    case Gigabyte;
    case Terabyte;
    case Petabyte;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Byte => 'B',
            self::Kilobyte => 'KB',
            self::Megabyte => 'MB',
            self::Gigabyte => 'GB',
            self::Terabyte => 'TB',
            self::Petabyte => 'PB',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Byte => 'byte',
            self::Kilobyte => 'kilobyte',
            self::Megabyte => 'megabyte',
            self::Gigabyte => 'gigabyte',
            self::Terabyte => 'terabyte',
            self::Petabyte => 'petabyte',
        };
    }

    public function dimension(): DimensionInterface
    {
        return DigitalInformation::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
