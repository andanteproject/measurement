<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\LuminousFlux;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\LuminousFlux;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Luminous flux units.
 *
 * Base unit is lumen (lm), the SI derived unit for luminous flux.
 * 1 lm = 1 cd⋅sr (candela steradian)
 *
 * Key conversions:
 * - 1 klm = 1000 lm
 * - 1 lm = 1000 mlm
 */
enum LuminousFluxUnit implements UnitInterface
{
    case Lumen;
    case Kilolumen;
    case Millilumen;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Lumen => 'lm',
            self::Kilolumen => 'klm',
            self::Millilumen => 'mlm',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Lumen => 'lumen',
            self::Kilolumen => 'kilolumen',
            self::Millilumen => 'millilumen',
        };
    }

    public function dimension(): DimensionInterface
    {
        return LuminousFlux::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
