<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Energy\Electric\GigawattHour;
use Andante\Measurement\Quantity\Energy\Electric\KilowattHour;
use Andante\Measurement\Quantity\Energy\Electric\MegawattHour;
use Andante\Measurement\Quantity\Energy\Electric\WattHour;
use Andante\Measurement\Quantity\Energy\ElectricEnergy;
use Andante\Measurement\Quantity\Energy\Energy;
use Andante\Measurement\Quantity\Energy\SI\Joule;
use Andante\Measurement\Quantity\Energy\SI\Kilojoule;
use Andante\Measurement\Quantity\Energy\SI\Megajoule;
use Andante\Measurement\Quantity\Energy\SIEnergy;
use Andante\Measurement\Quantity\Energy\Thermal\BritishThermalUnit;
use Andante\Measurement\Quantity\Energy\Thermal\Calorie;
use Andante\Measurement\Quantity\Energy\Thermal\Kilocalorie;
use Andante\Measurement\Quantity\Energy\ThermalEnergy;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Energy\ElectricEnergyUnit;
use Andante\Measurement\Unit\Energy\SIEnergyUnit;
use Andante\Measurement\Unit\Energy\ThermalEnergyUnit;

/**
 * Provides default configuration for Energy quantities.
 *
 * Registers all SI, electric, and thermal energy units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to Joule)
 * - Result quantity mappings for operations
 * - Default formula units
 */
final class EnergyProvider implements QuantityDefaultConfigProviderInterface
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
     * Centralized SI energy unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to Joule)].
     *
     * @return array<array{SIEnergyUnit, class-string, numeric-string}>
     */
    private function getSIUnits(): array
    {
        return [
            [SIEnergyUnit::Joule, Joule::class, '1'],
            [SIEnergyUnit::Kilojoule, Kilojoule::class, '1000'],
            [SIEnergyUnit::Megajoule, Megajoule::class, '1000000'],
            [SIEnergyUnit::Gigajoule, Megajoule::class, '1000000000'],
            [SIEnergyUnit::Millijoule, Joule::class, '0.001'],
        ];
    }

    /**
     * Centralized electric energy unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to Joule)].
     *
     * 1 Wh = 3600 J
     * 1 kWh = 3,600,000 J
     * 1 MWh = 3,600,000,000 J
     * 1 GWh = 3,600,000,000,000 J
     *
     * @return array<array{ElectricEnergyUnit, class-string, numeric-string}>
     */
    private function getElectricUnits(): array
    {
        return [
            [ElectricEnergyUnit::WattHour, WattHour::class, '3600'],
            [ElectricEnergyUnit::KilowattHour, KilowattHour::class, '3600000'],
            [ElectricEnergyUnit::MegawattHour, MegawattHour::class, '3600000000'],
            [ElectricEnergyUnit::GigawattHour, GigawattHour::class, '3600000000000'],
        ];
    }

    /**
     * Centralized thermal energy unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to Joule)].
     *
     * 1 cal = 4.184 J (thermochemical calorie)
     * 1 kcal = 4184 J
     * 1 BTU = 1055.06 J (ISO BTU)
     * 1 therm = 105506000 J (100,000 BTU)
     *
     * @return array<array{ThermalEnergyUnit, class-string, numeric-string}>
     */
    private function getThermalUnits(): array
    {
        return [
            [ThermalEnergyUnit::Calorie, Calorie::class, '4.184'],
            [ThermalEnergyUnit::Kilocalorie, Kilocalorie::class, '4184'],
            [ThermalEnergyUnit::BritishThermalUnit, BritishThermalUnit::class, '1055.06'],
            [ThermalEnergyUnit::Therm, BritishThermalUnit::class, '105506000'],
        ];
    }

    public function registerUnits(UnitRegistry $registry): void
    {
        foreach ($this->getSIUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
        foreach ($this->getElectricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
        foreach ($this->getThermalUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
    }

    public function registerConversionFactors(ConversionFactorRegistry $registry): void
    {
        foreach ($this->getSIUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
        foreach ($this->getElectricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
        foreach ($this->getThermalUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
    }

    public function registerResultMappings(ResultQuantityRegistry $registry): void
    {
        $formula = new DimensionalFormula(length: 2, mass: 1, time: -2);

        // Unit-specific classes → mid-level class (preserves system)
        foreach ($this->getSIUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, SIEnergy::class);
        }
        foreach ($this->getElectricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ElectricEnergy::class);
        }
        foreach ($this->getThermalUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ThermalEnergy::class);
        }

        // Mid-level classes → themselves
        $registry->register(SIEnergy::class, $formula, SIEnergy::class);
        $registry->register(ElectricEnergy::class, $formula, ElectricEnergy::class);
        $registry->register(ThermalEnergy::class, $formula, ThermalEnergy::class);

        // Generic
        $registry->register(Energy::class, $formula, Energy::class);
        $registry->registerGeneric($formula, Energy::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // L²M¹T⁻² → Joule (default unit for energy dimension)
        $registry->register(
            new DimensionalFormula(length: 2, mass: 1, time: -2),
            SIEnergyUnit::Joule,
        );
    }
}
