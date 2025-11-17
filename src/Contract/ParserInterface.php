<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Exception\ParsingException;
use Andante\Measurement\Parser\ParseOptions;

/**
 * Interface for parsing measurement strings into Quantity objects.
 *
 * Implementations should handle:
 * - Locale-aware number parsing (e.g., "1,234.56" vs "1.234,56")
 * - Unit symbol and name recognition (e.g., "km", "kilometer")
 * - Default unit fallback when no unit is in the input
 *
 * Example:
 * ```php
 * $parser = Parser::global();
 *
 * $quantity = $parser->parse('10 km');
 * $quantity = $parser->parse('1.234,56 m', ParseOptions::fromLocale('it_IT'));
 * $quantity = $parser->parse('100', ParseOptions::create()->withDefaultUnit(MetricLengthUnit::Meter));
 * ```
 */
interface ParserInterface
{
    /**
     * Parse a measurement string into a quantity.
     *
     * @param string            $input   The input string (e.g., "10 kWh", "5.5 meters")
     * @param ParseOptions|null $options Parser options (locale, default unit, etc.)
     *
     * @return QuantityInterface The parsed quantity
     *
     * @throws ParsingException If parsing fails
     */
    public function parse(string $input, ?ParseOptions $options = null): QuantityInterface;

    /**
     * Try to parse a measurement string, returning null on failure.
     *
     * @param string            $input   The input string
     * @param ParseOptions|null $options Parser options
     *
     * @return QuantityInterface|null The parsed quantity, or null if parsing fails
     */
    public function tryParse(string $input, ?ParseOptions $options = null): ?QuantityInterface;
}
