<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Pressure dimension [L⁻¹M¹T⁻²].
 *
 * Pressure is a derived SI dimension representing force per unit area.
 *
 * The dimensional formula is Force / Area:
 * [L¹M¹T⁻²] / [L²] = [L⁻¹M¹T⁻²]
 *
 * Common units: Pa (pascal), kPa, bar, atm, psi
 *
 * Pressure = Force / Area
 */
final class Pressure implements DimensionInterface
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
        return 'Pressure';
    }

    public function getSymbol(): string
    {
        return 'p';
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
