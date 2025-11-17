<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\ElectricCapacitance;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\ElectricCapacitance;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Electric capacitance units.
 *
 * Base unit is farad (F), the SI derived unit for electric capacitance.
 * 1 F = 1 C/V = 1 A⋅s/V = 1 A²⋅s⁴/(kg⋅m²)
 *
 * Key conversions:
 * - 1 F = 1000 mF
 * - 1 mF = 1000 μF
 * - 1 μF = 1000 nF
 * - 1 nF = 1000 pF
 */
enum ElectricCapacitanceUnit implements UnitInterface
{
    case Farad;
    case Millifarad;
    case Microfarad;
    case Nanofarad;
    case Picofarad;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($notation) {
            SymbolNotation::ASCII => match ($this) {
                self::Farad => 'F',
                self::Millifarad => 'mF',
                self::Microfarad => 'uF',
                self::Nanofarad => 'nF',
                self::Picofarad => 'pF',
            },
            default => match ($this) {
                self::Farad => 'F',
                self::Millifarad => 'mF',
                self::Microfarad => 'μF',
                self::Nanofarad => 'nF',
                self::Picofarad => 'pF',
            },
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Farad => 'farad',
            self::Millifarad => 'millifarad',
            self::Microfarad => 'microfarad',
            self::Nanofarad => 'nanofarad',
            self::Picofarad => 'picofarad',
        };
    }

    public function dimension(): DimensionInterface
    {
        return ElectricCapacitance::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
