<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\ElectricPotential;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\ElectricPotential;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Electric potential (voltage) units.
 *
 * Base unit is volt (V), the SI derived unit for electric potential.
 * 1 V = 1 W/A = 1 J/C = 1 kg⋅m²/(A⋅s³)
 *
 * Key conversions:
 * - 1 MV = 1000 kV = 1,000,000 V
 * - 1 kV = 1000 V
 * - 1 V = 1000 mV
 * - 1 mV = 1000 μV
 */
enum ElectricPotentialUnit implements UnitInterface
{
    case Volt;
    case Megavolt;
    case Kilovolt;
    case Millivolt;
    case Microvolt;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($notation) {
            SymbolNotation::ASCII => match ($this) {
                self::Volt => 'V',
                self::Megavolt => 'MV',
                self::Kilovolt => 'kV',
                self::Millivolt => 'mV',
                self::Microvolt => 'uV',
            },
            default => match ($this) {
                self::Volt => 'V',
                self::Megavolt => 'MV',
                self::Kilovolt => 'kV',
                self::Millivolt => 'mV',
                self::Microvolt => 'μV',
            },
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Volt => 'volt',
            self::Megavolt => 'megavolt',
            self::Kilovolt => 'kilovolt',
            self::Millivolt => 'millivolt',
            self::Microvolt => 'microvolt',
        };
    }

    public function dimension(): DimensionInterface
    {
        return ElectricPotential::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
