<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Volume;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Volume;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Metric system units of volume.
 *
 * The SI unit of volume is the cubic meter (m³).
 * The liter (L) is a commonly used non-SI metric unit.
 * 1 L = 0.001 m³ = 1 dm³
 */
enum MetricVolumeUnit implements UnitInterface
{
    case CubicMeter;
    case CubicDecimeter;
    case CubicCentimeter;
    case CubicMillimeter;
    case Liter;
    case Deciliter;
    case Centiliter;
    case Milliliter;
    case Hectoliter;
    case Kiloliter;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::CubicMeter => match ($notation) {
                SymbolNotation::ASCII => 'm3',
                default => 'm³',
            },
            self::CubicDecimeter => match ($notation) {
                SymbolNotation::ASCII => 'dm3',
                default => 'dm³',
            },
            self::CubicCentimeter => match ($notation) {
                SymbolNotation::ASCII => 'cm3',
                default => 'cm³',
            },
            self::CubicMillimeter => match ($notation) {
                SymbolNotation::ASCII => 'mm3',
                default => 'mm³',
            },
            self::Liter => 'L',
            self::Deciliter => 'dL',
            self::Centiliter => 'cL',
            self::Milliliter => 'mL',
            self::Hectoliter => 'hL',
            self::Kiloliter => 'kL',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::CubicMeter => 'cubic meter',
            self::CubicDecimeter => 'cubic decimeter',
            self::CubicCentimeter => 'cubic centimeter',
            self::CubicMillimeter => 'cubic millimeter',
            self::Liter => 'liter',
            self::Deciliter => 'deciliter',
            self::Centiliter => 'centiliter',
            self::Milliliter => 'milliliter',
            self::Hectoliter => 'hectoliter',
            self::Kiloliter => 'kiloliter',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Volume::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Metric;
    }
}
