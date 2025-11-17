<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\ElectricCurrent;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\ElectricCurrent;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Electric current units.
 *
 * Base unit is ampere (A), the SI base unit for electric current.
 * 1 A = 1 coulomb per second = 1 C/s
 *
 * Key conversions:
 * - 1 kA = 1000 A
 * - 1 A = 1000 mA
 * - 1 mA = 1000 μA
 * - 1 μA = 1000 nA
 */
enum ElectricCurrentUnit implements UnitInterface
{
    case Ampere;
    case Kiloampere;
    case Milliampere;
    case Microampere;
    case Nanoampere;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($notation) {
            SymbolNotation::ASCII => match ($this) {
                self::Ampere => 'A',
                self::Kiloampere => 'kA',
                self::Milliampere => 'mA',
                self::Microampere => 'uA',
                self::Nanoampere => 'nA',
            },
            default => match ($this) {
                self::Ampere => 'A',
                self::Kiloampere => 'kA',
                self::Milliampere => 'mA',
                self::Microampere => 'μA',
                self::Nanoampere => 'nA',
            },
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Ampere => 'ampere',
            self::Kiloampere => 'kiloampere',
            self::Milliampere => 'milliampere',
            self::Microampere => 'microampere',
            self::Nanoampere => 'nanoampere',
        };
    }

    public function dimension(): DimensionInterface
    {
        return ElectricCurrent::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
