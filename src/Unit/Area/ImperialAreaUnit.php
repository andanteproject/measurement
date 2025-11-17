<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Area;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Area;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Imperial/US customary units of area.
 *
 * These units are commonly used in the United States and United Kingdom.
 */
enum ImperialAreaUnit implements UnitInterface
{
    case SquareFoot;
    case SquareInch;
    case SquareYard;
    case SquareMile;
    case Acre;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::SquareFoot => match ($notation) {
                SymbolNotation::ASCII => 'ft2',
                default => 'ft²',
            },
            self::SquareInch => match ($notation) {
                SymbolNotation::ASCII => 'in2',
                default => 'in²',
            },
            self::SquareYard => match ($notation) {
                SymbolNotation::ASCII => 'yd2',
                default => 'yd²',
            },
            self::SquareMile => match ($notation) {
                SymbolNotation::ASCII => 'mi2',
                default => 'mi²',
            },
            self::Acre => 'ac',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::SquareFoot => 'square foot',
            self::SquareInch => 'square inch',
            self::SquareYard => 'square yard',
            self::SquareMile => 'square mile',
            self::Acre => 'acre',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Area::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Imperial;
    }
}
