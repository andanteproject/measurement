<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Angle dimension [dimensionless].
 *
 * Angle is a dimensionless quantity in SI terms, but is treated as a
 * distinct physical dimension for practical purposes. The SI unit of
 * angle is the radian (rad), which is defined as the ratio of arc length
 * to radius (both have dimension of length, so the ratio is dimensionless).
 *
 * Despite being dimensionless, angles are treated specially because:
 * - They have distinct units (radian, degree, gradian, etc.)
 * - They appear in trigonometric functions
 * - They describe geometric relationships
 *
 * Common units: rad (radian), ° (degree), gon (gradian), rev (revolution)
 */
final class Angle implements DimensionInterface
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
            // Dimensionless - all exponents are zero
            self::$formula = new DimensionalFormula();
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Angle';
    }

    public function getSymbol(): string
    {
        return 'θ';
    }

    public function isCompatibleWith(DimensionInterface $other): bool
    {
        // For angle, we need exact dimension match (not just formula)
        // since other dimensionless quantities exist
        return $other instanceof self;
    }

    public function isDimensionless(): bool
    {
        return true;
    }
}
