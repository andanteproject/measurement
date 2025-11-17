<?php

declare(strict_types=1);

namespace Andante\Measurement\Exception;

/**
 * Thrown when parsing a string into a quantity fails.
 *
 * Examples:
 * - Invalid number format: "abc meters"
 * - Unknown unit symbol: "10 xyz"
 * - Malformed input: "meters 10"
 * - Locale-specific parsing errors
 */
final class ParsingException extends QuantityException
{
}
