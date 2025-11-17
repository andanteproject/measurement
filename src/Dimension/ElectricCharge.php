<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Electric charge dimension [T¹I¹].
 *
 * Electric charge is a derived dimension representing the quantity
 * of electric charge carried by a body.
 *
 * The dimensional formula is:
 * [T¹I¹] = A⋅s
 *
 * The SI unit is the coulomb (C), defined as:
 * 1 C = 1 A⋅s
 *
 * Common units: C (coulomb), mC, μC, Ah, mAh
 */
final class ElectricCharge implements DimensionInterface
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
                time: 1,
                electricCurrent: 1,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'ElectricCharge';
    }

    public function getSymbol(): string
    {
        return 'Q';
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
