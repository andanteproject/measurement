<?php

declare(strict_types=1);

namespace Andante\Measurement\Parser;

use Andante\Measurement\Contract\UnitInterface;

/**
 * Immutable configuration options for parsing measurement strings.
 *
 * Uses builder pattern - all withX() methods return new instances.
 *
 * Example:
 * ```php
 * // Default options (en_US style)
 * $options = ParseOptions::create();
 *
 * // Italian locale
 * $options = ParseOptions::fromLocale('it_IT');
 *
 * // Custom separators
 * $options = ParseOptions::create()
 *     ->withThousandSeparator(' ')
 *     ->withDecimalSeparator(',');
 *
 * // Parse numbers without units
 * $options = ParseOptions::create()
 *     ->withDefaultUnit(MetricLengthUnit::Meter);
 * ```
 */
final class ParseOptions
{
    private function __construct(
        private readonly ?string $locale,
        private readonly ?string $thousandSeparator,
        private readonly ?string $decimalSeparator,
        private readonly ?UnitInterface $defaultUnit,
    ) {
    }

    /**
     * Create default parse options.
     *
     * Defaults:
     * - No locale (uses English defaults)
     * - Thousand separator: ','
     * - Decimal separator: '.'
     * - No default unit
     */
    public static function create(): self
    {
        return new self(
            locale: null,
            thousandSeparator: null,
            decimalSeparator: null,
            defaultUnit: null,
        );
    }

    /**
     * Create parse options from a locale.
     *
     * The locale determines default thousand/decimal separators using
     * PHP's NumberFormatter (ICU library).
     *
     * @param string $locale e.g., 'en_US', 'it_IT', 'de_DE'
     */
    public static function fromLocale(string $locale): self
    {
        return new self(
            locale: $locale,
            thousandSeparator: null,
            decimalSeparator: null,
            defaultUnit: null,
        );
    }

    /**
     * Set or change the locale.
     *
     * @param string $locale e.g., 'en_US', 'it_IT', 'de_DE'
     */
    public function withLocale(string $locale): self
    {
        return new self($locale, $this->thousandSeparator, $this->decimalSeparator, $this->defaultUnit);
    }

    /**
     * Override the thousand separator.
     *
     * @param string $separator e.g., ',', '.', ' ', '''
     */
    public function withThousandSeparator(string $separator): self
    {
        return new self($this->locale, $separator, $this->decimalSeparator, $this->defaultUnit);
    }

    /**
     * Override the decimal separator.
     *
     * @param string $separator e.g., '.', ','
     */
    public function withDecimalSeparator(string $separator): self
    {
        return new self($this->locale, $this->thousandSeparator, $separator, $this->defaultUnit);
    }

    /**
     * Set a default unit to use when no unit is found in the input.
     *
     * This allows parsing plain numbers like "100" or "1,234.56" without requiring
     * an explicit unit in the string.
     *
     * @param UnitInterface $unit The unit to assume when none is provided
     */
    public function withDefaultUnit(UnitInterface $unit): self
    {
        return new self($this->locale, $this->thousandSeparator, $this->decimalSeparator, $unit);
    }

    /**
     * Get the locale, or null if not set.
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * Get the default unit, or null if not set.
     */
    public function getDefaultUnit(): ?UnitInterface
    {
        return $this->defaultUnit;
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
