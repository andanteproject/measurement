<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Digital;

/**
 * Interface for SI bit-based digital information quantities.
 *
 * This interface represents digital information measurements using the SI
 * bit unit (the base unit of digital information).
 *
 * Example:
 * ```php
 * function processBits(SIBitDigitalInformationInterface $info): void
 * {
 *     // Only accepts SI bit units
 * }
 * ```
 */
interface SIBitDigitalInformationInterface extends SIDigitalInformationInterface
{
}
