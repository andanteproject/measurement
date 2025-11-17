<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry;

use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\QuantityInterface;
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

/**
 * Registry that maps source quantity classes + result formulas to result quantity classes.
 *
 * When multiplying or dividing quantities, the result has a new dimension.
 * This registry determines which quantity class should be used for the result.
 *
 * The registry uses hierarchical lookup:
 * 1. First, try to find an exact match for the source quantity class
 * 2. Then, try parent classes/interfaces
 * 3. Finally, fall back to a generic mapping for the formula
 *
 * Example:
 * ```php
 * $registry = ResultQuantityRegistry::global();
 *
 * // Register specific mappings
 * $registry->register(MetricLength::class, DimensionalFormula::length()->power(2), MetricArea::class);
 * $registry->register(Length::class, DimensionalFormula::length()->power(2), Area::class);
 *
 * // Lookup
 * $areaClass = $registry->getQuantityClass(MetricLength::class, $areaFormula);
 * // Returns: MetricArea::class (specific match)
 *
 * $areaClass = $registry->getQuantityClass(ImperialLength::class, $areaFormula);
 * // Returns: Area::class (fallback to Length mapping if ImperialArea not registered)
 * ```
 */
final class ResultQuantityRegistry
{
    /**
     * Primary mappings: [sourceClass][formulaKey] => resultClass.
     *
     * @var array<class-string, array<string, class-string<QuantityFactoryInterface>>>
     */
    private array $mappings = [];

    /**
     * Generic formula mappings: [formulaKey] => resultClass.
     * Used when no specific source class mapping is found.
     *
     * @var array<string, class-string<QuantityFactoryInterface>>
     */
    private array $genericMappings = [];

    /**
     * Class hierarchy cache to avoid repeated reflection.
     *
     * @var array<class-string, array<class-string>>
     */
    private array $hierarchyCache = [];

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
     * Register a derived quantity mapping.
     *
     * @param class-string                           $sourceQuantityClass The source quantity class (e.g., MetricLength::class)
     * @param DimensionalFormula                     $resultFormula       The resulting dimensional formula
     * @param class-string<QuantityFactoryInterface> $resultQuantityClass The result quantity class (e.g., MetricArea::class)
     *
     * @throws InvalidArgumentException If result class doesn't implement QuantityFactoryInterface
     */
    public function register(
        string $sourceQuantityClass,
        DimensionalFormula $resultFormula,
        string $resultQuantityClass,
    ): void {
        if (!\is_a($resultQuantityClass, QuantityFactoryInterface::class, true)) {
            throw new InvalidArgumentException(\sprintf('Result quantity class must implement %s, %s given', QuantityFactoryInterface::class, $resultQuantityClass));
        }

        $formulaKey = $this->formulaToKey($resultFormula);

        if (!isset($this->mappings[$sourceQuantityClass])) {
            $this->mappings[$sourceQuantityClass] = [];
        }

        $this->mappings[$sourceQuantityClass][$formulaKey] = $resultQuantityClass;
    }

    /**
     * Register a generic formula mapping (fallback when no specific source class matches).
     *
     * @param DimensionalFormula                     $resultFormula       The resulting dimensional formula
     * @param class-string<QuantityFactoryInterface> $resultQuantityClass The result quantity class
     *
     * @throws InvalidArgumentException If result class doesn't implement QuantityFactoryInterface
     */
    public function registerGeneric(
        DimensionalFormula $resultFormula,
        string $resultQuantityClass,
    ): void {
        if (!\is_a($resultQuantityClass, QuantityFactoryInterface::class, true)) {
            throw new InvalidArgumentException(\sprintf('Result quantity class must implement %s, %s given', QuantityFactoryInterface::class, $resultQuantityClass));
        }

        $formulaKey = $this->formulaToKey($resultFormula);
        $this->genericMappings[$formulaKey] = $resultQuantityClass;
    }

