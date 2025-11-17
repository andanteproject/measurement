<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Exception\InvalidOperationException;
use Andante\Measurement\Math\RoundingMode;

/**
 * Interface for quantities that support arithmetic operations.
 *
 * Implementations should delegate to the Calculator service for the actual
 * calculation logic, ensuring consistent behavior across the library.
 *
 * Operations between quantities of the same dimension return a quantity
 * in the same unit as the first operand (this).
 *
 * Example:
 * ```php
 * $m100 = Meter::from(NumberFactory::create('100'));
 * $km1 = Kilometer::from(NumberFactory::create('1'));
 *
 * $sum = $m100->add($km1); // 1100 meters
 * $double = $m100->multiplyBy(NumberFactory::create('2')); // 200 meters
 * ```
 */
interface CalculableInterface
{
    /**
     * Add another quantity to this one.
     *
     * The result is in the unit of this quantity.
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function add(QuantityInterface $other): QuantityInterface;

    /**
     * Subtract another quantity from this one.
     *
     * The result is in the unit of this quantity.
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function subtract(QuantityInterface $other): QuantityInterface;

    /**
     * Multiply this quantity by a scalar value.
     *
     * The result is in the same unit as this quantity.
     */
    public function multiplyBy(NumberInterface $scalar): QuantityInterface;

    /**
     * Divide this quantity by a scalar value.
     *
     * The result is in the same unit as this quantity.
     *
     * @param NumberInterface $scalar       The divisor
     * @param int             $scale        Number of decimal places (default: 10)
     * @param RoundingMode    $roundingMode Rounding mode (default: HalfUp)
     */
    public function divideBy(
        NumberInterface $scalar,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface;

    /**
     * Multiply this quantity by another quantity.
     *
     * Creates a derived quantity with a new dimension.
     * For example: Length × Length = Area, Length × Force = Energy.
     *
     * @param QuantityInterface  $other        The quantity to multiply by
     * @param UnitInterface|null $resultUnit   The unit for the result (optional)
     * @param int                $scale        Number of decimal places (default: 10)
     * @param RoundingMode       $roundingMode Rounding mode (default: HalfUp)
     *
     * @throws InvalidOperationException If no unit is registered for the result formula
     */
    public function multiply(
        QuantityInterface $other,
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface;

    /**
     * Divide this quantity by another quantity.
     *
     * Creates a derived quantity with a new dimension.
     * For example: Length / Time = Velocity, Energy / Time = Power.
     *
     * @param QuantityInterface  $other        The quantity to divide by
     * @param UnitInterface|null $resultUnit   The unit for the result (optional)
     * @param int                $scale        Number of decimal places (default: 10)
     * @param RoundingMode       $roundingMode Rounding mode (default: HalfUp)
     *
     * @throws InvalidOperationException If no unit is registered for the result formula
     */
    public function divide(
        QuantityInterface $other,
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface;

    /**
     * Get the absolute value of this quantity.
     *
     * Returns a new quantity with the absolute value, same unit.
     */
    public function abs(): QuantityInterface;

    /**
     * Negate this quantity.
     *
     * Returns a new quantity with the negated value, same unit.
     */
    public function negate(): QuantityInterface;

    /**
     * Round this quantity's value to specified precision.
     *
     * @param int                   $precision Number of decimal places (default: 0)
     * @param RoundingModeInterface $mode      Rounding mode (default: HalfUp)
     */
    public function round(int $precision = 0, RoundingModeInterface $mode = RoundingMode::HalfUp): QuantityInterface;

    /**
     * Round this quantity's value down (towards negative infinity).
     *
     * @param int $precision Number of decimal places (default: 0)
     */
    public function floor(int $precision = 0): QuantityInterface;

    /**
     * Round this quantity's value up (towards positive infinity).
     *
     * @param int $precision Number of decimal places (default: 0)
     */
    public function ceil(int $precision = 0): QuantityInterface;

    /**
     * Raise this quantity to an integer power.
     *
     * Creates a derived quantity with a new dimension.
     * For example: Length² = Area, Length³ = Volume.
     *
     * @param int                $exponent     The integer exponent
     * @param UnitInterface|null $resultUnit   The unit for the result (optional)
     * @param int                $scale        Number of decimal places (default: 10)
     * @param RoundingMode       $roundingMode Rounding mode (default: HalfUp)
     *
     * @throws InvalidOperationException If no unit is registered for the result formula
     */
    public function power(
        int $exponent,
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface;

    /**
     * Calculate the square root of this quantity.
     *
     * Creates a derived quantity with halved dimension exponents.
     * For example: √Area = Length.
     *
     * @param UnitInterface|null $resultUnit   The unit for the result (optional)
     * @param int                $scale        Number of decimal places (default: 10)
     * @param RoundingMode       $roundingMode Rounding mode (default: HalfUp)
     *
     * @throws InvalidOperationException If dimension exponents are not all even
     * @throws InvalidOperationException If no unit is registered for the result formula
     */
    public function sqrt(
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface;

    /**
     * Calculate the ratio between this quantity and another.
     *
     * Returns a dimensionless number representing how many times larger
     * this quantity is compared to the other.
     *
     * @param QuantityInterface $other        The divisor quantity
     * @param int               $scale        Number of decimal places (default: 10)
     * @param RoundingMode      $roundingMode Rounding mode (default: HalfUp)
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function ratio(
        QuantityInterface $other,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): NumberInterface;
}
