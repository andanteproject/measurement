<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Inductance;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Inductance;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Inductance units.
 *
 * Base unit is henry (H), the SI derived unit for inductance.
 * 1 H = 1 V⋅s/A = 1 Wb/A = 1 kg⋅m²/(A²⋅s²)
 *
 * Key conversions:
 * - 1 H = 1000 mH = 1,000,000 μH = 1,000,000,000 nH
 * - 1 mH = 1000 μH
 * - 1 μH = 1000 nH
 */
enum InductanceUnit implements UnitInterface
{
    case Henry;
    case Millihenry;
    case Microhenry;
    case Nanohenry;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($notation) {
            SymbolNotation::ASCII => match ($this) {
                self::Henry => 'H',
                self::Millihenry => 'mH',
                self::Microhenry => 'uH',
                self::Nanohenry => 'nH',
            },
            default => match ($this) {
                self::Henry => 'H',
                self::Millihenry => 'mH',
                self::Microhenry => 'μH',
                self::Nanohenry => 'nH',
            },
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Henry => 'henry',
            self::Millihenry => 'millihenry',
            self::Microhenry => 'microhenry',
            self::Nanohenry => 'nanohenry',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Inductance::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
