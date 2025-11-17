<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Digital\IEC;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DigitalInformation;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * IEC (binary) Bit units.
 *
 * Uses binary prefixes: kibi (2^10), mebi (2^20), gibi (2^30), tebi (2^40), pebi (2^50)
 */
enum IECBitUnit implements UnitInterface
{
    case Kibibit;
    case Mebibit;
    case Gibibit;
    case Tebibit;
    case Pebibit;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::Kibibit => 'Kib',
            self::Mebibit => 'Mib',
            self::Gibibit => 'Gib',
            self::Tebibit => 'Tib',
            self::Pebibit => 'Pib',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::Kibibit => 'kibibit',
            self::Mebibit => 'mebibit',
            self::Gibibit => 'gibibit',
            self::Tebibit => 'tebibit',
            self::Pebibit => 'pebibit',
        };
    }

    public function dimension(): DimensionInterface
    {
        return DigitalInformation::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::IEC;
    }
}
