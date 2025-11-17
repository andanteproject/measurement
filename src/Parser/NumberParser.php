<?php

declare(strict_types=1);

namespace Andante\Measurement\Parser;

use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Exception\ParsingException;
use Andante\Measurement\Math\NumberFactory;

/**
 * Parses number strings with locale support.
 *
 * Supports two modes:
 * 1. Locale-based parsing using NumberFormatter (recommended)
 * 2. Manual separator-based parsing (fallback)
 *
 * Example:
 * ```php
 * // Using global instance
 * $parser = NumberParser::global();
 *
 * // Using locale (recommended - uses NumberFormatter)
 * $number = $parser->parseWithLocale('1.234,56', 'it_IT'); // 1234.56
 * $number = $parser->parseWithLocale('1,234.56', 'en_US'); // 1234.56
 *
 * // Using explicit separators (fallback)
 * $number = $parser->parse('1,234.56', ',', '.'); // 1234.56
 * $number = $parser->parse('1.234,56', '.', ','); // 1234.56
 * ```
 */
final class NumberParser
{
    private static ?self $instance = null;

    /**
     * Get the global NumberParser instance.
     */
    public static function global(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * Set a custom global NumberParser instance.
     */
    public static function setGlobal(self $parser): void
    {
        self::$instance = $parser;
    }

    /**
     * Reset the global NumberParser instance.
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    /**
     * Parse a number string using locale settings.
     *
     * Uses PHP's NumberFormatter (intl extension) for accurate locale-aware parsing.
     * Falls back to manual parsing if intl extension is not available.
     *
     * @param string $input  The number string to parse
     * @param string $locale The locale (e.g., 'en_US', 'it_IT', 'de_DE')
     *
     * @return NumberInterface The parsed number
     *
     * @throws ParsingException If the number cannot be parsed
     */
    public function parseWithLocale(string $input, string $locale): NumberInterface
    {
        $input = \trim($input);

        if ('' === $input) {
            throw new ParsingException('Cannot parse empty string as number');
        }

        // Use NumberFormatter if available (best accuracy)
        if (\extension_loaded('intl')) {
            return $this->parseWithIntl($input, $locale);
        }

        // Fallback to manual parsing with locale-derived separators
        $thousandSeparator = $this->getLocaleThousandSeparator($locale);
        $decimalSeparator = $this->getLocaleDecimalSeparator($locale);

        return $this->parseWithSeparators($input, $thousandSeparator, $decimalSeparator);
    }

    /**
     * Parse a number string with explicit separators.
     *
     * @param string $input             The number string to parse
     * @param string $thousandSeparator The thousand separator (e.g., ',', '.', ' ', ''')
     * @param string $decimalSeparator  The decimal separator (e.g., '.', ',')
     *
     * @return NumberInterface The parsed number
     *
     * @throws ParsingException If the number cannot be parsed
     */
    public function parse(
        string $input,
        string $thousandSeparator,
        string $decimalSeparator,
    ): NumberInterface {
        $input = \trim($input);

        if ('' === $input) {
            throw new ParsingException('Cannot parse empty string as number');
        }

        return $this->parseWithSeparators($input, $thousandSeparator, $decimalSeparator);
    }

    /**
     * Try to parse a number string using locale, returning null on failure.
     *
     * @param string $input  The number string to parse
     * @param string $locale The locale
     *
     * @return NumberInterface|null The parsed number, or null if parsing fails
     */
    public function tryParseWithLocale(string $input, string $locale): ?NumberInterface
    {
        try {
            return $this->parseWithLocale($input, $locale);
        } catch (ParsingException) {
            return null;
        }
    }

    /**
     * Try to parse a number string with explicit separators, returning null on failure.
     *
     * @param string $input             The number string to parse
     * @param string $thousandSeparator The thousand separator
     * @param string $decimalSeparator  The decimal separator
     *
     * @return NumberInterface|null The parsed number, or null if parsing fails
     */
    public function tryParse(
        string $input,
        string $thousandSeparator,
        string $decimalSeparator,
    ): ?NumberInterface {
        try {
            return $this->parse($input, $thousandSeparator, $decimalSeparator);
        } catch (ParsingException) {
            return null;
        }
    }

    /**
     * Parse using PHP's intl extension (NumberFormatter).
     */
    private function parseWithIntl(string $input, string $locale): NumberInterface
    {
        $formatter = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);

        // Enable lenient parsing to handle various formats
        $formatter->setAttribute(\NumberFormatter::LENIENT_PARSE, 1);

        $position = 0;
        $result = $formatter->parse($input, \NumberFormatter::TYPE_DOUBLE, $position);

        if (false === $result) {
            throw new ParsingException(\sprintf('Invalid number format for locale "%s": "%s"', $locale, $input));
        }

        // Check if the entire input was consumed
        // Allow trailing whitespace but not other characters
        $remaining = \substr($input, $position);
        if ('' !== \trim($remaining)) {
            throw new ParsingException(\sprintf('Invalid number format for locale "%s": "%s" (unexpected characters: "%s")', $locale, $input, $remaining));
        }

        // Convert to string for arbitrary precision
        // Use enough precision to preserve the original value
        /** @var numeric-string $stringValue */
        $stringValue = $this->floatToString($result);

        return NumberFactory::create($stringValue);
    }

