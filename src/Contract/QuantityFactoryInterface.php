<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Contract\Math\NumberInterface;

/**
 * Factory interface for quantity classes that accept any compatible unit.
 *
 * Implemented by flexible classes like Length, MetricLength, Mass, etc.
 * These classes need both a value and a unit to be specified.
 *
 * Example:
 * ```php
 * $length = Length::from(NumberFactory::create('100'), MetricLengthUnit::Meter);
 * $metric = MetricLength::from(NumberFactory::create('5'), MetricLengthUnit::Kilometer);
 * ```
 */
interface QuantityFactoryInterface
{
    /**
     * Create a quantity with the given value and unit.
     */
    public static function from(NumberInterface $value, UnitInterface $unit): QuantityInterface;
}
