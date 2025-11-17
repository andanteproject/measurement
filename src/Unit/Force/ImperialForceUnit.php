<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Force;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Force;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Imperial force units.
 *
 * Based on pound-force (lbf).
 * 1 lbf = 4.44822 N (force to accelerate 1 lb mass at 1 g)
 */
enum ImperialForceUnit implements UnitInterface
{
    case PoundForce;
    case OunceForce;
    case Kip;
    case Poundal;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::PoundForce => 'lbf',
            self::OunceForce => 'ozf',
            self::Kip => 'kip',
            self::Poundal => 'pdl',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::PoundForce => 'pound_force',
            self::OunceForce => 'ounce_force',
            self::Kip => 'kip',
            self::Poundal => 'poundal',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Force::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Imperial;
    }
}
