<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
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
use Andante\Measurement\Translation\TranslationLoader;
use Andante\Measurement\Translation\TranslationLoaderInterface;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Registry that maps units to their corresponding quantity classes.
 *
 * This registry maintains:
 * - Primary mapping: Unit instance -> Quantity class
 * - Dimension index: Dimension -> Units
 * - System index: Dimension -> System -> Units
 *
 * Uses WeakMap for efficient memory management and automatic garbage collection.
 *
 * Example:
 * ```php
 * $registry = UnitRegistry::global();
 * $registry->register(MetricLengthUnit::Meter, Meter::class);
 * $registry->register(ImperialLengthUnit::Foot, Foot::class);
 *
 * $quantityClass = $registry->getQuantityClass(MetricLengthUnit::Meter);
 * // Returns: Meter::class
 *
 * $metricUnits = $registry->getUnitsForSystem(Length::instance(), UnitSystem::Metric);
 * // Returns: [MetricLengthUnit::Meter, MetricLengthUnit::Kilometer, ...]
 * ```
 */
final class UnitRegistry
{
    /**
     * @var \WeakMap<UnitInterface, class-string>
     */
    private \WeakMap $quantityClasses;

    /**
     * @var \WeakMap<DimensionInterface, array<UnitInterface>>
     */
    private \WeakMap $dimensionIndex;

    /**
     * @var \WeakMap<DimensionInterface, \WeakMap<UnitSystem, array<UnitInterface>>>
     */
    private \WeakMap $systemIndex;

    private static ?self $instance = null;

    public function __construct()
    {
        $this->quantityClasses = new \WeakMap();
        $this->dimensionIndex = new \WeakMap();
        $this->systemIndex = new \WeakMap();
    }

    /**
     * Get the global registry instance.
     *
     * The global instance is lazily initialized and automatically
     * registers all default library units on first access.
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
     * Register a unit with its corresponding quantity class.
     *
     * @param UnitInterface $unit          The unit instance (enum case or singleton)
     * @param class-string  $quantityClass The quantity class for this unit
     *
     * @throws InvalidArgumentException If the quantity class doesn't implement QuantityInterface
     */
    public function register(UnitInterface $unit, string $quantityClass): void
    {
        if (!\is_a($quantityClass, QuantityInterface::class, true)) {
            throw new InvalidArgumentException(\sprintf('Quantity class must implement %s, %s given', QuantityInterface::class, $quantityClass));
        }

        // Store primary mapping
        $this->quantityClasses[$unit] = $quantityClass;

        // Build dimension index
        $dimension = $unit->dimension();
        if (!isset($this->dimensionIndex[$dimension])) {
            $this->dimensionIndex[$dimension] = [];
        }
        $this->dimensionIndex[$dimension][] = $unit;

        // Build system index
        $system = $unit->system();
        if (!isset($this->systemIndex[$dimension])) {
            /** @var \WeakMap<UnitSystem, array<UnitInterface>> $systemMap */
            $systemMap = new \WeakMap();
            $this->systemIndex[$dimension] = $systemMap;
        }

        $systemMap = $this->systemIndex[$dimension];
        if (!isset($systemMap[$system])) {
            $systemMap[$system] = [];
        }
        $systemMap[$system][] = $unit;
    }

    /**
     * Unregister a unit from the registry.
     *
     * Note: This only removes the primary mapping. The unit may still
     * appear in dimension/system indexes until garbage collected.
     */
    public function unregister(UnitInterface $unit): void
    {
        unset($this->quantityClasses[$unit]);
    }

    /**
     * Get the quantity class for a given unit.
     *
     * @param UnitInterface $unit The unit instance
     *
     * @return class-string The quantity class
     *
     * @throws InvalidArgumentException If the unit is not registered
     */
    public function getQuantityClass(UnitInterface $unit): string
    {
        if (!isset($this->quantityClasses[$unit])) {
            throw new InvalidArgumentException(\sprintf('Unit "%s" is not registered', $unit->name()));
        }

        return $this->quantityClasses[$unit];
    }

    /**
     * Check if a unit is registered.
     */
    public function has(UnitInterface $unit): bool
    {
        return isset($this->quantityClasses[$unit]);
    }

    /**
     * Get all units for a given dimension.
     *
     * @return array<UnitInterface>
     */
    public function getUnitsForDimension(DimensionInterface $dimension): array
    {
        return $this->dimensionIndex[$dimension] ?? [];
    }

    /**
     * Get all units for a given dimension and system.
     *
     * @return array<UnitInterface>
     */
    public function getUnitsForSystem(DimensionInterface $dimension, UnitSystem $system): array
    {
        if (!isset($this->systemIndex[$dimension])) {
            return [];
        }

        $systemMap = $this->systemIndex[$dimension];
        if (!isset($systemMap[$system])) {
            return [];
        }

        return $systemMap[$system];
    }

    /**
     * Get all metric units for a given dimension.
     *
     * @return array<UnitInterface>
     */
    public function getMetricUnits(DimensionInterface $dimension): array
    {
        return $this->getUnitsForSystem($dimension, UnitSystem::Metric);
    }

