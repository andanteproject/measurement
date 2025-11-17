<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Math\RoundingMode;

/**
 * Interface for unit conversion operations.
 */
interface ConverterInterface
{
    /**
     * Convert a value from one unit to another.
     *
     * @param NumberInterface       $value        The value to convert
     * @param UnitInterface         $fromUnit     The source unit
     * @param UnitInterface         $toUnit       The target unit
     * @param int                   $scale        Number of decimal places in the result (default: 10)
     * @param RoundingModeInterface $roundingMode Rounding mode for the division (default: HalfUp)
     *
     * @return NumberInterface The converted value
     */
    public function convert(
        NumberInterface $value,
        UnitInterface $fromUnit,
        UnitInterface $toUnit,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): NumberInterface;

    /**
     * Convert an entire quantity to a different unit.
     *
     * Returns a new quantity instance with the converted value and target unit.
     *
     * @param QuantityInterface     $quantity     The quantity to convert
     * @param UnitInterface         $toUnit       The target unit
     * @param int                   $scale        Number of decimal places in the result (default: 10)
     * @param RoundingModeInterface $roundingMode Rounding mode for the division (default: HalfUp)
     *
     * @return QuantityInterface A new quantity with the converted value in the target unit
     */
    public function convertQuantity(
        QuantityInterface $quantity,
        UnitInterface $toUnit,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface;

    /**
     * Convert a value to the base unit of its dimension.
     *
     * @param NumberInterface $value The value to convert
     * @param UnitInterface   $unit  The unit the value is in
     *
     * @return NumberInterface The value in base units
     */
    public function toBaseUnit(NumberInterface $value, UnitInterface $unit): NumberInterface;

    /**
     * Convert a value from base units to a target unit.
     *
     * @param NumberInterface       $baseValue    The value in base units
     * @param UnitInterface         $toUnit       The target unit
     * @param int                   $scale        Number of decimal places (default: 10)
     * @param RoundingModeInterface $roundingMode Rounding mode (default: HalfUp)
     *
     * @return NumberInterface The value in the target unit
     */
    public function fromBaseUnit(
        NumberInterface $baseValue,
        UnitInterface $toUnit,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): NumberInterface;
}
