<?php

declare(strict_types=1);

namespace Andante\Measurement\Parser;

use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\ParsingException;
use Andante\Measurement\Registry\UnitRegistry;

/**
 * Resolves unit strings to UnitInterface instances using a UnitRegistry.
 *
 * Resolution order:
 * 1. Symbol match (case-insensitive)
 * 2. Translated name match (if locale provided)
 * 3. English name match (case-insensitive)
 * 4. Default unit fallback (if provided and unit string is empty)
 *
 * Example:
 * ```php
 * // Using global instance
 * $resolver = UnitResolver::global();
 *
 * // By symbol
 * $unit = $resolver->resolve('km');        // MetricLengthUnit::Kilometer
 *
 * // By English name
 * $unit = $resolver->resolve('kilometer'); // MetricLengthUnit::Kilometer
 *
 * // By Italian name (with locale)
 * $unit = $resolver->resolve('chilometri', 'it_IT'); // MetricLengthUnit::Kilometer
 *
 * // With default unit
 * $unit = $resolver->resolve('', null, MetricLengthUnit::Meter); // MetricLengthUnit::Meter
 * ```
 */
final class UnitResolver
{
    private static ?self $instance = null;

    /**
     * Get the global UnitResolver instance.
     *
     * Uses the global UnitRegistry by default.
     */
    public static function global(): self
    {
        return self::$instance ??= new self(UnitRegistry::global());
    }

    /**
     * Set a custom global UnitResolver instance.
     */
    public static function setGlobal(self $resolver): void
    {
        self::$instance = $resolver;
    }

    /**
     * Reset the global UnitResolver instance.
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    public function __construct(
        private readonly UnitRegistry $registry,
    ) {
    }

    /**
     * Resolve a unit string to a UnitInterface.
     *
     * @param string             $unitString  The unit symbol or name
     * @param string|null        $locale      Optional locale for translated name lookup
     * @param UnitInterface|null $defaultUnit Optional default unit when no unit is found
     *
     * @return UnitInterface The resolved unit
     *
     * @throws ParsingException If the unit cannot be resolved and no default unit is provided
     */
    public function resolve(
        string $unitString,
        ?string $locale = null,
        ?UnitInterface $defaultUnit = null,
    ): UnitInterface {
        $unitString = \trim($unitString);

        // If unit string is empty and we have a default unit, use it
        if ('' === $unitString && null !== $defaultUnit) {
            return $defaultUnit;
        }

        // If unit string is empty and no default, throw
        if ('' === $unitString) {
            throw new ParsingException('No unit specified and no default unit provided');
        }

        // 1. Try symbol first (case-insensitive) - fastest and language-independent
        $unit = $this->registry->findBySymbol($unitString);
        if (null !== $unit) {
            return $unit;
        }

        // 2. Try translated name if locale provided
        if (null !== $locale) {
            $unit = $this->registry->findByTranslatedName($unitString, $locale);
            if (null !== $unit) {
                return $unit;
            }
        }

        // 3. Try English name (case-insensitive)
        $unit = $this->registry->findByName($unitString);
        if (null !== $unit) {
            return $unit;
        }

        throw new ParsingException(\sprintf('Unknown unit: "%s"', $unitString));
    }

    /**
     * Try to resolve a unit string, returning null on failure.
     *
     * @param string             $unitString  The unit symbol or name
     * @param string|null        $locale      Optional locale for translated name lookup
     * @param UnitInterface|null $defaultUnit Optional default unit when no unit is found
     *
     * @return UnitInterface|null The resolved unit, or null if resolution fails
     */
    public function tryResolve(
        string $unitString,
        ?string $locale = null,
        ?UnitInterface $defaultUnit = null,
    ): ?UnitInterface {
        try {
            return $this->resolve($unitString, $locale, $defaultUnit);
        } catch (ParsingException) {
            return null;
        }
    }
}
