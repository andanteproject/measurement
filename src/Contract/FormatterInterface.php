<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Formatter\FormatOptions;

/**
 * Interface for formatting quantities to human-readable strings.
 *
 * Implementations should handle:
 * - Locale-aware number formatting (e.g., "1,234.56" vs "1.234,56")
 * - Multiple format styles (short, long, narrow, numeric)
 * - Unit translations for long format
 *
 * Example:
 * ```php
 * $formatter = Formatter::global();
 *
 * $quantity = Meter::of(1500);
 * echo $formatter->format($quantity); // "1,500 m"
 *
 * // Italian locale with long style
 * $options = FormatOptions::fromLocale('it_IT')->withStyle(FormatStyle::Long);
 * echo $formatter->format($quantity, $options); // "1.500 metri"
 * ```
 */
interface FormatterInterface
{
    /**
     * Format a quantity as a string.
     *
     * @param QuantityInterface  $quantity The quantity to format
     * @param FormatOptions|null $options  Formatting options (locale, style, precision, etc.)
     *
     * @return string The formatted quantity string
     */
    public function format(QuantityInterface $quantity, ?FormatOptions $options = null): string;
}
