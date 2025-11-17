<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\ElectricResistance;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\ElectricResistance;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Electric resistance units.
 *
 * Base unit is ohm (Ω), the SI derived unit for electric resistance.
 * 1 Ω = 1 V/A = 1 kg⋅m²/(A²⋅s³)
 *
 * Key conversions:
 * - 1 MΩ = 1000 kΩ = 1,000,000 Ω
 * - 1 kΩ = 1000 Ω
 * - 1 Ω = 1000 mΩ
 * - 1 mΩ = 1000 μΩ
 */
enum ElectricResistanceUnit implements UnitInterface
{
    case Ohm;
    case Megaohm;
    case Kiloohm;
    case Milliohm;
    case Microohm;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($notation) {
            SymbolNotation::ASCII => match ($this) {
                self::Ohm => 'ohm',
                self::Megaohm => 'Mohm',
                self::Kiloohm => 'kohm',
                self::Milliohm => 'mohm',
                self::Microohm => 'uohm',
            },
            default => match ($this) {
                self::Ohm => 'Ω',
                self::Megaohm => 'MΩ',
                self::Kiloohm => 'kΩ',
                self::Milliohm => 'mΩ',
                self::Microohm => 'μΩ',
            },
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Ohm => 'ohm',
            self::Megaohm => 'megaohm',
            self::Kiloohm => 'kiloohm',
            self::Milliohm => 'milliohm',
            self::Microohm => 'microohm',
        };
    }

    public function dimension(): DimensionInterface
    {
        return ElectricResistance::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
