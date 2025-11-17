<?php

declare(strict_types=1);

namespace Andante\Measurement\Exception;

use Exception;

/**
 * Base exception for all quantity-related errors.
 *
 * All exceptions thrown by this library extend this base exception,
 * allowing users to catch all library exceptions with a single catch block.
 */
abstract class QuantityException extends \Exception
{
}
