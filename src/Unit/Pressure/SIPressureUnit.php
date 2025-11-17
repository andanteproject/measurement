<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Pressure;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Pressure;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * SI pressure units.
 *
 * Base unit is pascal (Pa), the SI unit for pressure.
 * 1 Pa = 1 N/m² = 1 kg/(m·s²)
 */
enum SIPressureUnit implements UnitInterface
{
    case Pascal;
    case Hectopascal;
    case Kilopascal;
    case Megapascal;
    case Gigapascal;
    case Bar;
    case Millibar;
    case Atmosphere;
    case Torr;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Pascal => 'Pa',
            self::Hectopascal => 'hPa',
            self::Kilopascal => 'kPa',
            self::Megapascal => 'MPa',
            self::Gigapascal => 'GPa',
            self::Bar => 'bar',
            self::Millibar => 'mbar',
            self::Atmosphere => 'atm',
            self::Torr => 'Torr',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Pascal => 'pascal',
            self::Hectopascal => 'hectopascal',
            self::Kilopascal => 'kilopascal',
            self::Megapascal => 'megapascal',
            self::Gigapascal => 'gigapascal',
            self::Bar => 'bar',
            self::Millibar => 'millibar',
            self::Atmosphere => 'atmosphere',
            self::Torr => 'torr',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Pressure::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Metric;
    }
}
