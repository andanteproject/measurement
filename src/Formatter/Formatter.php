<?php

declare(strict_types=1);

namespace Andante\Measurement\Formatter;

use Andante\Measurement\Contract\FormatterInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Translation\PluralRule;
use Andante\Measurement\Translation\TranslationLoader;
use Andante\Measurement\Translation\TranslationLoaderInterface;
use Andante\Measurement\Unit\SymbolNotation;

/**
 * Formats measurements for display with locale support.
 *
 * This is the main formatting service that converts quantities to
 * human-readable strings with locale-aware number formatting and translations.
 *
 * Supports separate locales for number formatting and unit translation:
 * - Number locale: Controls thousand/decimal separators
 * - Unit locale: Controls unit name translation (defaults to number locale)
 *
 * Example:
 * ```php
 * $formatter = Formatter::global();
 *
 * // Simple formatting (Short style, en_US defaults)
 * echo $formatter->format($quantity); // "1,500 m"
 *
 * // Italian locale
 * echo $formatter->format($quantity, FormatOptions::fromLocale('it_IT')); // "1.500 m"
 *
 * // Long style with unit names (English)
 * $options = FormatOptions::create()->withStyle(FormatStyle::Long);
 * echo $formatter->format($quantity, $options); // "1,500 meters"
 *
 * // Long style with Italian translations
 * $options = FormatOptions::fromLocale('it_IT')->withStyle(FormatStyle::Long);
 * echo $formatter->format($quantity, $options); // "1.500 metri"
 *
 * // Italian number formatting with English unit names
 * $options = FormatOptions::fromLocale('it_IT')
 *     ->withUnitLocale('en')
 *     ->withStyle(FormatStyle::Long);
 * echo $formatter->format($quantity, $options); // "1.500 meters"
 *
 * // Fixed precision
 * $options = FormatOptions::create()->withPrecision(2);
 * echo $formatter->format($quantity, $options); // "1,500.00 m"
 * ```
 */
final class Formatter implements FormatterInterface
{
    private static ?self $instance = null;

    /** @var array<string, TranslationLoaderInterface> Cached translation loaders by locale */
    private array $translationLoaders = [];

    public function __construct()
    {
    }

