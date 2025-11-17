<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Digital;

/**
 * Interface for IEC byte-based digital information quantities.
 *
 * This interface represents digital information measurements using IEC/binary
 * byte prefixes (kibibyte, mebibyte, gibibyte, tebibyte, pebibyte).
 *
 * Example:
 * ```php
 * function processIECBytes(IECByteDigitalInformationInterface $info): void
 * {
 *     // Only accepts IEC byte units (KiB, MiB, GiB, TiB, PiB)
 * }
 * ```
 */
interface IECByteDigitalInformationInterface extends IECDigitalInformationInterface
{
}
