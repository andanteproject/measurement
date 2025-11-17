<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Digital;

/**
 * Interface for SI (decimal) data transfer rate quantities.
 *
 * This interface represents data transfer rates using SI (decimal) prefixes,
 * both bit-based (bps, kbps, Mbps) and byte-based (B/s, KB/s, MB/s).
 *
 * Example:
 * ```php
 * function processSITransferRate(SITransferRateInterface $rate): void
 * {
 *     // Accepts any SI transfer rate (bit or byte based)
 * }
 * ```
 */
interface SITransferRateInterface extends DataTransferRateInterface
{
}
