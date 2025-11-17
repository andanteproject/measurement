<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Frequency dimension [T⁻¹].
 *
 * Frequency is a derived SI dimension representing the number of occurrences
 * of a repeating event per unit of time.
 *
 * The dimensional formula is the inverse of Time:
 * [T¹]⁻¹ = [T⁻¹]
 *
 * Common units: Hz (hertz), kHz, MHz, GHz, THz
 *
 * Frequency = 1 / Period
 */
final class Frequency implements DimensionInterface
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
                time: -1,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Frequency';
    }

    public function getSymbol(): string
    {
        return 'f';
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
