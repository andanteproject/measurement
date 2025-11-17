<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Digital;

/**
 * Interface for IEC bit-based digital information quantities.
 *
 * This interface represents digital information measurements using IEC/binary
 * bit prefixes (kibibit, mebibit, gibibit, tebibit, pebibit).
 *
 * Example:
 * ```php
 * function processIECBits(IECBitDigitalInformationInterface $info): void
 * {
 *     // Only accepts IEC bit units (Kib, Mib, Gib, Tib, Pib)
 * }
 * ```
 */
interface IECBitDigitalInformationInterface extends IECDigitalInformationInterface
{
}