    /**
     * Parse using manual separator replacement.
     */
    private function parseWithSeparators(
        string $input,
        string $thousandSeparator,
        string $decimalSeparator,
    ): NumberInterface {
        // Remove thousand separators (only if different from decimal separator)
        if ($thousandSeparator !== $decimalSeparator && '' !== $thousandSeparator) {
            $normalized = \str_replace($thousandSeparator, '', $input);
        } else {
            $normalized = $input;
        }

        // Replace decimal separator with '.'
        if ('.' !== $decimalSeparator) {
            $normalized = \str_replace($decimalSeparator, '.', $normalized);
        }

        // Remove various space characters (thin space, non-breaking space, etc.)
        /** @var numeric-string $normalized */
        $normalized = \preg_replace('/[\s\x{00A0}\x{202F}]+/u', '', $normalized);
        if (null === $normalized) {
            throw new ParsingException(\sprintf('Invalid number format: "%s"', $input));
        }

        // Validate format: optional sign, digits, optional decimal part, optional scientific notation
        if (1 !== \preg_match('/^[+-]?\d+(\.\d+)?([eE][+-]?\d+)?$/', $normalized)) {
            throw new ParsingException(\sprintf('Invalid number format: "%s"', $input));
        }

        return NumberFactory::create($normalized);
    }

    /**
     * Convert a float to string with appropriate precision.
     *
     * Handles floating-point representation issues by rounding to reasonable precision.
     * PHP_FLOAT_DIG (typically 15) is the number of significant decimal digits
     * that can be round-tripped without precision loss.
     */
    private function floatToString(float $value): string
    {
        // Handle special cases
        if (0.0 === $value) {
            return '0';
        }

        // Round to reasonable precision to avoid floating-point artifacts
        // Use round() with precision based on the magnitude of the number
        $magnitude = (int) \floor(\log10(\abs($value)));
        $precision = \max(0, 10 - $magnitude);
        $rounded = \round($value, $precision);

        // Convert to string
        $stringValue = \sprintf('%.'.$precision.'f', $rounded);

        // Remove trailing zeros after decimal point
        if (\str_contains($stringValue, '.')) {
            $stringValue = \rtrim(\rtrim($stringValue, '0'), '.');
        }

        return $stringValue;
    }

    /**
     * Get thousand separator for a locale (fallback without intl).
     */
    private function getLocaleThousandSeparator(string $locale): string
    {
        return match (true) {
            \str_starts_with($locale, 'it') => '.',
            \str_starts_with($locale, 'de') => '.',
            \str_starts_with($locale, 'fr') => ' ',
            \str_starts_with($locale, 'es') => '.',
            \str_starts_with($locale, 'pt') => '.',
            default => ',',
        };
    }

    /**
     * Get decimal separator for a locale (fallback without intl).
     */
    private function getLocaleDecimalSeparator(string $locale): string
    {
        return match (true) {
            \str_starts_with($locale, 'it') => ',',
            \str_starts_with($locale, 'de') => ',',
            \str_starts_with($locale, 'fr') => ',',
            \str_starts_with($locale, 'es') => ',',
            \str_starts_with($locale, 'pt') => ',',
            default => '.',
        };
    }
}
