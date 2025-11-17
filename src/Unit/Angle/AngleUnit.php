<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Angle;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Angle;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Angle units.
 *
 * Base unit is radian (rad), the SI unit for angle.
 * 1 rad = the angle subtended at the center of a circle by an arc
 * equal in length to the radius.
 *
 * Key conversions:
 * - 1 revolution = 2π rad = 360° = 400 gon
 * - 1 rad ≈ 57.2958°
 * - 1° = π/180 rad ≈ 0.01745 rad
 * - 1 gon = π/200 rad = 0.9°
 * - 1° = 60 arcmin = 3600 arcsec
 */
enum AngleUnit implements UnitInterface
{
    case Radian;
    case Milliradian;
    case Degree;
    case Arcminute;
    case Arcsecond;
    case Gradian;
    case Revolution;
    case Turn;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($notation) {
            SymbolNotation::ASCII => match ($this) {
                self::Radian => 'rad',
                self::Milliradian => 'mrad',
                self::Degree => 'deg',
                self::Arcminute => 'arcmin',
                self::Arcsecond => 'arcsec',
                self::Gradian => 'gon',
                self::Revolution => 'rev',
                self::Turn => 'tr',
            },
            default => match ($this) {
                self::Radian => 'rad',
                self::Milliradian => 'mrad',
                self::Degree => '°',
                self::Arcminute => '′',
                self::Arcsecond => '″',
                self::Gradian => 'gon',
                self::Revolution => 'rev',
                self::Turn => 'tr',
            },
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Radian => 'radian',
            self::Milliradian => 'milliradian',
            self::Degree => 'degree',
            self::Arcminute => 'arcminute',
            self::Arcsecond => 'arcsecond',
            self::Gradian => 'gradian',
            self::Revolution => 'revolution',
            self::Turn => 'turn',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Angle::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
