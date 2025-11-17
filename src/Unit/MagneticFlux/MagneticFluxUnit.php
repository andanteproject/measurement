<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\MagneticFlux;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\MagneticFlux;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Magnetic flux units.
 *
 * Base unit is weber (Wb), the SI derived unit for magnetic flux.
 * 1 Wb = 1 V⋅s = 1 kg⋅m²/(A⋅s²)
 *
 * Key conversions:
 * - 1 Wb = 1000 mWb = 1,000,000 μWb
 * - 1 Wb = 10⁸ Mx (maxwell, CGS unit)
 * - 1 Mx = 10⁻⁸ Wb
 */
enum MagneticFluxUnit implements UnitInterface
{
    case Weber;
    case Milliweber;
    case Microweber;
    case Maxwell;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($notation) {
            SymbolNotation::ASCII => match ($this) {
                self::Weber => 'Wb',
                self::Milliweber => 'mWb',
                self::Microweber => 'uWb',
                self::Maxwell => 'Mx',
            },
            default => match ($this) {
                self::Weber => 'Wb',
                self::Milliweber => 'mWb',
                self::Microweber => 'μWb',
                self::Maxwell => 'Mx',
            },
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Weber => 'weber',
            self::Milliweber => 'milliweber',
            self::Microweber => 'microweber',
            self::Maxwell => 'maxwell',
        };
    }

    public function dimension(): DimensionInterface
    {
        return MagneticFlux::instance();
    }

    public function system(): UnitSystem
    {
        return match ($this) {
            self::Maxwell => UnitSystem::CGS,
            default => UnitSystem::SI,
        };
    }
}
