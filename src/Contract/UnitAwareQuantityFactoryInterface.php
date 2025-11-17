<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Contract\Math\NumberInterface;

/**
 * Factory interface for quantity classes that have a fixed, known unit.
 *
 * Implemented by unit-specific classes like Meter, Kilometer, Foot, etc.
 * These classes are "aware" of their unit internally, so only the value is needed.
 *
 * Example:
 * ```php
 * $meter = Meter::from(NumberFactory::create('100'));
 * $kilometer = Kilometer::from(NumberFactory::create('5'));
 * ```
 */
interface UnitAwareQuantityFactoryInterface
{
    /**
     * Create a quantity with the given value.
     *
     * The unit is determined by the implementing class.
     */
    public static function from(NumberInterface $value): QuantityInterface;
}
