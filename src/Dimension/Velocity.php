<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Velocity dimension [L¹T⁻¹].
 *
 * Velocity (or speed) is a derived SI dimension representing the rate of
 * change of position with respect to time.
 *
 * The dimensional formula is Length / Time:
 * [L¹] / [T¹] = [L¹T⁻¹]
 *
 * Common units: m/s, km/h, mph, ft/s, knot
 *
 * Note: In physics, velocity is a vector quantity (has direction), while speed
 * is scalar. This library treats them equivalently as scalar values.
 */
final class Velocity implements DimensionInterface
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
                time: -1,
            );
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Velocity';
    }

    public function getSymbol(): string
    {
        return 'v';
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
