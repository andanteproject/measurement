<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Represents a unit of measurement.
 *
 * A unit belongs to a specific dimension and measurement system.
 * Units are typically implemented as enums (e.g., MetricLengthUnit::Meter)
 * or as singleton classes.
 *
 * All methods are instance methods to support both enum cases and class instances.
 * Conversion logic is handled by the Converter class using ConversionFactorRegistry,
 * not by the units themselves.
 */
interface UnitInterface
{
    /**
     * Get the symbol for this unit.
     *
     * Different notations may return different symbol representations:
     * - Default: Most common form (e.g., "Gbps", "m³")
     * - IEEE: Standards-compliant (e.g., "Gbit/s")
     * - ASCII: Keyboard-friendly (e.g., "m3")
     * - Unicode: Proper symbols (e.g., "m³", "μm")
     *
     * If a unit doesn't have a specific notation variant, it falls back to Default.
     *
     * @param SymbolNotation $notation The notation style to use
     */
    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string;

    /**
     * Get the name of this unit.
     *
     * Examples: "meter", "kilogram", "second", "kilowatt-hour"
     */
    public function name(): string;

    /**
     * Get the dimension this unit measures.
     */
    public function dimension(): DimensionInterface;

    /**
     * Get the unit system this unit belongs to.
     *
     * Examples: UnitSystem::Metric, UnitSystem::Imperial, UnitSystem::SI
     */
    public function system(): UnitSystem;
}
