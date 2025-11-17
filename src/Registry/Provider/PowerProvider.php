<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Power\Imperial\BTUPerHour;
use Andante\Measurement\Quantity\Power\Imperial\ElectricalHorsepower;
use Andante\Measurement\Quantity\Power\Imperial\FootPoundPerSecond;
use Andante\Measurement\Quantity\Power\Imperial\MechanicalHorsepower;
use Andante\Measurement\Quantity\Power\Imperial\MetricHorsepower;
use Andante\Measurement\Quantity\Power\ImperialPower;
use Andante\Measurement\Quantity\Power\Power;
use Andante\Measurement\Quantity\Power\SI\Gigawatt;
use Andante\Measurement\Quantity\Power\SI\Kilowatt;
use Andante\Measurement\Quantity\Power\SI\Megawatt;
use Andante\Measurement\Quantity\Power\SI\Milliwatt;
use Andante\Measurement\Quantity\Power\SI\Watt;
use Andante\Measurement\Quantity\Power\SIPower;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Power\ImperialPowerUnit;
use Andante\Measurement\Unit\Power\SIPowerUnit;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Provides default configuration for Power quantities.
 *
 * Registers all SI and imperial power units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to watt)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to watt):
 * - 1 W = 1 W (base)
 * - 1 mW = 0.001 W
 * - 1 kW = 1000 W
 * - 1 MW = 1,000,000 W
 * - 1 GW = 1,000,000,000 W
 * - 1 hp (mechanical) = 745.69987 W
 * - 1 hp (electrical) = 746 W (exact)
 * - 1 PS (metric hp) = 735.49875 W
 * - 1 ft⋅lbf/s = 1.3558179483314 W
 * - 1 BTU/h = 0.29307107 W
 */
final class PowerProvider implements QuantityDefaultConfigProviderInterface
{
    /**
     * Mechanical horsepower to watt: 1 hp = 745.69987 W.
     * Defined as 550 ft⋅lbf/s.
     */
    private const MECHANICAL_HP_TO_WATT = '745.69987158227022';

    /**
     * Electrical horsepower to watt: 1 hp(E) = 746 W (exact).
     */
    private const ELECTRICAL_HP_TO_WATT = '746';

    /**
     * Metric horsepower to watt: 1 PS = 735.49875 W.
     * Defined as 75 kgf⋅m/s.
     */
    private const METRIC_HP_TO_WATT = '735.49875';

    /**
     * Foot-pound-force per second to watt.
     * 1 ft⋅lbf/s = 1.3558179483314 W.
     */
    private const FOOT_POUND_PER_SECOND_TO_WATT = '1.3558179483314';

    /**
     * BTU per hour to watt (thermochemical).
     * 1 BTU/h = 0.29307107 W.
     */
    private const BTU_PER_HOUR_TO_WATT = '0.29307107';

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
     * Centralized SI power unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to watt)].
     *
     * @return array<array{SIPowerUnit, class-string, numeric-string}>
     */
    private function getSIUnits(): array
    {
        return [
            [SIPowerUnit::Watt, Watt::class, '1'],
            [SIPowerUnit::Milliwatt, Milliwatt::class, '0.001'],
            [SIPowerUnit::Kilowatt, Kilowatt::class, '1000'],
            [SIPowerUnit::Megawatt, Megawatt::class, '1000000'],
            [SIPowerUnit::Gigawatt, Gigawatt::class, '1000000000'],
        ];
    }

    /**
     * Centralized imperial power unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to watt)].
     *
     * @return array<array{ImperialPowerUnit, class-string, numeric-string}>
     */
    private function getImperialUnits(): array
    {
        return [
            [ImperialPowerUnit::MechanicalHorsepower, MechanicalHorsepower::class, self::MECHANICAL_HP_TO_WATT],
            [ImperialPowerUnit::ElectricalHorsepower, ElectricalHorsepower::class, self::ELECTRICAL_HP_TO_WATT],
            [ImperialPowerUnit::MetricHorsepower, MetricHorsepower::class, self::METRIC_HP_TO_WATT],
            [ImperialPowerUnit::FootPoundPerSecond, FootPoundPerSecond::class, self::FOOT_POUND_PER_SECOND_TO_WATT],
            [ImperialPowerUnit::BTUPerHour, BTUPerHour::class, self::BTU_PER_HOUR_TO_WATT],
        ];
    }

    public function registerUnits(UnitRegistry $registry): void
    {
        foreach ($this->getSIUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
    }

    public function registerConversionFactors(ConversionFactorRegistry $registry): void
    {
        foreach ($this->getSIUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
    }

    public function registerResultMappings(ResultQuantityRegistry $registry): void
    {
        $formula = new DimensionalFormula(length: 2, mass: 1, time: -3);

        // Unit-specific classes → mid-level class (preserves system)
        foreach ($this->getSIUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, SIPower::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, ImperialPower::class);
        }

        // Mid-level classes → themselves
        $registry->register(SIPower::class, $formula, SIPower::class);
        $registry->register(ImperialPower::class, $formula, ImperialPower::class);

        // Generic
        $registry->register(Power::class, $formula, Power::class);
        $registry->registerGeneric($formula, Power::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        $formula = new DimensionalFormula(length: 2, mass: 1, time: -3);

        // Default unit for power dimension (SI: watt)
        $registry->register($formula, SIPowerUnit::Watt);

        // Imperial system default
        $registry->registerForSystem($formula, UnitSystem::Imperial, ImperialPowerUnit::MechanicalHorsepower);
    }
}
