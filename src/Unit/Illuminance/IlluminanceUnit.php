<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Illuminance;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Illuminance;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Illuminance units.
 *
 * Base unit is lux (lx), the SI derived unit for illuminance.
 * 1 lx = 1 lm/m² (lumen per square meter)
 *
 * Key conversions:
 * - 1 klx = 1000 lx
 * - 1 lx = 1000 mlx
 * - 1 fc = 10.7639 lx (foot-candle)
 */
enum IlluminanceUnit implements UnitInterface
{
    case Lux;
    case Kilolux;
    case Millilux;
    case FootCandle;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Lux => 'lx',
            self::Kilolux => 'klx',
            self::Millilux => 'mlx',
            self::FootCandle => 'fc',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Lux => 'lux',
            self::Kilolux => 'kilolux',
            self::Millilux => 'millilux',
            self::FootCandle => 'foot-candle',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Illuminance::instance();
    }

    public function system(): UnitSystem
    {
        return match ($this) {
            self::FootCandle => UnitSystem::Imperial,
            default => UnitSystem::SI,
        };
    }
}
