<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Digital;

/**
 * Interface for SI byte-based data transfer rate quantities.
 *
 * This interface represents data transfer rates measured in bytes per second
 * using SI (decimal) prefixes (B/s, KB/s, MB/s, GB/s).
 * Commonly used for file transfer speeds.
 *
 * Example:
 * ```php
 * function processDownloadSpeed(ByteTransferRateInterface $speed): void
 * {
 *     // Only accepts SI byte-based rates (B/s, KB/s, MB/s, GB/s)
 * }
 * ```
 */
interface ByteTransferRateInterface extends SITransferRateInterface
{
}
