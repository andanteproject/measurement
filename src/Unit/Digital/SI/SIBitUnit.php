<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Digital\SI;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DigitalInformation;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * SI (decimal) Bit units.
 *
 * Uses decimal prefixes: kilo (10^3), mega (10^6), giga (10^9), tera (10^12), peta (10^15)
 */
enum SIBitUnit implements UnitInterface
{
    case Bit;
    case Kilobit;
    case Megabit;
    case Gigabit;
    case Terabit;
    case Petabit;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Bit => 'b',
            self::Kilobit => 'Kb',
            self::Megabit => 'Mb',
            self::Gigabit => 'Gb',
            self::Terabit => 'Tb',
            self::Petabit => 'Pb',
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
