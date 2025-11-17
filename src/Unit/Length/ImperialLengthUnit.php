<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Length;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Length;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Imperial/US customary units of length.
 *
 * The imperial system uses units derived from historical measures.
 * The base SI unit for conversion is the meter.
 */
enum ImperialLengthUnit implements UnitInterface
{
    case Inch;
    case Foot;
    case Yard;
    case Mile;
    case NauticalMile;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Inch => 'in',
            self::Foot => 'ft',
            self::Yard => 'yd',
            self::Mile => 'mi',
            self::NauticalMile => 'nmi',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Inch => 'inch',
            self::Foot => 'foot',
            self::Yard => 'yard',
            self::Mile => 'mile',
            self::NauticalMile => 'nautical mile',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Length::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Imperial;
    }
}
