<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Electric potential dimension [L²M¹T⁻³I⁻¹].
 *
 * Electric potential (voltage) is a derived dimension representing
 * the work done per unit charge to move a charge from one point to another.
 *
 * The dimensional formula is:
 * [L²M¹T⁻³I⁻¹] = kg⋅m²/(A⋅s³)
 *
 * The SI unit is the volt (V), defined as:
 * 1 V = 1 W/A = 1 J/C = 1 kg⋅m²/(A⋅s³)
 *
 * Common units: V (volt), mV, kV, MV
 */
final class ElectricPotential implements DimensionInterface
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
                length: 2,
                mass: 1,
                time: -3,
                electricCurrent: -1,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'ElectricPotential';
    }

    public function getSymbol(): string
    {
        return 'U';
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
