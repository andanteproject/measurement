<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Acceleration dimension [L¹T⁻²].
 *
 * Acceleration is a derived SI dimension representing the rate of
 * change of velocity with respect to time.
 *
 * The dimensional formula is Velocity / Time:
 * [L¹T⁻¹] / [T¹] = [L¹T⁻²]
 *
 * Common units: m/s², ft/s², g (standard gravity), Gal
 *
 * Note: In physics, acceleration is a vector quantity (has direction).
 * This library treats it as a scalar value (magnitude only).
 */
final class Acceleration implements DimensionInterface
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
                time: -2,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Acceleration';
    }

    public function getSymbol(): string
    {
        return 'a';
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
