<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Exception\InvalidArgumentException;
use Andante\Measurement\Exception\InvalidOperationException;
use Andante\Measurement\Math\RoundingMode;

/**
 * Interface for quantities that can be converted to different units.
 *
 * Implementations should delegate to the Converter service for the actual
 * conversion logic, ensuring consistent behavior across the library.
 *
 * Example:
 * ```php
 * $meter1000 = Meter::from(NumberFactory::create('1000'));
 * $km = $meter1000->to(MetricLengthUnit::Kilometer); // 1 kilometer
 * ```
 */
interface ConvertibleInterface
{
    /**
     * Convert this quantity to a different unit.
     *
     * Returns a new quantity with the converted value in the target unit.
     *
     * @param UnitInterface         $unit         The target unit
     * @param int                   $scale        Number of decimal places (default: 10)
     * @param RoundingModeInterface $roundingMode Rounding mode (default: HalfUp)
     *
     * @throws InvalidOperationException If units are from different dimensions
     * @throws InvalidArgumentException  If conversion factors are not registered
     */
    public function to(
        UnitInterface $unit,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface;

    /**
     * Convert this quantity to the base SI unit of its dimension.
     *
     * Returns a new quantity with the value expressed in the base unit.
     * For example, kilometers become meters, kilowatt-hours become joules.
     *
     * @return QuantityInterface The quantity in base units
     *
     * @throws InvalidArgumentException If conversion factor is not registered
     */
    public function toBaseUnit(): QuantityInterface;
}
