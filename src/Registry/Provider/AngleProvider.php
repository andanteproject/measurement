<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Angle\Angle;
use Andante\Measurement\Quantity\Angle\SI\Arcminute;
use Andante\Measurement\Quantity\Angle\SI\Arcsecond;
use Andante\Measurement\Quantity\Angle\SI\Degree;
use Andante\Measurement\Quantity\Angle\SI\Gradian;
use Andante\Measurement\Quantity\Angle\SI\Milliradian;
use Andante\Measurement\Quantity\Angle\SI\Radian;
use Andante\Measurement\Quantity\Angle\SI\Revolution;
use Andante\Measurement\Quantity\Angle\SI\Turn;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Angle\AngleUnit;

/**
 * Provides default configuration for Angle quantities.
 *
 * Registers all angle units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to radian)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to radian):
 * - 1 rad = 1 rad (base)
 * - 1 mrad = 0.001 rad
 * - 1° = π/180 rad ≈ 0.01745329 rad
 * - 1′ = π/10800 rad ≈ 0.00029089 rad
 * - 1″ = π/648000 rad ≈ 0.00000485 rad
 * - 1 gon = π/200 rad ≈ 0.01570796 rad
 * - 1 rev = 2π rad ≈ 6.28318531 rad
 * - 1 turn = 2π rad ≈ 6.28318531 rad
 */
final class AngleProvider implements QuantityDefaultConfigProviderInterface
{
    /**
     * 2π for revolution/turn conversions.
     */
    private const TWO_PI = '6.28318530717958647692528676655900577';

    /**
     * π/180 for degree to radian conversion.
     */
    private const DEGREE_TO_RADIAN = '0.01745329251994329576923690768488613';

    /**
     * π/10800 for arcminute to radian conversion.
     */
    private const ARCMINUTE_TO_RADIAN = '0.00029088820866572159615394846141477';

    /**
     * π/648000 for arcsecond to radian conversion.
     */
    private const ARCSECOND_TO_RADIAN = '0.00000484813681109535993589914102358';

    /**
     * π/200 for gradian to radian conversion.
     */
    private const GRADIAN_TO_RADIAN = '0.01570796326794896619231321691639751';

    private static ?self $instance = null;

    private function __construct()
    {
    }

    public static function global(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * Reset the global instance (for testing).
     *
     * @internal
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    /**
     * Centralized angle unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to radian)].
     *
     * @return array<array{AngleUnit, class-string, numeric-string}>
     */
    private function getUnits(): array
    {
        return [
            [AngleUnit::Radian, Radian::class, '1'],
            [AngleUnit::Milliradian, Milliradian::class, '0.001'],
            [AngleUnit::Degree, Degree::class, self::DEGREE_TO_RADIAN],
            [AngleUnit::Arcminute, Arcminute::class, self::ARCMINUTE_TO_RADIAN],
            [AngleUnit::Arcsecond, Arcsecond::class, self::ARCSECOND_TO_RADIAN],
            [AngleUnit::Gradian, Gradian::class, self::GRADIAN_TO_RADIAN],
            [AngleUnit::Revolution, Revolution::class, self::TWO_PI],
            [AngleUnit::Turn, Turn::class, self::TWO_PI],
        ];
    }

    public function registerUnits(UnitRegistry $registry): void
    {
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
    }

    public function registerConversionFactors(ConversionFactorRegistry $registry): void
    {
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
    }

    public function registerResultMappings(ResultQuantityRegistry $registry): void
    {
        // Angle is dimensionless, so formula has all zeros
        $formula = new DimensionalFormula();

        // Unit-specific classes → generic class
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, Angle::class);
        }

        // Generic
        $registry->register(Angle::class, $formula, Angle::class);
        $registry->registerGeneric($formula, Angle::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // Angle is dimensionless, so formula has all zeros
        $formula = new DimensionalFormula();

        // Default unit for angle dimension (radian)
        $registry->register($formula, AngleUnit::Radian);
    }
}
