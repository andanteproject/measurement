<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Energy;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Energy;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * SI energy units.
 *
 * The SI unit of energy is the joule (J).
 * 1 J = 1 kg·m²/s² = 1 N·m = 1 W·s
 */
enum SIEnergyUnit implements UnitInterface
{
    case Joule;
    case Kilojoule;
    case Megajoule;
    case Gigajoule;
    case Millijoule;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Joule => 'J',
            self::Kilojoule => 'kJ',
            self::Megajoule => 'MJ',
            self::Gigajoule => 'GJ',
            self::Millijoule => 'mJ',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Joule => 'joule',
            self::Kilojoule => 'kilojoule',
            self::Megajoule => 'megajoule',
            self::Gigajoule => 'gigajoule',
            self::Millijoule => 'millijoule',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Energy::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
