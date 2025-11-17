<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\CalorificValue;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\CalorificValue;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Imperial calorific value units (energy per volume).
 *
 * Used primarily in the US/UK gas industry.
 * Common units: BTU/ft³, therm/ft³
 */
enum ImperialCalorificValueUnit implements UnitInterface
{
    case BTUPerCubicFoot;
    case ThermPerCubicFoot;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::BTUPerCubicFoot => 'BTU/ft³',
            self::ThermPerCubicFoot => 'thm/ft³',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::BTUPerCubicFoot => 'btu_per_cubic_foot',
            self::ThermPerCubicFoot => 'therm_per_cubic_foot',
        };
    }

    public function dimension(): DimensionInterface
    {
        return CalorificValue::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Imperial;
    }
}
