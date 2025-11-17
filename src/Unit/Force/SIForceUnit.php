<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Force;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Force;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * SI force units.
 *
 * Base unit is newton (N), the SI unit for force.
 * 1 N = 1 kg⋅m/s²
 */
enum SIForceUnit implements UnitInterface
{
    case Newton;
    case Kilonewton;
    case Meganewton;
    case Millinewton;
    case Micronewton;
    case Dyne;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Newton => 'N',
            self::Kilonewton => 'kN',
            self::Meganewton => 'MN',
            self::Millinewton => 'mN',
            self::Micronewton => 'μN',
            self::Dyne => 'dyn',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Newton => 'newton',
            self::Kilonewton => 'kilonewton',
            self::Meganewton => 'meganewton',
            self::Millinewton => 'millinewton',
            self::Micronewton => 'micronewton',
            self::Dyne => 'dyne',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Force::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Metric;
    }
}
