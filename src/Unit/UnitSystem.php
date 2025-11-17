<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit;

/**
 * Represents a system of units.
 *
 * This enum defines the major unit systems used for measurements.
 * Each unit belongs to one of these systems, which helps with
 * type safety and conversions.
 */
enum UnitSystem: string
{
    /**
     * International System of Units (SI).
     * The modern form of the metric system.
     * Examples: meter, kilogram, second, ampere, kelvin, mole, candela.
     */
    case SI = 'si';

    /**
     * Metric system (includes SI and metric-derived units).
     * Examples: meter, kilometer, centimeter, liter, gram.
     */
    case Metric = 'metric';

    /**
     * Imperial system (British Imperial).
     * Examples: foot, yard, mile, pound, gallon (UK).
     */
    case Imperial = 'imperial';

    /**
     * United States customary units.
     * Similar to Imperial but with some differences (e.g., gallon).
     * Examples: foot, yard, mile, pound, gallon (US).
     */
    case USCustomary = 'us_customary';

    /**
     * Nautical units.
     * Used primarily in maritime and aviation contexts.
     * Examples: nautical mile, knot.
     */
    case Nautical = 'nautical';

    /**
     * IEC (International Electrotechnical Commission) binary units.
     * Used for binary-based digital information measurements.
     * Examples: kibibyte (KiB), mebibyte (MiB), gibibyte (GiB).
     */
    case IEC = 'iec';

    /**
     * CGS (Centimeter-Gram-Second) system.
     * A variant of the metric system using centimeter, gram, and second as base units.
     * Examples: maxwell (magnetic flux), gauss (magnetic field), erg (energy).
     */
    case CGS = 'cgs';

    /**
     * No specific system (dimensionless or custom units).
     * Examples: ratio, percentage, decibel.
     */
    case None = 'none';

    /**
     * Get a human-readable name for this system.
     */
    public function getName(): string
    {
        return match ($this) {
            self::SI => 'International System of Units',
            self::Metric => 'Metric System',
            self::Imperial => 'Imperial System',
            self::USCustomary => 'US Customary Units',
            self::Nautical => 'Nautical Units',
            self::IEC => 'IEC Binary Units',
            self::CGS => 'CGS System',
            self::None => 'No System',
        };
    }

    /**
     * Check if this system is metric-based (SI, Metric, or CGS).
     */
    public function isMetric(): bool
    {
        return self::SI === $this || self::Metric === $this || self::CGS === $this;
    }

    /**
     * Check if this system is imperial-based (Imperial or US Customary).
     */
    public function isImperial(): bool
    {
        return self::Imperial === $this || self::USCustomary === $this;
    }
}
