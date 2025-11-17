<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Velocity;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Velocity;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Metric velocity units.
 *
 * Base unit is meter per second (m/s), the SI unit for velocity.
 * Kilometer per hour (km/h) is commonly used for vehicle speeds.
 */
enum MetricVelocityUnit implements UnitInterface
{
    case MeterPerSecond;
    case KilometerPerHour;
    case CentimeterPerSecond;
    case MillimeterPerSecond;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::MeterPerSecond => 'm/s',
            self::KilometerPerHour => 'km/h',
            self::CentimeterPerSecond => 'cm/s',
            self::MillimeterPerSecond => 'mm/s',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::MeterPerSecond => 'meter_per_second',
            self::KilometerPerHour => 'kilometer_per_hour',
            self::CentimeterPerSecond => 'centimeter_per_second',
            self::MillimeterPerSecond => 'millimeter_per_second',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Velocity::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Metric;
    }
}
