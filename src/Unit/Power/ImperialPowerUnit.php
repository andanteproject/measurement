<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Power;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Power;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Imperial power units.
 *
 * Includes mechanical horsepower and related units.
 * 1 hp (mechanical) = 745.69987 W
 */
enum ImperialPowerUnit implements UnitInterface
{
    case MechanicalHorsepower;
    case ElectricalHorsepower;
    case MetricHorsepower;
    case FootPoundPerSecond;
    case BTUPerHour;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::MechanicalHorsepower => 'hp',
            self::ElectricalHorsepower => 'hp(E)',
            self::MetricHorsepower => 'PS',
            self::FootPoundPerSecond => 'ft⋅lbf/s',
            self::BTUPerHour => 'BTU/h',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::MechanicalHorsepower => 'mechanical_horsepower',
            self::ElectricalHorsepower => 'electrical_horsepower',
            self::MetricHorsepower => 'metric_horsepower',
            self::FootPoundPerSecond => 'foot_pound_per_second',
            self::BTUPerHour => 'btu_per_hour',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Power::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Imperial;
    }
}
