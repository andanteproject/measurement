<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Digital Information dimension (dimensionless).
 *
 * Digital information (data size) is measured in bits and bytes.
 * This is not a physical SI dimension but is treated as a distinct
 * quantity type for type safety and clarity.
 *
 * Common units: bit, byte, kilobyte, megabyte, gigabyte, etc.
 */
final class DigitalInformation implements DimensionInterface
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
            // Digital information uses a special marker in the formula
            // Using 'digital: 1' to distinguish from truly dimensionless quantities
            self::$formula = new DimensionalFormula(digital: 1);
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Digital Information';
    }

    public function getSymbol(): string
    {
        return 'D';
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
