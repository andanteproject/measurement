<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Energy dimension [L²M¹T⁻²].
 *
 * Energy is a derived SI dimension.
 * The SI base unit is the joule (J = kg·m²/s²).
 *
 * Common units: joule, kilojoule, watt-hour, kilowatt-hour, calorie, BTU, etc.
 */
final class Energy implements DimensionInterface
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
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Energy';
    }

    public function getSymbol(): string
    {
        return 'E';
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
