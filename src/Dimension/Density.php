<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Density dimension [L⁻³M¹].
 *
 * Density is a derived SI dimension representing mass per unit volume.
 *
 * The dimensional formula is Mass / Volume:
 * [M¹] / [L³] = [L⁻³M¹]
 *
 * Common units: kg/m³, g/cm³, lb/ft³
 *
 * Density = Mass / Volume
 */
final class Density implements DimensionInterface
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
                length: -3,
                mass: 1,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Density';
    }

    public function getSymbol(): string
    {
        return 'ρ';
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
