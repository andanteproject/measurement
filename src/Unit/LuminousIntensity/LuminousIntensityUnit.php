<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\LuminousIntensity;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\LuminousIntensity;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Luminous intensity units.
 *
 * Base unit is candela (cd), the SI base unit for luminous intensity.
 * 1 cd is the luminous intensity of a source that emits monochromatic
 * radiation of frequency 540×10¹² Hz and has a radiant intensity of
 * 1/683 watt per steradian.
 *
 * Key conversions:
 * - 1 kcd = 1000 cd
 * - 1 cd = 1000 mcd
 * - 1 mcd = 1000 μcd
 */
enum LuminousIntensityUnit implements UnitInterface
{
    case Candela;
    case Kilocandela;
    case Millicandela;
    case Microcandela;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($notation) {
            SymbolNotation::ASCII => match ($this) {
                self::Candela => 'cd',
                self::Kilocandela => 'kcd',
                self::Millicandela => 'mcd',
                self::Microcandela => 'ucd',
            },
            default => match ($this) {
                self::Candela => 'cd',
                self::Kilocandela => 'kcd',
                self::Millicandela => 'mcd',
                self::Microcandela => 'μcd',
            },
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Candela => 'candela',
            self::Kilocandela => 'kilocandela',
            self::Millicandela => 'millicandela',
            self::Microcandela => 'microcandela',
        };
    }

    public function dimension(): DimensionInterface
    {
        return LuminousIntensity::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
