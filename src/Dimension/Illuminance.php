<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Illuminance dimension [L⁻²J¹].
 *
 * Illuminance represents the luminous flux incident on a surface per unit area.
 * It measures how much light falls on a surface.
 *
 * The dimensional formula is:
 * [L⁻²J¹]
 *
 * The SI derived unit is the lux (lx), defined as lm/m² (lumen per square meter).
 * The imperial unit is foot-candle (fc), defined as lm/ft².
 *
 * Common units: lx (lux), klx, mlx, fc (foot-candle)
 */
final class Illuminance implements DimensionInterface
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
                length: -2,
                luminousIntensity: 1,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Illuminance';
    }

    public function getSymbol(): string
    {
        return 'Ev';
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
