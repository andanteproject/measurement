<?php

declare(strict_types=1);

namespace Andante\Measurement\Formatter;

use Andante\Measurement\Unit\SymbolNotation;

/**
 * Immutable configuration options for formatting measurements.
 *
 * Uses builder pattern - all withX() methods return new instances.
 *
 * Supports separate locales for number formatting and unit translation:
 * - `locale`: Controls number formatting (thousand/decimal separators)
 * - `unitLocale`: Controls unit translation (defaults to `locale` if not set)
 *
 * Example:
 * ```php
 * // Default options (en_US style)
 * $options = FormatOptions::create();
 *
 * // Italian locale with 2 decimal places
 * $options = FormatOptions::fromLocale('it_IT')
 *     ->withPrecision(2);
 *
 * // Long style with translations
 * $options = FormatOptions::create()
 *     ->withStyle(FormatStyle::Long);
 *
 * // Custom separators
 * $options = FormatOptions::create()
 *     ->withThousandSeparator(' ')
 *     ->withDecimalSeparator(',');
 *
 * // Italian number formatting with English unit names
 * $options = FormatOptions::fromLocale('it_IT')
 *     ->withUnitLocale('en')
 *     ->withStyle(FormatStyle::Long);
 * // Result: "1.500 meters" (Italian separators, English unit name)
 *
 * // IEEE notation for technical output
 * $options = FormatOptions::create()
 *     ->withNotation(SymbolNotation::IEEE);
 * // "10 Gbit/s" instead of "10 Gbps"
 * ```
 */
final class FormatOptions
{
    private function __construct(
        private readonly ?string $locale,
        private readonly ?string $unitLocale,
        private readonly ?int $precision,
        private readonly ?string $thousandSeparator,
        private readonly ?string $decimalSeparator,
        private readonly FormatStyle $style,
        private readonly SymbolNotation $notation,
    ) {
    }

    /**
     * Create default format options.
     *
     * Defaults:
     * - No locale (uses English defaults)
     * - Precision: auto (preserve input precision, remove trailing zeros)
     * - Thousand separator: ','
     * - Decimal separator: '.'
     * - Style: Short
     * - Notation: Default
     */
    public static function create(): self
    {
        return new self(
            locale: null,
            unitLocale: null,
            precision: null,
            thousandSeparator: null,
            decimalSeparator: null,
            style: FormatStyle::Short,
            notation: SymbolNotation::Default,
        );
    }

    /**
     * Create format options from a locale.
     *
     * The locale determines default thousand/decimal separators using
     * PHP's NumberFormatter (ICU library). Unit translations will also
     * use this locale unless overridden with withUnitLocale().
     *
     * @param string $locale e.g., 'en_US', 'it_IT', 'de_DE'
     */
    public static function fromLocale(string $locale): self
    {
        return new self(
            locale: $locale,
            unitLocale: null,
            precision: null,
            thousandSeparator: null,
            decimalSeparator: null,
            style: FormatStyle::Short,
            notation: SymbolNotation::Default,
        );
    }

    /**
     * Set or change the locale for number formatting.
     *
     * This affects thousand/decimal separators. If unitLocale is not set,
     * this locale will also be used for unit translations.
     *
     * @param string $locale e.g., 'en_US', 'it_IT', 'de_DE'
     */
    public function withLocale(string $locale): self
    {
        return new self($locale, $this->unitLocale, $this->precision, $this->thousandSeparator, $this->decimalSeparator, $this->style, $this->notation);
    }

    /**
     * Set a separate locale for unit translations.
     *
     * Use this when you want number formatting in one locale but unit names
     * in another. For example, Italian number formatting with English unit names:
     *
     * ```php
     * $options = FormatOptions::fromLocale('it_IT')
     *     ->withUnitLocale('en')
     *     ->withStyle(FormatStyle::Long);
     * // "1.500 meters" (Italian separators, English unit name)
     * ```
     *
     * @param string $locale e.g., 'en', 'it_IT', 'de_DE'
     */
    public function withUnitLocale(string $locale): self
    {
        return new self($this->locale, $locale, $this->precision, $this->thousandSeparator, $this->decimalSeparator, $this->style, $this->notation);
    }

    /**
     * Set precision (number of decimal places).
     *
     * @param int|null $precision Number of decimal places, or null for auto (preserve input, remove trailing zeros)
     */
    public function withPrecision(?int $precision): self
    {
        return new self($this->locale, $this->unitLocale, $precision, $this->thousandSeparator, $this->decimalSeparator, $this->style, $this->notation);
    }

