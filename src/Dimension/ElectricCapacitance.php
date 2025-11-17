<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Electric Capacitance dimension [L⁻²M⁻¹T⁴I²].
 *
 * Electric capacitance is a derived dimension representing the ability of
 * a system to store electric charge per unit voltage.
 *
 * The dimensional formula is:
 * [L⁻²M⁻¹T⁴I²] = A²⋅s⁴/(kg⋅m²)
 *
 * The SI unit is the farad (F), defined as:
 * 1 F = 1 C/V = 1 A⋅s/V = 1 A²⋅s⁴/(kg⋅m²)
 *
 * Common units: F (farad), mF, μF, nF, pF
 */
final class ElectricCapacitance implements DimensionInterface
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
                mass: -1,
                time: 4,
                electricCurrent: 2,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Electric Capacitance';
    }

    public function getSymbol(): string
    {
        return 'C';
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
