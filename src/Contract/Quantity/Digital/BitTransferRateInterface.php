<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Digital;

/**
 * Interface for SI bit-based data transfer rate quantities.
 *
 * This interface represents data transfer rates measured in bits per second
 * using SI (decimal) prefixes (bps, kbps, Mbps, Gbps).
 * Commonly used for network speeds.
 *
 * Example:
 * ```php
 * function processNetworkSpeed(BitTransferRateInterface $speed): void
 * {
 *     // Only accepts SI bit-based rates (bps, kbps, Mbps, Gbps)
 * }
 * ```
 */
interface BitTransferRateInterface extends SITransferRateInterface
{
}
