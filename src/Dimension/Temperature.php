<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Thermodynamic Temperature dimension [Θ¹].
 *
 * Temperature is one of the seven SI base dimensions.
 * The SI base unit is the kelvin (K).
 *
 * Common units: kelvin, celsius, fahrenheit
 *
 * Note: Temperature conversions are affine (not just multiplicative)
 * because they involve both scaling and offset:
 * - Kelvin to Celsius: °C = K - 273.15
 * - Kelvin to Fahrenheit: °F = K × 9/5 - 459.67
 */
final class Temperature implements DimensionInterface
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
            self::$formula = new DimensionalFormula(temperature: 1);
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Temperature';
    }

    public function getSymbol(): string
    {
        return 'Θ';
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
