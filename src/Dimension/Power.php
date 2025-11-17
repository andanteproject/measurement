<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Power dimension [L²M¹T⁻³].
 *
 * Power is a derived SI dimension representing the rate of energy transfer.
 *
 * The dimensional formula is Energy / Time:
 * [L²M¹T⁻²] / [T¹] = [L²M¹T⁻³]
 *
 * Common units: W (watt), kW, MW, GW, hp
 *
 * Power = Energy / Time = Work / Time
 */
final class Power implements DimensionInterface
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
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Power';
    }

    public function getSymbol(): string
    {
        return 'P';
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
