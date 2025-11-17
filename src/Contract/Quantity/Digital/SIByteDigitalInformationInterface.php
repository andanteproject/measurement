<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Digital;

/**
 * Interface for SI byte-based digital information quantities.
 *
 * This interface represents digital information measurements using SI/decimal
 * byte prefixes (byte, kilobyte, megabyte, gigabyte, terabyte, petabyte).
 *
 * Example:
 * ```php
 * function processBytes(SIByteDigitalInformationInterface $info): void
 * {
 *     // Only accepts SI byte units (B, KB, MB, GB, TB, PB)
 * }
 * ```
 */
interface SIByteDigitalInformationInterface extends SIDigitalInformationInterface
{
}
