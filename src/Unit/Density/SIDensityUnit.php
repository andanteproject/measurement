<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Density;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Density;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * SI density units.
 *
 * Base unit is kilogram per cubic meter (kg/m³), the SI unit for density.
 */
enum SIDensityUnit implements UnitInterface
{
    case KilogramPerCubicMeter;
    case GramPerCubicMeter;
    case GramPerCubicCentimeter;
    case GramPerLiter;
    case KilogramPerLiter;
    case MilligramPerCubicMeter;
    case TonnePerCubicMeter;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::KilogramPerCubicMeter => 'kg/m³',
            self::GramPerCubicMeter => 'g/m³',
            self::GramPerCubicCentimeter => 'g/cm³',
            self::GramPerLiter => 'g/L',
            self::KilogramPerLiter => 'kg/L',
            self::MilligramPerCubicMeter => 'mg/m³',
            self::TonnePerCubicMeter => 't/m³',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::KilogramPerCubicMeter => 'kilogram_per_cubic_meter',
            self::GramPerCubicMeter => 'gram_per_cubic_meter',
            self::GramPerCubicCentimeter => 'gram_per_cubic_centimeter',
            self::GramPerLiter => 'gram_per_liter',
            self::KilogramPerLiter => 'kilogram_per_liter',
            self::MilligramPerCubicMeter => 'milligram_per_cubic_meter',
            self::TonnePerCubicMeter => 'tonne_per_cubic_meter',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Density::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Metric;
    }
}
