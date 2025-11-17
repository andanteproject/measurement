<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Digital;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for data transfer rate quantities.
 *
 * Data transfer rate measures how much digital information is transferred
 * per unit of time.
 * Implementations include BitPerSecond, BytePerSecond, MegabitPerSecond, etc.
 */
interface DataTransferRateInterface extends QuantityInterface
{
}
