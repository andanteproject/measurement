<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Energy;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Energy;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Electric energy units.
 *
 * Used primarily for measuring electrical energy consumption.
 * 1 Wh = 3600 J
 * 1 kWh = 3,600,000 J
 */
enum ElectricEnergyUnit implements UnitInterface
{
    case WattHour;
    case KilowattHour;
    case MegawattHour;
    case GigawattHour;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::WattHour => match ($notation) {
                SymbolNotation::IEEE, SymbolNotation::Unicode => 'W·h',
                SymbolNotation::ASCII => 'W*h',
                default => 'Wh',
            },
            self::KilowattHour => match ($notation) {
                SymbolNotation::IEEE, SymbolNotation::Unicode => 'kW·h',
                SymbolNotation::ASCII => 'kW*h',
                default => 'kWh',
            },
            self::MegawattHour => match ($notation) {
                SymbolNotation::IEEE, SymbolNotation::Unicode => 'MW·h',
                SymbolNotation::ASCII => 'MW*h',
                default => 'MWh',
            },
            self::GigawattHour => match ($notation) {
                SymbolNotation::IEEE, SymbolNotation::Unicode => 'GW·h',
                SymbolNotation::ASCII => 'GW*h',
                default => 'GWh',
            },
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::WattHour => 'watt-hour',
            self::KilowattHour => 'kilowatt-hour',
            self::MegawattHour => 'megawatt-hour',
            self::GigawattHour => 'gigawatt-hour',
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
