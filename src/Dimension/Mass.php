<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Mass dimension [M¹].
 *
 * Mass is one of the seven SI base dimensions.
 * The SI base unit is the kilogram (kg).
 *
 * Common units: kilogram, gram, milligram, pound, ounce, tonne, etc.
 */
final class Mass implements DimensionInterface
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
                length: 0,
                mass: 1,
                time: 0,
                electricCurrent: 0,
                temperature: 0,
                amountOfSubstance: 0,
                luminousIntensity: 0,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Mass';
    }

    public function getSymbol(): string
    {
        return 'M';
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
