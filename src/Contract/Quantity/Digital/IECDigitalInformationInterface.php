<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Digital;

/**
 * Interface for IEC (binary) digital information quantities.
 *
 * This interface represents digital information measurements using IEC/binary
 * prefixes (kibibyte, mebibyte, gibibyte, tebibyte, pebibyte).
 *
 * Example:
 * ```php
 * function processIECDigital(IECDigitalInformationInterface $size): void
 * {
 *     // Only accepts IEC units (KiB, MiB, GiB, TiB, PiB)
 * }
 * ```
 */
interface IECDigitalInformationInterface extends DigitalInformationInterface
{
}
