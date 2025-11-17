<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Area;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Area;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Metric system units of area.
 *
 * The metric system uses the square meter as the base unit.
 * Related units scale by powers of 10 (or 100 for area).
 */
enum MetricAreaUnit implements UnitInterface
{
    case SquareMeter;
    case SquareKilometer;
    case SquareCentimeter;
    case SquareMillimeter;
    case SquareDecimeter;
    case Hectare;
    case Are;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::SquareMeter => match ($notation) {
                SymbolNotation::ASCII => 'm2',
                default => 'm²',
            },
            self::SquareKilometer => match ($notation) {
                SymbolNotation::ASCII => 'km2',
                default => 'km²',
            },
            self::SquareCentimeter => match ($notation) {
                SymbolNotation::ASCII => 'cm2',
                default => 'cm²',
            },
            self::SquareMillimeter => match ($notation) {
                SymbolNotation::ASCII => 'mm2',
                default => 'mm²',
            },
            self::SquareDecimeter => match ($notation) {
                SymbolNotation::ASCII => 'dm2',
                default => 'dm²',
            },
            self::Hectare => 'ha',
            self::Are => 'a',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::SquareMeter => 'square meter',
            self::SquareKilometer => 'square kilometer',
            self::SquareCentimeter => 'square centimeter',
            self::SquareMillimeter => 'square millimeter',
            self::SquareDecimeter => 'square decimeter',
            self::Hectare => 'hectare',
            self::Are => 'are',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Area::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Metric;
    }
}
