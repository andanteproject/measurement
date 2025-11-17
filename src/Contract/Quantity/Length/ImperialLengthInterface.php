<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Length;

/**
 * Interface for imperial length quantities.
 *
 * This interface represents length measurements in the imperial/US customary
 * system (feet, inches, yards, miles, etc.). Use this for type-hinting
 * when you specifically need imperial lengths.
 *
 * Example:
 * ```php
 * function processImperialLength(ImperialLengthInterface $length): void
 * {
 *     // Only accepts imperial units (ft, in, yd, mi, etc.)
 * }
 * ```
 */
interface ImperialLengthInterface extends LengthInterface
{
}
