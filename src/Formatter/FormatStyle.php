<?php

declare(strict_types=1);

namespace Andante\Measurement\Formatter;

/**
 * Formatting style for measurements.
 *
 * Determines how the unit is displayed alongside the numeric value.
 */
enum FormatStyle
{
    /**
     * Short style with unit symbol and space.
     *
     * Example: "5 km", "10 m", "3.5 kg"
     */
    case Short;

    /**
     * Long style with full unit name.
     *
     * Example: "5 kilometers", "10 meters", "3.5 kilograms"
     * Uses translated names if available.
     */
    case Long;

    /**
     * Narrow style with no space between number and unit.
     *
     * Example: "5km", "10m", "3.5kg"
     */
    case Narrow;

    /**
     * Value only, no unit displayed.
     *
     * Example: "5", "10", "3.5"
     * Useful for charts, data export, etc.
     */
    case ValueOnly;

    /**
     * Unit symbol only, no value.
     *
     * Example: "km", "m", "kg"
     * Useful for table headers, legends, etc.
     */
    case UnitSymbolOnly;

    /**
     * Unit name only, no value.
     *
     * Example: "kilometers", "meters", "kilograms"
     * Uses translated names if available.
     * Useful for table headers, legends, etc.
     */
    case UnitNameOnly;
}
