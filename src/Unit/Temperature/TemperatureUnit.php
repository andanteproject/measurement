<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Temperature;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Temperature;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Temperature units.
 *
 * The SI base unit of temperature is the kelvin (K).
 *
 * Temperature conversions require affine transformations (scale + offset),
 * not just multiplication:
 * - Kelvin (K): base unit, factor=1, offset=0
 * - Celsius (°C): K = °C + 273.15, factor=1, offset=273.15
 * - Fahrenheit (°F): K = (°F + 459.67) × 5/9, factor=5/9, offset=255.372222...
 */
enum TemperatureUnit implements UnitInterface
{
    case Kelvin;
    case Celsius;
    case Fahrenheit;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Kelvin => 'K',
            self::Celsius => match ($notation) {
                SymbolNotation::ASCII => 'C',
                default => '°C',
            },
            self::Fahrenheit => match ($notation) {
                SymbolNotation::ASCII => 'F',
                default => '°F',
            },
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Kelvin => 'kelvin',
            self::Celsius => 'celsius',
            self::Fahrenheit => 'fahrenheit',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Temperature::instance();
    }

    public function system(): UnitSystem
    {
        return match ($this) {
            self::Kelvin, self::Celsius => UnitSystem::SI,
            self::Fahrenheit => UnitSystem::Imperial,
        };
    }
}
