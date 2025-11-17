<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Digital\SI;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DigitalInformation;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * SI (decimal) Digital Information units.
 *
 * Uses decimal prefixes: kilo (10^3), mega (10^6), giga (10^9), tera (10^12), peta (10^15)
 * Includes both bit-based and byte-based units.
 */
enum SIDigitalUnit implements UnitInterface
{
    // Bit-based
    case Bit;
    case Kilobit;
    case Megabit;
    case Gigabit;
    case Terabit;
    case Petabit;

    // Byte-based
    case Byte;
    case Kilobyte;
    case Megabyte;
    case Gigabyte;
    case Terabyte;
    case Petabyte;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Bit => 'b',
            self::Kilobit => 'Kb',
            self::Megabit => 'Mb',
            self::Gigabit => 'Gb',
            self::Terabit => 'Tb',
            self::Petabit => 'Pb',
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
            self::Bit => 'bit',
            self::Kilobit => 'kilobit',
            self::Megabit => 'megabit',
            self::Gigabit => 'gigabit',
            self::Terabit => 'terabit',
            self::Petabit => 'petabit',
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
