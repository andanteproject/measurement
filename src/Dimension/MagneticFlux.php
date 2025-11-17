<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Magnetic flux dimension [L²M¹T⁻²I⁻¹].
 *
 * Magnetic flux is a derived dimension representing the total magnetic
 * field passing through a surface.
 *
 * The dimensional formula is:
 * [L²M¹T⁻²I⁻¹] = kg⋅m²/(A⋅s²)
 *
 * The SI unit is the weber (Wb), defined as:
 * 1 Wb = 1 V⋅s = 1 kg⋅m²/(A⋅s²)
 *
 * Common units: Wb (weber), mWb, μWb, Mx (maxwell)
 */
final class MagneticFlux implements DimensionInterface
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
                time: -2,
                electricCurrent: -1,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'MagneticFlux';
    }

    public function getSymbol(): string
    {
        return 'Φ';
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