    /**
     * Override the thousand separator.
     *
     * @param string $separator e.g., ',', '.', ' ', '''
     */
    public function withThousandSeparator(string $separator): self
    {
        return new self($this->locale, $this->unitLocale, $this->precision, $separator, $this->decimalSeparator, $this->style, $this->notation);
    }

    /**
     * Override the decimal separator.
     *
     * @param string $separator e.g., '.', ','
     */
    public function withDecimalSeparator(string $separator): self
    {
        return new self($this->locale, $this->unitLocale, $this->precision, $this->thousandSeparator, $separator, $this->style, $this->notation);
    }

    /**
     * Set the format style.
     */
    public function withStyle(FormatStyle $style): self
    {
        return new self($this->locale, $this->unitLocale, $this->precision, $this->thousandSeparator, $this->decimalSeparator, $style, $this->notation);
    }

    /**
     * Set the symbol notation style.
     *
     * Different notations produce different symbol representations:
     * - Default: Most common form (e.g., "Gbps", "kWh", "m³")
     * - IEEE: Standards-compliant (e.g., "Gbit/s", "kW·h")
     * - ASCII: Keyboard-friendly (e.g., "m3", "um", "kW*h")
     * - Unicode: Proper Unicode symbols (e.g., "m³", "μm", "kW·h")
     *
     * ```php
     * $options = FormatOptions::create()
     *     ->withNotation(SymbolNotation::IEEE);
     * // "10 Gbit/s" instead of "10 Gbps"
     * ```
     */
    public function withSymbolNotation(SymbolNotation $notation): self
    {
        return new self($this->locale, $this->unitLocale, $this->precision, $this->thousandSeparator, $this->decimalSeparator, $this->style, $notation);
    }

    /**
     * Get the number formatting locale, or null if not set.
     *
     * This locale is used for thousand/decimal separators.
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * Get the unit translation locale.
     *
     * Returns the explicitly set unitLocale, or falls back to the number
     * formatting locale if not set.
     */
    public function getUnitLocale(): ?string
    {
        return $this->unitLocale ?? $this->locale;
    }

    /**
     * Get the precision, or null for auto.
     */
    public function getPrecision(): ?int
    {
        return $this->precision;
    }

    /**
     * Get the format style.
     */
    public function getStyle(): FormatStyle
    {
        return $this->style;
    }

    /**
     * Get the symbol notation style.
     */
    public function getNotation(): SymbolNotation
    {
        return $this->notation;
    }

    /**
     * Get the thousand separator.
     *
     * Returns the explicitly set value, or falls back to locale-based default,
     * or finally to ',' if no locale is set.
     */
    public function getThousandSeparator(): string
    {
        return $this->thousandSeparator ?? $this->getLocaleThousandSeparator();
    }

    /**
     * Get the decimal separator.
     *
     * Returns the explicitly set value, or falls back to locale-based default,
     * or finally to '.' if no locale is set.
     */
    public function getDecimalSeparator(): string
    {
        return $this->decimalSeparator ?? $this->getLocaleDecimalSeparator();
    }

    private function getLocaleThousandSeparator(): string
    {
        if (null === $this->locale) {
            return ',';
        }

        if (\extension_loaded('intl')) {
            $formatter = new \NumberFormatter($this->locale, \NumberFormatter::DECIMAL);

            return $formatter->getSymbol(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL);
        }

        // Simple fallback for common locales without intl extension
        return match (true) {
            \str_starts_with($this->locale, 'it') => '.',
            \str_starts_with($this->locale, 'de') => '.',
            \str_starts_with($this->locale, 'fr') => ' ',
            \str_starts_with($this->locale, 'es') => '.',
            \str_starts_with($this->locale, 'pt') => '.',
            default => ',',
        };
    }

    private function getLocaleDecimalSeparator(): string
    {
        if (null === $this->locale) {
            return '.';
        }

        if (\extension_loaded('intl')) {
            $formatter = new \NumberFormatter($this->locale, \NumberFormatter::DECIMAL);

            return $formatter->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
        }

        // Simple fallback for common locales without intl extension
        return match (true) {
            \str_starts_with($this->locale, 'it') => ',',
            \str_starts_with($this->locale, 'de') => ',',
            \str_starts_with($this->locale, 'fr') => ',',
            \str_starts_with($this->locale, 'es') => ',',
            \str_starts_with($this->locale, 'pt') => ',',
            default => '.',
        };
    }
}
