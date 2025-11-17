<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Force dimension [L¹M¹T⁻²].
 *
 * Force is a derived SI dimension representing the interaction that
 * causes an object to change its velocity.
 *
 * The dimensional formula is Mass × Acceleration:
 * [M¹] × [L¹T⁻²] = [L¹M¹T⁻²]
 *
 * Common units: N (newton), kN, lbf (pound-force), dyn (dyne)
 *
 * Newton's second law: F = ma
 */
final class Force implements DimensionInterface
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
                mass: 1,
                time: -2,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Force';
    }

    public function getSymbol(): string
    {
        return 'F';
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
