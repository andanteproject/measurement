<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit\Volume;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\Volume;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Imperial/US customary units of volume.
 *
 * Note: US and Imperial gallons are different sizes.
 * 1 US gallon = 3.785411784 L
 * 1 Imperial gallon = 4.54609 L
 */
enum ImperialVolumeUnit implements UnitInterface
{
    case CubicFoot;
    case CubicInch;
    case CubicYard;
    case USGallon;
    case USQuart;
    case USPint;
    case USCup;
    case USFluidOunce;
    case USTablespoon;
    case USTeaspoon;
    case ImperialGallon;
    case ImperialQuart;
    case ImperialPint;
    case ImperialFluidOunce;

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($this) {
            self::CubicFoot => match ($notation) {
                SymbolNotation::ASCII => 'ft3',
                default => 'ft³',
            },
            self::CubicInch => match ($notation) {
                SymbolNotation::ASCII => 'in3',
                default => 'in³',
            },
            self::CubicYard => match ($notation) {
                SymbolNotation::ASCII => 'yd3',
                default => 'yd³',
            },
            self::USGallon => 'gal',
            self::USQuart => 'qt',
            self::USPint => 'pt',
            self::USCup => 'cup',
            self::USFluidOunce => 'fl oz',
            self::USTablespoon => 'tbsp',
            self::USTeaspoon => 'tsp',
            self::ImperialGallon => 'imp gal',
            self::ImperialQuart => 'imp qt',
            self::ImperialPint => 'imp pt',
            self::ImperialFluidOunce => 'imp fl oz',
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::CubicFoot => 'cubic foot',
            self::CubicInch => 'cubic inch',
            self::CubicYard => 'cubic yard',
            self::USGallon => 'US gallon',
            self::USQuart => 'US quart',
            self::USPint => 'US pint',
            self::USCup => 'US cup',
            self::USFluidOunce => 'US fluid ounce',
            self::USTablespoon => 'US tablespoon',
            self::USTeaspoon => 'US teaspoon',
            self::ImperialGallon => 'imperial gallon',
            self::ImperialQuart => 'imperial quart',
            self::ImperialPint => 'imperial pint',
            self::ImperialFluidOunce => 'imperial fluid ounce',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Volume::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::Imperial;
    }
}
