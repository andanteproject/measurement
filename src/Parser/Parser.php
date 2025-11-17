<?php

declare(strict_types=1);

namespace Andante\Measurement\Parser;

use Andante\Measurement\Contract\ParserInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Exception\ParsingException;
use Andante\Measurement\Registry\UnitRegistry;

/**
 * Main parser for measurement strings.
 *
 * Parses strings like "5.5 km", "100 ft", "1,234.56 m" into quantity objects.
 * Supports locale-aware number parsing and unit resolution.
 *
 * Example:
 * ```php
 * $parser = Parser::global();
 *
 * // Simple parsing (en_US style)
 * $quantity = $parser->parse('10 km');
 * $quantity = $parser->parse('1,234.56 m');
 *
 * // Italian locale
 * $quantity = $parser->parse('1.234,56 m', ParseOptions::fromLocale('it_IT'));
 *
 * // With default unit (parse numbers without units)
 * $quantity = $parser->parse('100', ParseOptions::create()->withDefaultUnit(MetricLengthUnit::Meter));
 *
 * // Try parse (returns null on failure)
 * $quantity = $parser->tryParse('invalid');
 * ```
 */
final class Parser implements ParserInterface
{
    private static ?self $instance = null;

    private readonly NumberParser $numberParser;
    private readonly UnitResolver $unitResolver;

    public function __construct(
        private readonly ?UnitRegistry $unitRegistry = null,
    ) {
        $this->numberParser = new NumberParser();
        $this->unitResolver = new UnitResolver($this->getUnitRegistry());
    }

    /**
     * Get the global parser instance.
     */
    public static function global(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * Set a custom global parser instance.
     */
    public static function setGlobal(self $parser): void
    {
        self::$instance = $parser;
    }

    /**
     * Reset the global parser instance.
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    public function parse(string $input, ?ParseOptions $options = null): QuantityInterface
    {
        $options ??= ParseOptions::create();

        // Normalize and split input
        $input = $this->normalizeInput($input);
        [$numberPart, $unitPart] = $this->splitInput($input, $options);

        // Parse number - use locale if available, otherwise use explicit separators
        $locale = $options->getLocale();
        if (null !== $locale) {
            $value = $this->numberParser->parseWithLocale($numberPart, $locale);
        } else {
            $value = $this->numberParser->parse(
                $numberPart,
                $options->getThousandSeparator(),
                $options->getDecimalSeparator(),
            );
        }

        // Resolve unit (with locale and default unit fallback)
        $unit = $this->unitResolver->resolve($unitPart, $options->getLocale(), $options->getDefaultUnit());

        // Get the quantity class from the registry
        /** @var class-string<QuantityFactoryInterface> $quantityClass */
        $quantityClass = $this->getUnitRegistry()->getQuantityClass($unit);

        return $quantityClass::from($value, $unit);
    }

    public function tryParse(string $input, ?ParseOptions $options = null): ?QuantityInterface
    {
        try {
            return $this->parse($input, $options);
        } catch (\Throwable) {
            return null;
        }
    }

    private function getUnitRegistry(): UnitRegistry
    {
        return $this->unitRegistry ?? UnitRegistry::global();
    }

    private function normalizeInput(string $input): string
    {
        $input = \trim($input);

        if ('' === $input) {
            throw new ParsingException('Cannot parse empty string');
        }

        // Insert space between number and unit if missing: "5km" → "5 km"
        // Handles: digits followed by letters (including μ for micrometer, ° for degrees)
        $input = \preg_replace('/(\d)([a-zA-Zμ°])/', '$1 $2', $input);
        if (null === $input) {
            throw new ParsingException('Failed to normalize input');
        }

        // Normalize multiple spaces to single space
        $input = \preg_replace('/\s+/', ' ', $input);
        if (null === $input) {
            throw new ParsingException('Failed to normalize input');
        }

        return $input;
    }

    /**
     * @return array{string, string}
     */
    private function splitInput(string $input, ParseOptions $options): array
    {
        // Match number part (including signs, decimals, thousands separators, quotes, thin spaces)
        // Number part: optional sign, digits with optional separators
        // Unit part: everything after the space
        if (1 === \preg_match('/^([+-]?[\d\s,.\'"]+)\s+(.+)$/u', $input, $matches)) {
            return [\trim($matches[1]), \trim($matches[2])];
        }

        // If no unit part found, check if we have a default unit configured
        if (null !== $options->getDefaultUnit()) {
            // Input is just a number - return it with empty unit string
            // UnitResolver will handle using the default unit
            return [\trim($input), ''];
        }

        throw new ParsingException(\sprintf('Invalid measurement format: "%s"', $input));
    }
}
