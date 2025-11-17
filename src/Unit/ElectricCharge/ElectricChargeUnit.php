<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\ElectricCharge;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\ElectricCharge;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Electric charge units.
 *
 * Base unit is coulomb (C), the SI derived unit for electric charge.
 * 1 C = 1 A⋅s
 *
 * Key conversions:
 * - 1 C = 1000 mC = 1,000,000 μC
 * - 1 Ah = 3600 C (ampere-hour, common for batteries)
 * - 1 mAh = 3.6 C (milliampere-hour, common for small batteries)
 */
enum ElectricChargeUnit implements UnitInterface
{
    case Coulomb;
    case Millicoulomb;
    case Microcoulomb;
    case AmpereHour;
    case MilliampereHour;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($notation) {
            SymbolNotation::ASCII => match ($this) {
                self::Coulomb => 'C',
                self::Millicoulomb => 'mC',
                self::Microcoulomb => 'uC',
                self::AmpereHour => 'Ah',
                self::MilliampereHour => 'mAh',
            },
            default => match ($this) {
                self::Coulomb => 'C',
                self::Millicoulomb => 'mC',
                self::Microcoulomb => 'μC',
                self::AmpereHour => 'Ah',
                self::MilliampereHour => 'mAh',
            },
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Coulomb => 'coulomb',
            self::Millicoulomb => 'millicoulomb',
            self::Microcoulomb => 'microcoulomb',
            self::AmpereHour => 'ampere-hour',
            self::MilliampereHour => 'milliampere-hour',
        };
    }

    public function dimension(): DimensionInterface
    {
        return ElectricCharge::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
