<?php

declare(strict_types=1);

namespace Andante\Measurement\Exception;

/**
 * Thrown when formatting a quantity to a string fails.
 *
 * Examples:
 * - Invalid locale configuration
 * - Unknown format options
 * - Formatting configuration errors
 */
final class FormattingException extends QuantityException
{
}
