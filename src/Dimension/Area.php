<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Area dimension [L²].
 *
 * Area is a derived dimension representing two-dimensional space.
 * It is derived from the base dimension Length squared.
 * The SI base unit is the square meter (m²).
 *
 * Common units: square meter, square kilometer, hectare, square foot, acre, etc.
 */
final class Area implements DimensionInterface
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
        return 'Area';
    }

    public function getSymbol(): string
    {
        return 'L²';
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
