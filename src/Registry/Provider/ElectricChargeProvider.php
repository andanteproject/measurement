<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\ElectricCharge\ElectricCharge;
use Andante\Measurement\Quantity\ElectricCharge\SI\AmpereHour;
use Andante\Measurement\Quantity\ElectricCharge\SI\Coulomb;
use Andante\Measurement\Quantity\ElectricCharge\SI\Microcoulomb;
use Andante\Measurement\Quantity\ElectricCharge\SI\MilliampereHour;
use Andante\Measurement\Quantity\ElectricCharge\SI\Millicoulomb;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\ElectricCharge\ElectricChargeUnit;

/**
 * Provides default configuration for Electric Charge quantities.
 *
 * Registers all electric charge units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to coulomb)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to coulomb):
 * - 1 C = 1 C (base SI derived unit)
 * - 1 mC = 0.001 C
 * - 1 μC = 0.000001 C
 * - 1 Ah = 3600 C (1 ampere × 3600 seconds)
 * - 1 mAh = 3.6 C (0.001 ampere × 3600 seconds)
 */
final class ElectricChargeProvider implements QuantityDefaultConfigProviderInterface
{
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
     * Centralized electric charge unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to coulomb)].
     *
     * @return array<array{ElectricChargeUnit, class-string, numeric-string}>
     */
    private function getUnits(): array
    {
        return [
            [ElectricChargeUnit::Coulomb, Coulomb::class, '1'],
            [ElectricChargeUnit::Millicoulomb, Millicoulomb::class, '0.001'],
            [ElectricChargeUnit::Microcoulomb, Microcoulomb::class, '0.000001'],
            [ElectricChargeUnit::AmpereHour, AmpereHour::class, '3600'],
            [ElectricChargeUnit::MilliampereHour, MilliampereHour::class, '3.6'],
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
        // Electric charge dimension: T¹I¹
        $formula = new DimensionalFormula(
            time: 1,
            electricCurrent: 1,
        );

        // Unit-specific classes → generic class
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ElectricCharge::class);
        }

        // Generic
        $registry->register(ElectricCharge::class, $formula, ElectricCharge::class);
        $registry->registerGeneric($formula, ElectricCharge::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // Electric charge dimension: T¹I¹
        $formula = new DimensionalFormula(
            time: 1,
            electricCurrent: 1,
        );

        // Default unit for electric charge dimension (coulomb)
        $registry->register($formula, ElectricChargeUnit::Coulomb);
    }
}
