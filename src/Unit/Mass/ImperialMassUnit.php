<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Mass;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Mass;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Imperial/US customary units of mass.
 *
 * The imperial system uses units derived from historical measures.
 * The base SI unit for conversion is the kilogram.
 */
enum ImperialMassUnit implements UnitInterface
{
    case Pound;
    case Ounce;
    case Stone;
    case ShortTon;
    case LongTon;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Pound => 'lb',
            self::Ounce => 'oz',
            self::Stone => 'st',
            self::ShortTon => 'ton',
            self::LongTon => 'long ton',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Pound => 'pound',
            self::Ounce => 'ounce',
            self::Stone => 'stone',
            self::ShortTon => 'short ton',
            self::LongTon => 'long ton',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Mass::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Imperial;
    }
}
