<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract\Quantity\Frequency;

use Andante\Measurement\Contract\QuantityInterface;

/**
 * Interface for all frequency quantities.
 *
 * Frequency [T⁻¹] is the number of occurrences per unit time.
 * Common units: Hz, kHz, MHz, GHz, THz, rpm
 */
interface FrequencyInterface extends QuantityInterface
{
}
