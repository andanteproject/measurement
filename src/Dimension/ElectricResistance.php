<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Electric resistance dimension [L²M¹T⁻³I⁻²].
 *
 * Electric resistance is a derived dimension representing the opposition
 * to the flow of electric current.
 *
 * The dimensional formula is:
 * [L²M¹T⁻³I⁻²] = kg⋅m²/(A²⋅s³)
 *
 * The SI unit is the ohm (Ω), defined as:
 * 1 Ω = 1 V/A = 1 kg⋅m²/(A²⋅s³)
 *
 * Common units: Ω (ohm), mΩ, kΩ, MΩ
 */
final class ElectricResistance implements DimensionInterface
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
                electricCurrent: -2,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'ElectricResistance';
    }

    public function getSymbol(): string
    {
        return 'R';
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
