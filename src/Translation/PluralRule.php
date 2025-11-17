<?php

declare(strict_types=1);

namespace Andante\Measurement\Translation;

/**
 * ICU plural rules for internationalization.
 *
 * Different languages have different plural forms. This enum represents
 * the standard ICU plural categories.
 *
 * For most Western European languages (English, Italian, Spanish, French, German),
 * only "one" and "other" are used.
 *
 * @see https://unicode-org.github.io/cldr-staging/charts/latest/supplemental/language_plural_rules.html
 */
enum PluralRule: string
{
    /**
     * Used for zero items in some languages (e.g., Arabic).
     */
    case Zero = 'zero';

    /**
     * Used for exactly one item.
     *
     * Example: "1 meter" (English), "1 metro" (Italian)
     */
    case One = 'one';

    /**
     * Used for exactly two items in some languages (e.g., Arabic).
     */
    case Two = 'two';

    /**
     * Used for a few items in some languages.
     *
     * Example: Russian 2-4 (not ending in 12-14), Polish 2-4
     */
    case Few = 'few';

    /**
     * Used for many items in some languages.
     *
     * Example: Russian 5+, Polish 5+ or ending in 12-14
     */
    case Many = 'many';

    /**
     * Default/fallback category.
     *
     * Example: "5 meters" (English), "5 metri" (Italian)
     */
    case Other = 'other';

    /**
     * Get the appropriate plural rule for a given number in a locale.
     *
     * For Western European languages (English, Italian, French, Spanish, German),
     * uses the simple rule: "one" for n=1, "other" for everything else.
     *
     * @param int|float|string $number The number to check
     * @param string           $locale The locale (e.g., 'en', 'it_IT')
     */
    public static function select(int|float|string $number, string $locale = 'en'): self
    {
        $n = (float) $number;
        $absN = \abs($n);

        // Simple rules for common Western European languages
        // English, Italian, Spanish, French, German, Portuguese
        // Rule: one if n = 1 or 1.0, other otherwise
        if (1.0 === $absN) {
            return self::One;
        }

        return self::Other;
    }
}
