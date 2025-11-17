<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Luminous intensity dimension [J¹].
 *
 * Luminous intensity is one of the seven SI base dimensions, representing
 * the luminous power emitted by a light source in a particular direction
 * per unit solid angle.
 *
 * The dimensional formula is:
 * [J¹]
 *
 * The SI base unit is the candela (cd), defined as the luminous intensity
 * of a source that emits monochromatic radiation of frequency 540×10¹² Hz
 * and has a radiant intensity of 1/683 watt per steradian.
 *
 * Common units: cd (candela), mcd, kcd
 */
final class LuminousIntensity implements DimensionInterface
{
    private static ?self $instance = null;
    private static ?DimensionalFormula $formula = null;

    private function __construct()
    {
    }

    public static function instance(): self
    {
        return self::$instance ??= new self();
    }

    public function getFormula(): DimensionalFormula
    {
        if (null === self::$formula) {
            self::$formula = new DimensionalFormula(
                luminousIntensity: 1,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'LuminousIntensity';
    }

    public function getSymbol(): string
    {
        return 'J';
    }

    public function isCompatibleWith(DimensionInterface $other): bool
    {
        return $this->getFormula()->equals($other->getFormula());
    }

    public function isDimensionless(): bool
    {
        return false;
    }
}
