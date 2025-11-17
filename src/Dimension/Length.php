<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Length dimension [L¹].
 *
 * Length is one of the seven SI base dimensions.
 * The SI base unit is the meter (m).
 *
 * Common units: meter, kilometer, centimeter, foot, inch, mile, etc.
 */
final class Length implements DimensionInterface
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
                length: 1,
                mass: 0,
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
        return 'Length';
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
