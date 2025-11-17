<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Length;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Length;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Metric system units of length.
 *
 * The metric system is a decimal-based system where units are
 * related by powers of 10. The base unit is the meter.
 */
enum MetricLengthUnit implements UnitInterface
{
    case Meter;
    case Kilometer;
    case Hectometer;
    case Decameter;
    case Decimeter;
    case Centimeter;
    case Millimeter;
    case Micrometer;
    case Nanometer;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Meter => 'm',
            self::Kilometer => 'km',
            self::Hectometer => 'hm',
            self::Decameter => 'dam',
            self::Decimeter => 'dm',
            self::Centimeter => 'cm',
            self::Millimeter => 'mm',
            self::Micrometer => match ($notation) {
                SymbolNotation::ASCII => 'um',
                default => 'μm',
            },
            self::Nanometer => 'nm',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Meter => 'meter',
            self::Kilometer => 'kilometer',
            self::Hectometer => 'hectometer',
            self::Decameter => 'decameter',
            self::Decimeter => 'decimeter',
            self::Centimeter => 'centimeter',
            self::Millimeter => 'millimeter',
            self::Micrometer => 'micrometer',
            self::Nanometer => 'nanometer',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Length::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Metric;
    }
}
