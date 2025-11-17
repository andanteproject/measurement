<?php

declare(strict_types=1);

namespace Andante\Measurement\Unit;

/**
 * Defines different notation styles for unit symbols.
 *
 * Different contexts may require different symbol representations:
 * - Default: Most common/recognizable form (e.g., Gbps, kWh, m³)
 * - IEEE: Technical/standards-compliant form (e.g., Gbit/s, kW·h)
 * - ASCII: Keyboard-friendly form without special characters (e.g., m3, um, kW*h)
 * - Unicode: Proper Unicode symbols (e.g., m³, μm, kW·h)
 *
 * When a unit doesn't have a specific notation variant, it falls back to Default.
 *
 * Example:
 * ```php
 * $unit = BitTransferRateUnit::GigabitPerSecond;
 *
 * $unit->symbol();                           // "Gbps"
 * $unit->symbol(SymbolNotation::Default);    // "Gbps"
 * $unit->symbol(SymbolNotation::IEEE);       // "Gbit/s"
 * ```
 */
enum SymbolNotation: string
{
    /**
     * Most common/recognizable symbol form.
     * This is the default when no notation is specified.
     */
    case Default = 'default';

    /**
     * Technical/standards-compliant form (IEEE, SI conventions).
     * Examples: Gbit/s, kW·h.
     */
    case IEEE = 'ieee';

    /**
     * Keyboard-friendly form using only ASCII characters.
     * Examples: m3, um, kW*h.
     */
    case ASCII = 'ascii';

    /**
     * Proper Unicode symbols and characters.
     * Examples: m³, μm, kW·h.
     */
    case Unicode = 'unicode';
}
