<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\CalorificValue;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\CalorificValue;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Metric calorific value units (energy per volume).
 *
 * Base unit is Joule per cubic meter (J/m³).
 * Common in gas industry: MJ/m³ for natural gas calorific values.
 */
enum MetricCalorificValueUnit implements UnitInterface
{
    case JoulePerCubicMeter;
    case KilojoulePerCubicMeter;
    case MegajoulePerCubicMeter;
    case GigajoulePerCubicMeter;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::JoulePerCubicMeter => 'J/m³',
            self::KilojoulePerCubicMeter => 'kJ/m³',
            self::MegajoulePerCubicMeter => 'MJ/m³',
            self::GigajoulePerCubicMeter => 'GJ/m³',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::JoulePerCubicMeter => 'joule_per_cubic_meter',
            self::KilojoulePerCubicMeter => 'kilojoule_per_cubic_meter',
            self::MegajoulePerCubicMeter => 'megajoule_per_cubic_meter',
            self::GigajoulePerCubicMeter => 'gigajoule_per_cubic_meter',
        };
    }

    public function dimension(): DimensionInterface
    {
        return CalorificValue::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Metric;
    }
}
