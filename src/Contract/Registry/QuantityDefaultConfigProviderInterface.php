<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Registry;

use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;

/**
 * Provides default configuration for a quantity type (e.g., Length, Energy).
 *
 * Each quantity type implements this interface to register all default data
 * into the provided registries. This allows organizing all configuration
 * for a single quantity in one place.
 *
 * Example implementation:
 * ```php
 * final class LengthProvider implements QuantityDefaultConfigProviderInterface
 * {
 *     public function registerUnits(UnitRegistry $registry): void
 *     {
 *         $registry->register(MetricLengthUnit::Meter, Meter::class);
 *         $registry->register(MetricLengthUnit::Kilometer, Kilometer::class);
 *     }
 *     // ... other methods
 * }
 * ```
 */
interface QuantityDefaultConfigProviderInterface
{
    /**
     * Register unit → quantity class mappings.
     *
     * Maps each unit enum case to its corresponding quantity class.
     */
    public function registerUnits(UnitRegistry $registry): void;

    /**
     * Register unit → conversion factor mappings.
     *
     * Maps each unit to its conversion factor relative to the base unit.
     */
    public function registerConversionFactors(ConversionFactorRegistry $registry): void;

    /**
     * Register source class + formula → result class mappings.
     *
     * Defines which quantity class to use when operations produce
     * results with specific dimensional formulas.
     */
    public function registerResultMappings(ResultQuantityRegistry $registry): void;

    /**
     * Register dimensional formula → default unit mappings.
     *
     * Defines the default unit to use for results with specific
     * dimensional formulas.
     */
    public function registerFormulaUnits(FormulaUnitRegistry $registry): void;
}
