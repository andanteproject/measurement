<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry;

use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Exception\InvalidArgumentException;
use Andante\Measurement\Registry\Provider\AccelerationProvider;
use Andante\Measurement\Registry\Provider\AngleProvider;
use Andante\Measurement\Registry\Provider\AreaProvider;
use Andante\Measurement\Registry\Provider\CalorificValueProvider;
use Andante\Measurement\Registry\Provider\DataTransferRateProvider;
use Andante\Measurement\Registry\Provider\DensityProvider;
use Andante\Measurement\Registry\Provider\DigitalInformationProvider;
use Andante\Measurement\Registry\Provider\ElectricCapacitanceProvider;
use Andante\Measurement\Registry\Provider\ElectricChargeProvider;
use Andante\Measurement\Registry\Provider\ElectricCurrentProvider;
use Andante\Measurement\Registry\Provider\ElectricPotentialProvider;
use Andante\Measurement\Registry\Provider\ElectricResistanceProvider;
use Andante\Measurement\Registry\Provider\EnergyProvider;
use Andante\Measurement\Registry\Provider\ForceProvider;
use Andante\Measurement\Registry\Provider\FrequencyProvider;
use Andante\Measurement\Registry\Provider\IlluminanceProvider;
use Andante\Measurement\Registry\Provider\InductanceProvider;
use Andante\Measurement\Registry\Provider\LengthProvider;
use Andante\Measurement\Registry\Provider\LuminousFluxProvider;
use Andante\Measurement\Registry\Provider\LuminousIntensityProvider;
use Andante\Measurement\Registry\Provider\MagneticFluxProvider;
use Andante\Measurement\Registry\Provider\MassProvider;
use Andante\Measurement\Registry\Provider\PowerProvider;
use Andante\Measurement\Registry\Provider\PressureProvider;
use Andante\Measurement\Registry\Provider\TemperatureProvider;
use Andante\Measurement\Registry\Provider\TimeProvider;
use Andante\Measurement\Registry\Provider\VelocityProvider;
use Andante\Measurement\Registry\Provider\VolumeProvider;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Registry that maps dimensional formulas to their default (base) units.
 *
 * This is used when performing cross-dimension operations (multiply/divide)
 * where no result unit is explicitly specified. The registry provides the
 * "natural" or "SI base" unit for a given dimensional formula.
 *
 * Supports system-specific units: when a result quantity class requires
 * a specific unit system (e.g., ImperialArea requires ImperialAreaUnit),
 * the registry can provide the appropriate unit for that system.
 *
 * Example:
 * ```php
 * $registry = FormulaUnitRegistry::global();
 *
 * // Register default units for dimensional formulas
 * $registry->register(DimensionalFormula::length(), MetricLengthUnit::Meter);
 * $registry->register(DimensionalFormula::length()->power(2), MetricAreaUnit::SquareMeter);
 *
 * // Register system-specific units
 * $registry->registerForSystem(
 *     DimensionalFormula::length()->power(2),
 *     UnitSystem::Imperial,
 *     ImperialAreaUnit::SquareFoot
 * );
 *
 * // Get the default unit for a formula
 * $defaultAreaUnit = $registry->getUnit(DimensionalFormula::length()->power(2));
 * // Returns: MetricAreaUnit::SquareMeter
 *
 * // Get system-specific unit
 * $imperialUnit = $registry->getUnitForSystem(
 *     DimensionalFormula::length()->power(2),
 *     UnitSystem::Imperial
 * );
 * // Returns: ImperialAreaUnit::SquareFoot
 * ```
 */
final class FormulaUnitRegistry
{
    /**
     * Mappings from formula key to default unit.
     *
     * @var array<string, UnitInterface>
     */
    private array $mappings = [];

    /**
     * System-specific mappings: [formulaKey][systemValue] => unit.
     *
     * @var array<string, array<string, UnitInterface>>
     */
    private array $systemMappings = [];

    private static ?self $instance = null;

    /**
     * Get the global registry instance.
     */
    public static function global(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
            self::$instance->registerDefaults();
        }

