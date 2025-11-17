<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Acceleration;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Acceleration;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Metric acceleration units.
 *
 * Base unit is meter per second squared (m/s²), the SI unit for acceleration.
 * Also includes Gal (cm/s²) used in geodesy and geophysics.
 */
enum MetricAccelerationUnit implements UnitInterface
{
    case MeterPerSecondSquared;
    case CentimeterPerSecondSquared;
    case MillimeterPerSecondSquared;
    case Gal;
    case StandardGravity;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::MeterPerSecondSquared => 'm/s²',
            self::CentimeterPerSecondSquared => 'cm/s²',
            self::MillimeterPerSecondSquared => 'mm/s²',
            self::Gal => 'Gal',
            self::StandardGravity => 'g',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::MeterPerSecondSquared => 'meter_per_second_squared',
            self::CentimeterPerSecondSquared => 'centimeter_per_second_squared',
            self::MillimeterPerSecondSquared => 'millimeter_per_second_squared',
            self::Gal => 'gal',
            self::StandardGravity => 'standard_gravity',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Acceleration::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Metric;
    }
}
