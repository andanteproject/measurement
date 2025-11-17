<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Digital;

/**
 * Interface for IEC (binary) bit-based data transfer rate quantities.
 *
 * This interface represents data transfer rates measured in bits per second
 * using IEC (binary) prefixes (Kibps, Mibps, Gibps).
 * Used when binary prefixes are needed (1024-based).
 *
 * Example:
 * ```php
 * function processBinaryBitRate(IECBitTransferRateInterface $speed): void
 * {
 *     // Only accepts IEC bit-based rates (Kibps, Mibps, Gibps)
 * }
 * ```
 */
interface IECBitTransferRateInterface extends IECTransferRateInterface
{
}
