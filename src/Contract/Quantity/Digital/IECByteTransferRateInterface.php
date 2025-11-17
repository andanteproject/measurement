<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Digital;

/**
 * Interface for IEC (binary) byte-based data transfer rate quantities.
 *
 * This interface represents data transfer rates measured in bytes per second
 * using IEC (binary) prefixes (KiB/s, MiB/s, GiB/s).
 * Used when binary prefixes are needed (1024-based).
 *
 * Example:
 * ```php
 * function processBinaryByteRate(IECByteTransferRateInterface $speed): void
 * {
 *     // Only accepts IEC byte-based rates (KiB/s, MiB/s, GiB/s)
 * }
 * ```
 */
interface IECByteTransferRateInterface extends IECTransferRateInterface
{
}
