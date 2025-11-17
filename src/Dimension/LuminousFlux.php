<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Luminous flux dimension [J¹].
 *
 * Luminous flux represents the total perceived power of light emitted
 * by a source, weighted by the luminosity function to account for
 * human eye sensitivity.
 *
 * The dimensional formula is:
 * [J¹]
 *
 * Note: While this has the same base SI dimensional formula as luminous
 * intensity, they represent different physical quantities. Luminous flux
 * is the total light output, while luminous intensity is light per solid angle.
 *
 * The SI derived unit is the lumen (lm), defined as cd⋅sr (candela steradian).
 * Since steradian is dimensionless, lumen has the same dimensional formula as candela.
 *
 * Common units: lm (lumen), klm, mlm
 */
final class LuminousFlux implements DimensionInterface
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
        return 'LuminousFlux';
    }

    public function getSymbol(): string
    {
        return 'Φv';
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
