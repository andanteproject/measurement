<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Digital;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for digital information quantities.
 *
 * Digital information is measured in bits and bytes.
 * Implementations include Bit, Byte, Kilobyte, Megabyte, Gigabyte, etc.
 */
interface DigitalInformationInterface extends QuantityInterface
{
}