    /**
     * Get all imperial units for a given dimension.
     *
     * @return array<UnitInterface>
     */
    public function getImperialUnits(DimensionInterface $dimension): array
    {
        return $this->getUnitsForSystem($dimension, UnitSystem::Imperial);
    }

    /**
     * Get all SI units for a given dimension.
     *
     * @return array<UnitInterface>
     */
    public function getSIUnits(DimensionInterface $dimension): array
    {
        return $this->getUnitsForSystem($dimension, UnitSystem::SI);
    }

    /**
     * Filter units using a custom predicate.
     *
     * @param callable(UnitInterface): bool $predicate The filter function
     *
     * @return array<UnitInterface> Units that match the predicate
     */
    public function filter(callable $predicate): array
    {
        $results = [];
        foreach ($this->dimensionIndex as $units) {
            foreach ($units as $unit) {
                if ($predicate($unit)) {
                    $results[] = $unit;
                }
            }
        }

        return $results;
    }

    /**
     * Find a unit by its symbol (case-insensitive).
     *
     * Checks all notation variants (Default, IEEE, ASCII, Unicode) to ensure
     * that any valid symbol representation can be parsed.
     *
     * @param string $symbol The unit symbol to search for (e.g., "km", "m", "ft", "Gbit/s")
     *
     * @return UnitInterface|null The matching unit, or null if not found
     */
    public function findBySymbol(string $symbol): ?UnitInterface
    {
        $symbolLower = \strtolower($symbol);

        foreach ($this->dimensionIndex as $units) {
            foreach ($units as $unit) {
                // Check all notation variants
                foreach (SymbolNotation::cases() as $notation) {
                    if (\strtolower($unit->symbol($notation)) === $symbolLower) {
                        return $unit;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Find a unit by its name (case-insensitive).
     *
     * @param string $name The unit name to search for (e.g., "kilometer", "meter", "foot")
     *
     * @return UnitInterface|null The matching unit, or null if not found
     */
    public function findByName(string $name): ?UnitInterface
    {
        $nameLower = \strtolower($name);

        foreach ($this->dimensionIndex as $units) {
            foreach ($units as $unit) {
                if (\strtolower($unit->name()) === $nameLower) {
                    return $unit;
                }
            }
        }

        return null;
    }

    /**
     * Find a unit by symbol or name (case-insensitive).
     *
     * First tries symbol lookup, then falls back to name lookup.
     *
     * @param string $identifier The unit symbol or name to search for
     *
     * @return UnitInterface|null The matching unit, or null if not found
     */
    public function find(string $identifier): ?UnitInterface
    {
        return $this->findBySymbol($identifier) ?? $this->findByName($identifier);
    }

    /**
     * Find a unit by its translated name (case-insensitive).
     *
     * Searches through all registered units and checks if any of their
     * translated names (singular or plural) match the given name.
     *
     * @param string                            $name   The translated unit name (e.g., "metri", "chilometri")
     * @param TranslationLoaderInterface|string $loader Translation loader instance or locale string
     *
     * @return UnitInterface|null The matching unit, or null if not found
     */
    public function findByTranslatedName(string $name, TranslationLoaderInterface|string $loader): ?UnitInterface
    {
        $nameLower = \strtolower($name);

        // Create loader if locale string provided
        if (\is_string($loader)) {
            $loader = new TranslationLoader($loader);
        }

        foreach ($this->dimensionIndex as $units) {
            foreach ($units as $unit) {
                $translations = $loader->getUnitTranslation($unit);

                foreach ($translations as $translatedName) {
                    if (\strtolower($translatedName) === $nameLower) {
                        return $unit;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Register all default library units.
     */
    private function registerDefaults(): void
    {
        LengthProvider::global()->registerUnits($this);
        MassProvider::global()->registerUnits($this);
        AreaProvider::global()->registerUnits($this);
        EnergyProvider::global()->registerUnits($this);
        VolumeProvider::global()->registerUnits($this);
        TimeProvider::global()->registerUnits($this);
        DigitalInformationProvider::global()->registerUnits($this);
        DataTransferRateProvider::global()->registerUnits($this);
        TemperatureProvider::global()->registerUnits($this);
        CalorificValueProvider::global()->registerUnits($this);
        VelocityProvider::global()->registerUnits($this);
        AccelerationProvider::global()->registerUnits($this);
        ForceProvider::global()->registerUnits($this);
        PressureProvider::global()->registerUnits($this);
        PowerProvider::global()->registerUnits($this);
        DensityProvider::global()->registerUnits($this);
        FrequencyProvider::global()->registerUnits($this);
        AngleProvider::global()->registerUnits($this);
        ElectricCurrentProvider::global()->registerUnits($this);
        ElectricPotentialProvider::global()->registerUnits($this);
        ElectricResistanceProvider::global()->registerUnits($this);
        ElectricCapacitanceProvider::global()->registerUnits($this);
        ElectricChargeProvider::global()->registerUnits($this);
        InductanceProvider::global()->registerUnits($this);
        MagneticFluxProvider::global()->registerUnits($this);
        LuminousIntensityProvider::global()->registerUnits($this);
        LuminousFluxProvider::global()->registerUnits($this);
        IlluminanceProvider::global()->registerUnits($this);
    }
}
