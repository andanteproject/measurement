<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Pressure;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Pressure;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Imperial pressure units.
 *
 * Based on pounds per square inch (psi).
 * 1 psi = 6894.76 Pa
 */
enum ImperialPressureUnit implements UnitInterface
{
    case PoundPerSquareInch;
    case PoundPerSquareFoot;
    case InchOfMercury;
    case InchOfWater;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::PoundPerSquareInch => 'psi',
            self::PoundPerSquareFoot => 'psf',
            self::InchOfMercury => 'inHg',
            self::InchOfWater => 'inH₂O',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::PoundPerSquareInch => 'pound_per_square_inch',
            self::PoundPerSquareFoot => 'pound_per_square_foot',
            self::InchOfMercury => 'inch_of_mercury',
            self::InchOfWater => 'inch_of_water',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Pressure::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Imperial;
    }
}
