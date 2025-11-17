<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Digital;

/**
 * Interface for IEC (binary) data transfer rate quantities.
 *
 * This interface represents data transfer rates using IEC (binary) prefixes,
 * both bit-based (Kibps, Mibps) and byte-based (KiB/s, MiB/s).
 *
 * Example:
 * ```php
 * function processIECTransferRate(IECTransferRateInterface $rate): void
 * {
 *     // Accepts any IEC transfer rate (bit or byte based)
 * }
 * ```
 */
interface IECTransferRateInterface extends DataTransferRateInterface
{
}
