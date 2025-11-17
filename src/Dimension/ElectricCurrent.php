<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Electric current dimension [I¹].
 *
 * Electric current is one of the seven SI base dimensions, representing
 * the flow of electric charge per unit time.
 *
 * The dimensional formula is:
 * [I¹]
 *
 * The SI base unit is the ampere (A), defined as the flow of exactly
 * 1/(1.602176634×10⁻¹⁹) elementary charges per second.
 *
 * Common units: A (ampere), mA, μA, kA
 */
final class ElectricCurrent implements DimensionInterface
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
                electricCurrent: 1,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'ElectricCurrent';
    }

    public function getSymbol(): string
    {
        return 'I';
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