        return self::$instance;
    }

    /**
     * Set a custom global registry instance.
     *
     * @internal Primarily for testing
     */
    public static function setGlobal(self $registry): void
    {
        self::$instance = $registry;
    }

    /**
     * Reset the global registry.
     *
     * @internal Primarily for testing
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    /**
     * Register a default unit for a dimensional formula.
     *
     * @param DimensionalFormula $formula The dimensional formula
     * @param UnitInterface      $unit    The default unit for this formula
     */
    public function register(DimensionalFormula $formula, UnitInterface $unit): void
    {
        $formulaKey = $this->formulaToKey($formula);
        $this->mappings[$formulaKey] = $unit;
    }

    /**
     * Register a unit for a specific unit system and dimensional formula.
     *
     * @param DimensionalFormula $formula The dimensional formula
     * @param UnitSystem         $system  The unit system
     * @param UnitInterface      $unit    The unit for this system and formula
     */
    public function registerForSystem(
        DimensionalFormula $formula,
        UnitSystem $system,
        UnitInterface $unit,
    ): void {
        $formulaKey = $this->formulaToKey($formula);

        if (!isset($this->systemMappings[$formulaKey])) {
            $this->systemMappings[$formulaKey] = [];
        }

        $this->systemMappings[$formulaKey][$system->value] = $unit;
    }

    /**
     * Get the unit for a dimensional formula.
     *
     * @param DimensionalFormula $formula The dimensional formula
     *
     * @return UnitInterface The unit for this formula
     *
     * @throws InvalidArgumentException If no unit is registered for the formula
     */
    public function getUnit(DimensionalFormula $formula): UnitInterface
    {
        $formulaKey = $this->formulaToKey($formula);

        if (!isset($this->mappings[$formulaKey])) {
            throw new InvalidArgumentException(\sprintf('No unit registered for dimensional formula %s', $formula->toString()));
        }

        return $this->mappings[$formulaKey];
    }

    /**
     * Get the unit for a dimensional formula and unit system.
     *
     * If a system-specific unit is registered, it returns that unit.
     * Otherwise, it falls back to the default unit for the formula.
     *
     * @param DimensionalFormula $formula The dimensional formula
     * @param UnitSystem         $system  The unit system
     *
     * @return UnitInterface The unit for this formula and system
     *
     * @throws InvalidArgumentException If no unit is registered for the formula
     */
    public function getUnitForSystem(DimensionalFormula $formula, UnitSystem $system): UnitInterface
    {
        $formulaKey = $this->formulaToKey($formula);

        // Try system-specific mapping first
        if (isset($this->systemMappings[$formulaKey][$system->value])) {
            return $this->systemMappings[$formulaKey][$system->value];
        }

        // Fall back to default
        return $this->getUnit($formula);
    }

    /**
     * Check if a unit is registered for a formula.
     */
    public function has(DimensionalFormula $formula): bool
    {
        $formulaKey = $this->formulaToKey($formula);

        return isset($this->mappings[$formulaKey]);
    }

    /**
     * Convert a dimensional formula to a unique string key.
     */
    private function formulaToKey(DimensionalFormula $formula): string
    {
        return \sprintf(
            'L%d:M%d:T%d:I%d:Θ%d:N%d:J%d:D%d',
            $formula->length,
            $formula->mass,
            $formula->time,
            $formula->electricCurrent,
            $formula->temperature,
            $formula->amountOfSubstance,
            $formula->luminousIntensity,
            $formula->digital,
        );
    }

    /**
     * Register all default units for common dimensional formulas.
     */
    private function registerDefaults(): void
    {
        LengthProvider::global()->registerFormulaUnits($this);
        MassProvider::global()->registerFormulaUnits($this);
        AreaProvider::global()->registerFormulaUnits($this);
        EnergyProvider::global()->registerFormulaUnits($this);
        VolumeProvider::global()->registerFormulaUnits($this);
        TimeProvider::global()->registerFormulaUnits($this);
        DigitalInformationProvider::global()->registerFormulaUnits($this);
        DataTransferRateProvider::global()->registerFormulaUnits($this);
        TemperatureProvider::global()->registerFormulaUnits($this);
        CalorificValueProvider::global()->registerFormulaUnits($this);
        VelocityProvider::global()->registerFormulaUnits($this);
        AccelerationProvider::global()->registerFormulaUnits($this);
        ForceProvider::global()->registerFormulaUnits($this);
        PressureProvider::global()->registerFormulaUnits($this);
        PowerProvider::global()->registerFormulaUnits($this);
        DensityProvider::global()->registerFormulaUnits($this);
        FrequencyProvider::global()->registerFormulaUnits($this);
        AngleProvider::global()->registerFormulaUnits($this);
        ElectricCurrentProvider::global()->registerFormulaUnits($this);
        ElectricPotentialProvider::global()->registerFormulaUnits($this);
        ElectricResistanceProvider::global()->registerFormulaUnits($this);
        ElectricCapacitanceProvider::global()->registerFormulaUnits($this);
        ElectricChargeProvider::global()->registerFormulaUnits($this);
        InductanceProvider::global()->registerFormulaUnits($this);
        MagneticFluxProvider::global()->registerFormulaUnits($this);
        LuminousIntensityProvider::global()->registerFormulaUnits($this);
        LuminousFluxProvider::global()->registerFormulaUnits($this);
        IlluminanceProvider::global()->registerFormulaUnits($this);
    }
}
