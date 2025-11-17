<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Volume dimension [L³].
 *
 * Volume is a derived SI dimension (length cubed).
 * The SI base unit is the cubic meter (m³).
 *
 * Common units: cubic meter, liter, milliliter, cubic foot, gallon, etc.
 */
final class Volume implements DimensionInterface
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
                length: 3,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Volume';
    }

    public function getSymbol(): string
    {
        return 'V';
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
