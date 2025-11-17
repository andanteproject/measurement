<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Converter\ConversionRule;
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

/**
 * Registry storing conversion rules from units to their dimension's base unit.
 *
 * Each dimension has a base unit (e.g., meter for length, kelvin for temperature).
 * This registry stores the conversion rule needed to convert from any unit
 * to its dimension's base unit.
 *
 * Most conversions are simple multiplication:
 *   base_value = value × factor
 *
 * Some conversions (like temperature) require affine transformations:
 *   base_value = value × factor + offset
 *
 * Example:
 * ```php
 * $registry = ConversionFactorRegistry::global();
 *
 * // Simple multiplicative conversions (most units)
 * $registry->register(MetricLengthUnit::Meter, ConversionRule::factor(NumberFactory::create('1')));
 * $registry->register(MetricLengthUnit::Kilometer, ConversionRule::factor(NumberFactory::create('1000')));
 *
 * // Affine conversions with offset (temperature)
 * $registry->register(TemperatureUnit::Celsius, ConversionRule::factor(
 *     NumberFactory::create('1'),
 *     NumberFactory::create('273.15')
 * )); // K = °C + 273.15
 * ```
 */
final class ConversionFactorRegistry
{
    /**
     * @var \WeakMap<UnitInterface, ConversionRule>
     */
    private \WeakMap $conversionRules;

    /**
     * Track registered units for iteration (WeakMap doesn't support iteration).
     *
     * @var array<string, UnitInterface>
     */
    private array $registeredUnits = [];

    private static ?self $instance = null;

    public function __construct()
    {
        $this->conversionRules = new \WeakMap();
    }

    /**
     * Get the global registry instance.
     *
     * The global instance is lazily initialized and automatically
     * registers all default library conversion factors on first access.
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
     * Register a conversion rule for a unit.
     *
     * @param UnitInterface  $unit The unit instance
     * @param ConversionRule $rule The conversion rule (use ConversionRule::factor())
     */
    public function register(UnitInterface $unit, ConversionRule $rule): void
    {
        $this->conversionRules[$unit] = $rule;
        $this->registeredUnits[$this->getUnitKey($unit)] = $unit;
    }

    /**
     * Unregister a conversion factor for a unit.
     */
    public function unregister(UnitInterface $unit): void
    {
        unset($this->conversionRules[$unit]);
        unset($this->registeredUnits[$this->getUnitKey($unit)]);
    }

    /**
     * Get the conversion factor to the base unit for a given unit.
     *
     * Note: For affine conversions, this returns only the factor part.
     * Use getRule() to get the full conversion rule including offset.
     *
     * @param UnitInterface $unit The unit instance
     *
     * @return NumberInterface The conversion factor
     *
     * @throws InvalidArgumentException If no conversion factor is registered for the unit
     */
    public function getFactorToBase(UnitInterface $unit): NumberInterface
    {
        return $this->getRule($unit)->getFactor();
    }

    /**
     * Get the full conversion rule for a unit.
     *
     * @param UnitInterface $unit The unit instance
     *
     * @return ConversionRule The conversion rule
     *
     * @throws InvalidArgumentException If no conversion rule is registered for the unit
     */
    public function getRule(UnitInterface $unit): ConversionRule
    {
        if (!isset($this->conversionRules[$unit])) {
            throw new InvalidArgumentException(\sprintf('No conversion rule registered for unit "%s"', $unit->name()));
        }

        return $this->conversionRules[$unit];
    }

    /**
     * Check if a conversion factor is registered for a unit.
     */
    public function has(UnitInterface $unit): bool
    {
        return isset($this->conversionRules[$unit]);
    }

    /**
     * Get all registered units.
     *
     * @return array<UnitInterface>
     */
    public function getRegisteredUnits(): array
    {
        return \array_values($this->registeredUnits);
    }

    /**
     * Get the base unit for a dimension.
     *
     * The base unit is the one with a conversion factor of 1 and no offset.
     *
     * @param DimensionInterface $dimension The dimension to find the base unit for
     *
     * @return UnitInterface The base unit
     *
     * @throws InvalidArgumentException If no base unit is registered for the dimension
     */
    public function getBaseUnit(DimensionInterface $dimension): UnitInterface
    {
        foreach ($this->registeredUnits as $unit) {
            if (!$unit->dimension()->isCompatibleWith($dimension)) {
                continue;
            }

            $rule = $this->conversionRules[$unit];
            // Base unit has factor=1 and no offset
            if ('1' === $rule->getFactor()->value() && $rule->isMultiplicative()) {
                return $unit;
            }
        }

        throw new InvalidArgumentException(\sprintf('No base unit registered for dimension "%s"', $dimension->getName()));
    }

    /**
     * Generate a unique key for a unit.
     */
    private function getUnitKey(UnitInterface $unit): string
    {
        return $unit::class.'::'.$unit->name();
    }

    /**
     * Register all default library conversion factors.
     *
     * All conversion factors are relative to the SI base unit (e.g., Meter for length).
     */
    private function registerDefaults(): void
    {
        LengthProvider::global()->registerConversionFactors($this);
        MassProvider::global()->registerConversionFactors($this);
        AreaProvider::global()->registerConversionFactors($this);
        EnergyProvider::global()->registerConversionFactors($this);
        VolumeProvider::global()->registerConversionFactors($this);
        TimeProvider::global()->registerConversionFactors($this);
        DigitalInformationProvider::global()->registerConversionFactors($this);
        DataTransferRateProvider::global()->registerConversionFactors($this);
        TemperatureProvider::global()->registerConversionFactors($this);
        CalorificValueProvider::global()->registerConversionFactors($this);
        VelocityProvider::global()->registerConversionFactors($this);
        AccelerationProvider::global()->registerConversionFactors($this);
        ForceProvider::global()->registerConversionFactors($this);
        PressureProvider::global()->registerConversionFactors($this);
        PowerProvider::global()->registerConversionFactors($this);
        DensityProvider::global()->registerConversionFactors($this);
        FrequencyProvider::global()->registerConversionFactors($this);
        AngleProvider::global()->registerConversionFactors($this);
        ElectricCurrentProvider::global()->registerConversionFactors($this);
        ElectricPotentialProvider::global()->registerConversionFactors($this);
        ElectricResistanceProvider::global()->registerConversionFactors($this);
        ElectricCapacitanceProvider::global()->registerConversionFactors($this);
        ElectricChargeProvider::global()->registerConversionFactors($this);
        InductanceProvider::global()->registerConversionFactors($this);
        MagneticFluxProvider::global()->registerConversionFactors($this);
        LuminousIntensityProvider::global()->registerConversionFactors($this);
        LuminousFluxProvider::global()->registerConversionFactors($this);
        IlluminanceProvider::global()->registerConversionFactors($this);
    }
}
