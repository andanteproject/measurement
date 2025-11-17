<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Density;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Density;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Imperial density units.
 *
 * Common imperial units for density measurement.
 */
enum ImperialDensityUnit implements UnitInterface
{
    case PoundPerCubicFoot;
    case PoundPerCubicInch;
    case PoundPerGallon;
    case OuncePerCubicInch;
    case SlugPerCubicFoot;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::PoundPerCubicFoot => 'lb/ft³',
            self::PoundPerCubicInch => 'lb/in³',
            self::PoundPerGallon => 'lb/gal',
            self::OuncePerCubicInch => 'oz/in³',
            self::SlugPerCubicFoot => 'slug/ft³',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::PoundPerCubicFoot => 'pound_per_cubic_foot',
            self::PoundPerCubicInch => 'pound_per_cubic_inch',
            self::PoundPerGallon => 'pound_per_gallon',
            self::OuncePerCubicInch => 'ounce_per_cubic_inch',
            self::SlugPerCubicFoot => 'slug_per_cubic_foot',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Density::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Imperial;
    }
}