    /**
     * Get the global formatter instance.
     */
    public static function global(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * Set a custom global formatter instance.
     */
    public static function setGlobal(self $formatter): void
    {
        self::$instance = $formatter;
    }

    /**
     * Reset the global formatter instance.
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    public function format(QuantityInterface $quantity, ?FormatOptions $options = null): string
    {
        $options ??= FormatOptions::create();
        $style = $options->getStyle();
        $notation = $options->getNotation();

        // For unit-only styles, return just the unit
        if (FormatStyle::UnitSymbolOnly === $style) {
            return $quantity->getUnit()->symbol($notation);
        }

        if (FormatStyle::UnitNameOnly === $style) {
            return $this->getUnitName(
                $quantity->getUnit(),
                $quantity->getValue(),
                $options->getUnitLocale(),
            );
        }

        // Format the number
        $formattedNumber = $this->formatNumber(
            $quantity->getValue(),
            $options->getPrecision(),
            $options->getThousandSeparator(),
            $options->getDecimalSeparator(),
            $style,
        );

        // For value-only style, return just the number
        if (FormatStyle::ValueOnly === $style) {
            return $formattedNumber;
        }

        // Get unit string (symbol or name)
        $unitString = $this->getUnitString(
            $quantity->getUnit(),
            $style,
            $quantity->getValue(),
            $options->getUnitLocale(),
            $notation,
        );

        // Combine number and unit
        return $this->combine($formattedNumber, $unitString, $style);
    }

    /**
     * Format a number with locale-specific formatting.
     */
    private function formatNumber(
        NumberInterface $value,
        ?int $precision,
        string $thousandSeparator,
        string $decimalSeparator,
        FormatStyle $style,
    ): string {
        $numberString = $value->value();

        // Apply precision if specified
        if (null !== $precision) {
            $rounded = \round((float) $numberString, $precision);
            $numberString = \number_format($rounded, $precision, '.', '');
        } else {
            // Auto precision: remove trailing zeros
            $numberString = $this->removeTrailingZeros($numberString);
        }

        // Narrow style: no thousand separator
        if (FormatStyle::Narrow === $style) {
            return \str_replace('.', $decimalSeparator, $numberString);
        }

        // Format with thousand separators
        return $this->formatSimple($numberString, $thousandSeparator, $decimalSeparator);
    }

    /**
     * Format a number with custom separators.
     */
    private function formatSimple(string $numberString, string $thousandSeparator, string $decimalSeparator): string
    {
        // Split on decimal point
        $parts = \explode('.', $numberString);
        $integerPart = $parts[0];
        $decimalPart = $parts[1] ?? '';

        // Add thousand separators to integer part
        $integerFormatted = $this->addThousandSeparators($integerPart, $thousandSeparator);

        // Combine with decimal part
        if ('' !== $decimalPart) {
            return $integerFormatted.$decimalSeparator.$decimalPart;
        }

        return $integerFormatted;
    }

    /**
     * Add thousand separators to an integer string.
     */
    private function addThousandSeparators(string $integer, string $separator): string
    {
        // Handle negative numbers
        $negative = \str_starts_with($integer, '-');
        if ($negative) {
            $integer = \substr($integer, 1);
        }

        // Add separators from right to left
        $result = '';
        $length = \strlen($integer);

        for ($i = 0; $i < $length; ++$i) {
            if (0 < $i && ($length - $i) % 3 === 0) {
                $result .= $separator;
            }
            $result .= $integer[$i];
        }

        return $negative ? '-'.$result : $result;
    }

    /**
     * Remove trailing zeros from a decimal number string.
     *
     * Examples:
     * - "1.20000" → "1.2"
     * - "1.00000" → "1"
     * - "1.23456" → "1.23456"
     */
    private function removeTrailingZeros(string $number): string
    {
        if (!\str_contains($number, '.')) {
            return $number;
        }

        // Remove trailing zeros after decimal point
        $trimmed = \rtrim($number, '0');

        // Remove decimal point if no decimals left
        $trimmed = \rtrim($trimmed, '.');

        return $trimmed;
    }

    /**
     * Get the unit string (symbol or name) based on format style.
     */
    private function getUnitString(
        UnitInterface $unit,
        FormatStyle $style,
        NumberInterface $value,
        ?string $locale,
        SymbolNotation $notation,
    ): string {
        return match ($style) {
            FormatStyle::Short, FormatStyle::Narrow, FormatStyle::UnitSymbolOnly => $unit->symbol($notation),
            FormatStyle::Long, FormatStyle::UnitNameOnly => $this->getUnitName($unit, $value, $locale),
            FormatStyle::ValueOnly => '',
        };
    }

    /**
     * Get the unit name with proper pluralization and translation.
     *
     * Fallback chain:
     * 1. Try translated name from TranslationLoader
     * 2. Fall back to unit's name() method
     * 3. Fall back to symbol
     */
    private function getUnitName(UnitInterface $unit, NumberInterface $value, ?string $locale): string
    {
        $pluralRule = PluralRule::select($value->value(), $locale ?? 'en');

        // Try to get translation
        if (null !== $locale) {
            $loader = $this->getTranslationLoader($locale);
            $translated = $loader->getUnitName($unit, $pluralRule);
            if (null !== $translated) {
                return $translated;
            }
        }

        // Fall back to English translation
        $englishLoader = $this->getTranslationLoader('en');
        $translated = $englishLoader->getUnitName($unit, $pluralRule);
        if (null !== $translated) {
            return $translated;
        }

        // Final fallback: unit's name() method
        return $unit->name();
    }

    /**
     * Get or create a translation loader for a locale.
     */
    private function getTranslationLoader(string $locale): TranslationLoaderInterface
    {
        if (!isset($this->translationLoaders[$locale])) {
            $this->translationLoaders[$locale] = new TranslationLoader($locale);
        }

        return $this->translationLoaders[$locale];
    }

    /**
     * Combine formatted number and unit string.
     */
    private function combine(string $number, string $unit, FormatStyle $style): string
    {
        if ('' === $unit) {
            return $number;
        }

        return match ($style) {
            FormatStyle::Short, FormatStyle::Long => $number.' '.$unit,
            FormatStyle::Narrow => $number.$unit,
            FormatStyle::ValueOnly, FormatStyle::UnitSymbolOnly, FormatStyle::UnitNameOnly => $number,
        };
    }
}
