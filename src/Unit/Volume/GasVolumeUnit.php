<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Volume;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Volume;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Gas volume measurement units.
 *
 * These units are used for measuring natural gas volumes at standard conditions.
 * Standard conditions vary by region:
 * - Smc (Standard cubic meter): 15°C, 101.325 kPa (common in Europe/Italy)
 * - Nmc (Normal cubic meter): 0°C, 101.325 kPa (ISO standard)
 * - scf (Standard cubic foot): 60°F (15.56°C), 14.696 psi (common in US)
 * - Mcf (Thousand cubic feet): 1000 scf
 */
enum GasVolumeUnit implements UnitInterface
{
    case StandardCubicMeter;  // Smc - at 15°C, 101.325 kPa
    case NormalCubicMeter;    // Nmc - at 0°C, 101.325 kPa
    case StandardCubicFoot;   // scf - at 60°F, 14.696 psi
    case ThousandCubicFeet;   // Mcf - 1000 scf

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::StandardCubicMeter => 'Smc',
            self::NormalCubicMeter => 'Nmc',
            self::StandardCubicFoot => 'scf',
            self::ThousandCubicFeet => 'Mcf',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::StandardCubicMeter => 'standard cubic meter',
            self::NormalCubicMeter => 'normal cubic meter',
            self::StandardCubicFoot => 'standard cubic foot',
            self::ThousandCubicFeet => 'thousand cubic feet',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Volume::instance();
    }

    public function system(): UnitSystem
    {
        return match ($this) {
            self::StandardCubicMeter, self::NormalCubicMeter => UnitSystem::Metric,
            self::StandardCubicFoot, self::ThousandCubicFeet => UnitSystem::Imperial,
        };
    }
}
