<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Inductance dimension [L²M¹T⁻²I⁻²].
 *
 * Inductance is a derived dimension representing the property of an
 * electrical conductor to oppose changes in current.
 *
 * The dimensional formula is:
 * [L²M¹T⁻²I⁻²] = kg⋅m²/(A²⋅s²)
 *
 * The SI unit is the henry (H), defined as:
 * 1 H = 1 V⋅s/A = 1 Wb/A = 1 kg⋅m²/(A²⋅s²)
 *
 * Common units: H (henry), mH, μH, nH
 */
final class Inductance implements DimensionInterface
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
                electricCurrent: -2,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Inductance';
    }

    public function getSymbol(): string
    {
        return 'L';
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
