<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Velocity;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Velocity;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Imperial velocity units.
 *
 * Mile per hour (mph) is the standard unit for vehicle speeds in the US/UK.
 * Foot per second is used in technical applications.
 * Knot is used for nautical and aviation speeds.
 */
enum ImperialVelocityUnit implements UnitInterface
{
    case MilePerHour;
    case FootPerSecond;
    case Knot;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::MilePerHour => 'mph',
            self::FootPerSecond => 'ft/s',
            self::Knot => 'kn',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::MilePerHour => 'mile_per_hour',
            self::FootPerSecond => 'foot_per_second',
            self::Knot => 'knot',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Velocity::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Imperial;
    }
}
