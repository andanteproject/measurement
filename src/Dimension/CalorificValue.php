<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Calorific Value dimension [L⁻¹M¹T⁻²] (Energy per Volume).
 *
 * Calorific value (also called energy density or heating value) is a derived
 * SI dimension representing energy content per unit volume of fuel.
 *
 * The dimensional formula is Energy / Volume:
 * [L²M¹T⁻²] / [L³] = [L⁻¹M¹T⁻²]
 *
 * Common units: J/m³, kJ/m³, MJ/m³, BTU/ft³
 *
 * Used primarily in gas billing to convert gas volume to energy equivalent.
 */
final class CalorificValue implements DimensionInterface
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
                length: -1,
                mass: 1,
                time: -2,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'CalorificValue';
    }

    public function getSymbol(): string
    {
        return 'E/V';
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
