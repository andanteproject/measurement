<?php

declare(strict_types=1);

namespace Andante\Measurement\Translation;

use Andante\Measurement\Contract\UnitInterface;

/**
 * Interface for loading unit translations.
 *
 * Implementations should provide translated unit names based on locale
 * and plural rules.
 */
interface TranslationLoaderInterface
{
    /**
     * Get translated unit name for a given plural rule.
     *
     * @param UnitInterface $unit       The unit to translate
     * @param PluralRule    $pluralRule The plural rule to use
     *
     * @return string|null The translated name, or null if not found
     */
    public function getUnitName(UnitInterface $unit, PluralRule $pluralRule = PluralRule::One): ?string;

    /**
     * Get all translation data for a unit.
     *
     * @param UnitInterface $unit The unit to get translations for
     *
     * @return array<string, string> Map of plural rule name → translated name
     */
    public function getUnitTranslation(UnitInterface $unit): array;

    /**
     * Check if a translation exists for a unit.
     *
     * @param UnitInterface $unit The unit to check
     */
    public function hasTranslation(UnitInterface $unit): bool;

    /**
     * Get the locale this loader is using.
     */
    public function getLocale(): string;
}
