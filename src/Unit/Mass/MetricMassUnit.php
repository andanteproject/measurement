<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Mass;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Mass;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Metric system units of mass.
 *
 * The metric system is a decimal-based system where units are
 * related by powers of 10. The SI base unit is the kilogram.
 */
enum MetricMassUnit implements UnitInterface
{
    case Kilogram;
    case Gram;
    case Milligram;
    case Microgram;
    case Tonne;
    case Hectogram;
    case Decagram;
    case Decigram;
    case Centigram;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Kilogram => 'kg',
            self::Gram => 'g',
            self::Milligram => 'mg',
            self::Microgram => match ($notation) {
                SymbolNotation::ASCII => 'ug',
                default => 'μg',
            },
            self::Tonne => 't',
            self::Hectogram => 'hg',
            self::Decagram => 'dag',
            self::Decigram => 'dg',
            self::Centigram => 'cg',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Kilogram => 'kilogram',
            self::Gram => 'gram',
            self::Milligram => 'milligram',
            self::Microgram => 'microgram',
            self::Tonne => 'tonne',
            self::Hectogram => 'hectogram',
            self::Decagram => 'decagram',
            self::Decigram => 'decigram',
            self::Centigram => 'centigram',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Mass::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Metric;
    }
}