    /**
     * Get the quantity class for a derived quantity.
     *
     * @param class-string       $sourceQuantityClass The source quantity class
     * @param DimensionalFormula $resultFormula       The resulting dimensional formula
     *
     * @return class-string<QuantityFactoryInterface> The result quantity class
     *
     * @throws InvalidArgumentException If no mapping is found
     */
    public function getQuantityClass(string $sourceQuantityClass, DimensionalFormula $resultFormula): string
    {
        $formulaKey = $this->formulaToKey($resultFormula);

        // Try exact source class match first
        if (isset($this->mappings[$sourceQuantityClass][$formulaKey])) {
            return $this->mappings[$sourceQuantityClass][$formulaKey];
        }

        // Try parent classes in the hierarchy
        $hierarchy = $this->getClassHierarchy($sourceQuantityClass);
        foreach ($hierarchy as $parentClass) {
            if (isset($this->mappings[$parentClass][$formulaKey])) {
                return $this->mappings[$parentClass][$formulaKey];
            }
        }

        // Fall back to generic mapping
        if (isset($this->genericMappings[$formulaKey])) {
            return $this->genericMappings[$formulaKey];
        }

        throw new InvalidArgumentException(\sprintf('No result quantity registered for source class "%s" with formula %s', $sourceQuantityClass, $resultFormula->toString()));
    }

    /**
     * Get the quantity class for a derived quantity from a quantity instance.
     *
     * Convenience method that extracts the class from the quantity.
     *
     * @param QuantityInterface  $sourceQuantity The source quantity
     * @param DimensionalFormula $resultFormula  The resulting dimensional formula
     *
     * @return class-string<QuantityFactoryInterface> The result quantity class
     *
     * @throws InvalidArgumentException If no mapping is found
     */
    public function getQuantityClassFromInstance(
        QuantityInterface $sourceQuantity,
        DimensionalFormula $resultFormula,
    ): string {
        return $this->getQuantityClass($sourceQuantity::class, $resultFormula);
    }

    /**
     * Check if a mapping exists for the given source class and formula.
     *
     * @param class-string $sourceQuantityClass
     */
    public function has(string $sourceQuantityClass, DimensionalFormula $resultFormula): bool
    {
        try {
            $this->getQuantityClass($sourceQuantityClass, $resultFormula);

            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
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
     * Get the class hierarchy for a given class (parent classes and interfaces).
     *
     * @param class-string $class
     *
     * @return array<class-string>
     */
    private function getClassHierarchy(string $class): array
    {
        if (isset($this->hierarchyCache[$class])) {
            return $this->hierarchyCache[$class];
        }

        $hierarchy = [];

        // Add parent classes
        $parents = \class_parents($class);
        if (false !== $parents) {
            $hierarchy = \array_merge($hierarchy, \array_values($parents));
        }

        // Add interfaces
        $interfaces = \class_implements($class);
        if (false !== $interfaces) {
            $hierarchy = \array_merge($hierarchy, \array_values($interfaces));
        }

        $this->hierarchyCache[$class] = $hierarchy;

        return $hierarchy;
    }

    /**
     * Register all default derived quantity mappings.
     */
    private function registerDefaults(): void
    {
        LengthProvider::global()->registerResultMappings($this);
        MassProvider::global()->registerResultMappings($this);
        AreaProvider::global()->registerResultMappings($this);
        EnergyProvider::global()->registerResultMappings($this);
        VolumeProvider::global()->registerResultMappings($this);
        TimeProvider::global()->registerResultMappings($this);
        DigitalInformationProvider::global()->registerResultMappings($this);
        DataTransferRateProvider::global()->registerResultMappings($this);
        TemperatureProvider::global()->registerResultMappings($this);
        CalorificValueProvider::global()->registerResultMappings($this);
        VelocityProvider::global()->registerResultMappings($this);
        AccelerationProvider::global()->registerResultMappings($this);
        ForceProvider::global()->registerResultMappings($this);
        PressureProvider::global()->registerResultMappings($this);
        PowerProvider::global()->registerResultMappings($this);
        DensityProvider::global()->registerResultMappings($this);
        FrequencyProvider::global()->registerResultMappings($this);
        AngleProvider::global()->registerResultMappings($this);
        ElectricCurrentProvider::global()->registerResultMappings($this);
        ElectricPotentialProvider::global()->registerResultMappings($this);
        ElectricResistanceProvider::global()->registerResultMappings($this);
        ElectricCapacitanceProvider::global()->registerResultMappings($this);
        ElectricChargeProvider::global()->registerResultMappings($this);
        InductanceProvider::global()->registerResultMappings($this);
        MagneticFluxProvider::global()->registerResultMappings($this);
        LuminousIntensityProvider::global()->registerResultMappings($this);
        LuminousFluxProvider::global()->registerResultMappings($this);
        IlluminanceProvider::global()->registerResultMappings($this);
    }
}
