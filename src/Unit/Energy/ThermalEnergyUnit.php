<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Energy;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Energy;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Thermal energy units.
 *
 * Used primarily for measuring heat energy.
 * 1 cal = 4.184 J (thermochemical calorie)
 * 1 BTU = 1055.06 J
 */
enum ThermalEnergyUnit implements UnitInterface
{
    case Calorie;
    case Kilocalorie;
    case BritishThermalUnit;
    case Therm;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Calorie => 'cal',
            self::Kilocalorie => 'kcal',
            self::BritishThermalUnit => 'BTU',
            self::Therm => 'thm',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Calorie => 'calorie',
            self::Kilocalorie => 'kilocalorie',
            self::BritishThermalUnit => 'British thermal unit',
            self::Therm => 'therm',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Energy::instance();
    }

    public function system(): UnitSystem
    {
        return match ($this) {
            self::Calorie, self::Kilocalorie => UnitSystem::Metric,
            self::BritishThermalUnit, self::Therm => UnitSystem::Imperial,
        };
    }
}
