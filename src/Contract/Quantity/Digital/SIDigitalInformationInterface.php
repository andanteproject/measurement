<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Digital;

/**
 * Interface for SI (decimal) digital information quantities.
 *
 * This interface represents digital information measurements using SI/decimal
 * prefixes (bit, byte, kilobyte, megabyte, gigabyte, terabyte, petabyte).
 *
 * Example:
 * ```php
 * function processSIDigital(SIDigitalInformationInterface $size): void
 * {
 *     // Only accepts SI units (B, KB, MB, GB, TB, PB)
 * }
 * ```
 */
interface SIDigitalInformationInterface extends DigitalInformationInterface
{
}
