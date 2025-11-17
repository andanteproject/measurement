<?php

declare(strict_types=1);

namespace Andante\Measurement\Translation;

use Andante\Measurement\Contract\UnitInterface;

/**
 * Loads unit translations from PHP files with fallback chain support.
 *
 * Translation files are organized by locale in the translations directory:
 * - translations/en/length.php
 * - translations/it/length.php
 *
 * Each translation file returns an array mapping unit keys to plural forms:
 * ```php
 * return [
 *     'MetricLengthUnit.Meter' => [
 *         'one' => 'metro',
 *         'other' => 'metri',
 *     ],
 *     // ...
 * ];
 * ```
 *
 * The loader supports a fallback chain (e.g., it_IT → it → en).
 */
final class TranslationLoader implements TranslationLoaderInterface
{
    private string $translationsPath;
    /** @var array<string> */
    private array $fallbackChain;
    /** @var array<string, array<string, string>> Cached translations: unitKey => [pluralRule => name] */
    private array $translations = [];
    /** @var array<string, array<string, string>> User overrides: unitKey => [pluralRule => name] */
    private array $overrides = [];

    /**
     * @param string             $locale           The locale to load (e.g., 'it_IT', 'en', 'fr_FR')
     * @param string|null        $translationsPath Path to translations directory (defaults to library translations)
     * @param array<string>|null $fallbackChain    Custom fallback chain (defaults to auto-generated)
     */
    public function __construct(
        private readonly string $locale,
        ?string $translationsPath = null,
        ?array $fallbackChain = null,
    ) {
        $this->translationsPath = $translationsPath ?? __DIR__.'/translations';
        $this->fallbackChain = $fallbackChain ?? $this->buildFallbackChain($locale);

        $this->loadTranslations();
    }

    public function getUnitName(UnitInterface $unit, PluralRule $pluralRule = PluralRule::One): ?string
    {
        $key = $this->getUnitKey($unit);

        // Check overrides first
        if (isset($this->overrides[$key][$pluralRule->value])) {
            return $this->overrides[$key][$pluralRule->value];
        }

        // Check loaded translations
        if (isset($this->translations[$key][$pluralRule->value])) {
            return $this->translations[$key][$pluralRule->value];
        }

        // Fallback to 'other' if specific rule not found
        if (PluralRule::Other !== $pluralRule) {
            return $this->getUnitName($unit, PluralRule::Other);
        }

        return null;
    }

    public function getUnitTranslation(UnitInterface $unit): array
    {
        $key = $this->getUnitKey($unit);

        // Merge loaded translations with overrides (overrides take precedence)
        $loaded = $this->translations[$key] ?? [];
        $overridden = $this->overrides[$key] ?? [];

        return \array_merge($loaded, $overridden);
    }

    public function hasTranslation(UnitInterface $unit): bool
    {
        $key = $this->getUnitKey($unit);

        return isset($this->translations[$key]) || isset($this->overrides[$key]);
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Register or override a translation for a specific unit.
     *
     * @param UnitInterface         $unit         The unit to register translation for
     * @param array<string, string> $translations Map of plural rule name → translated name
     */
    public function registerTranslation(UnitInterface $unit, array $translations): void
    {
        $key = $this->getUnitKey($unit);
        $this->overrides[$key] = $translations;
    }

    /**
     * Get the fallback chain being used.
     *
     * @return array<string>
     */
    public function getFallbackChain(): array
    {
        return $this->fallbackChain;
    }

    /**
     * Build automatic fallback chain from locale.
     *
     * Examples:
     * - it_IT → [it_IT, it, en]
     * - fr_FR → [fr_FR, fr, en]
     * - en_US → [en_US, en]
     * - en → [en]
     *
     * @return array<string>
     */
    private function buildFallbackChain(string $locale): array
    {
        $chain = [$locale];

        // Add language without region if applicable
        if (\str_contains($locale, '_')) {
            $lang = \explode('_', $locale)[0];
            if (!\in_array($lang, $chain, true)) {
                $chain[] = $lang;
            }
        }

        // Always fall back to English if not already English
        if (!\str_starts_with($locale, 'en')) {
            $chain[] = 'en';
        }

        return $chain;
    }

    /**
     * Load translations from files using fallback chain.
     */
    private function loadTranslations(): void
    {
        // Try each locale in fallback chain (in reverse order so most specific wins)
        $reversedChain = \array_reverse($this->fallbackChain);

        foreach ($reversedChain as $locale) {
            $localePath = $this->translationsPath.'/'.$locale;

            if (!\is_dir($localePath)) {
                continue;
            }

            // Load all translation files for this locale
            $files = \glob($localePath.'/*.php');
            if (false === $files) {
                continue;
            }

            foreach ($files as $file) {
                $this->loadTranslationFile($file);
            }
        }
    }

    /**
     * Load a single translation file.
     */
    private function loadTranslationFile(string $filePath): void
    {
        if (!\file_exists($filePath)) {
            return;
        }

        $data = require $filePath;

        if (!\is_array($data)) {
            return;
        }

        // Merge translations (most specific locale overwrites less specific)
        /** @var array<string, string> $translation */
        foreach ($data as $unitKey => $translation) {
            if (\is_array($translation)) {
                /* @var  $translation */
                $this->translations[$unitKey] = $translation;
            }
        }
    }

    /**
     * Get unique key for a unit.
     *
     * Format: "ClassName.CaseName"
     * Example: "MetricLengthUnit.Meter"
     */
    private function getUnitKey(UnitInterface $unit): string
    {
        $className = $unit::class;
        $pos = \strrpos($className, '\\');
        $shortName = false !== $pos ? \substr($className, $pos + 1) : $className;

        // For enums, get the case name
        if ($unit instanceof \UnitEnum) {
            return \sprintf('%s.%s', $shortName, $unit->name);
        }

        // For objects, use class name + object ID
        return \sprintf('%s.%d', $shortName, \spl_object_id($unit));
    }
}
