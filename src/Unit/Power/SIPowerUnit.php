<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Power;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Power;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * SI power units.
 *
 * Base unit is watt (W), the SI unit for power.
 * 1 W = 1 J/s = 1 kg⋅m²/s³
 */
enum SIPowerUnit implements UnitInterface
{
    case Watt;
    case Milliwatt;
    case Kilowatt;
    case Megawatt;
    case Gigawatt;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Watt => 'W',
            self::Milliwatt => 'mW',
            self::Kilowatt => 'kW',
            self::Megawatt => 'MW',
            self::Gigawatt => 'GW',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Watt => 'watt',
            self::Milliwatt => 'milliwatt',
            self::Kilowatt => 'kilowatt',
            self::Megawatt => 'megawatt',
            self::Gigawatt => 'gigawatt',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Power::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Metric;
    }
}
