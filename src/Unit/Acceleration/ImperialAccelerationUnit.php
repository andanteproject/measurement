<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Acceleration;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Acceleration;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Imperial acceleration units.
 *
 * Based on foot per second squared (ft/s²).
 */
enum ImperialAccelerationUnit implements UnitInterface
{
    case FootPerSecondSquared;
    case InchPerSecondSquared;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::FootPerSecondSquared => 'ft/s²',
            self::InchPerSecondSquared => 'in/s²',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::FootPerSecondSquared => 'foot_per_second_squared',
            self::InchPerSecondSquared => 'inch_per_second_squared',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Acceleration::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Imperial;
    }
}
