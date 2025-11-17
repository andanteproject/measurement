<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Math\RoundingMode;

/**
 * Interface for quantity arithmetic operations.
 */
interface CalculatorInterface
{
    /**
     * Add two quantities of the same dimension.
     *
     * The result is in the unit of the first operand.
     */
    public function add(QuantityInterface $a, QuantityInterface $b): QuantityInterface;

    /**
     * Subtract second quantity from first.
     *
     * The result is in the unit of the first operand.
     */
    public function subtract(QuantityInterface $a, QuantityInterface $b): QuantityInterface;

    /**
     * Multiply a quantity by a scalar value.
     *
     * The result is in the same unit as the input quantity.
     */
    public function multiplyByScalar(QuantityInterface $quantity, NumberInterface $scalar): QuantityInterface;

    /**
     * Divide a quantity by a scalar value.
     *
     * The result is in the same unit as the input quantity.
     */
    public function divideByScalar(
        QuantityInterface $quantity,
        NumberInterface $scalar,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface;

    /**
     * Multiply two quantities, creating a derived quantity.
     *
     * The result dimension is the product of the two input dimensions.
     */
    public function multiply(
        QuantityInterface $a,
        QuantityInterface $b,
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface;

    /**
     * Divide two quantities, creating a derived quantity.
     *
     * The result dimension is the quotient of the two input dimensions.
     */
    public function divide(
        QuantityInterface $a,
        QuantityInterface $b,
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface;

    /**
     * Calculate the sum of multiple quantities.
     *
     * The result is in the unit of the first quantity.
     */
    public function sum(QuantityInterface $first, QuantityInterface ...$quantities): QuantityInterface;

    /**
     * Calculate the average of multiple quantities.
     *
     * The result is in the unit of the first quantity.
     */
    public function average(QuantityInterface $first, QuantityInterface ...$quantities): QuantityInterface;

    /**
     * Get the absolute value of a quantity.
     */
    public function abs(QuantityInterface $quantity): QuantityInterface;

    /**
     * Negate a quantity.
     */
    public function negate(QuantityInterface $quantity): QuantityInterface;

    /**
     * Round a quantity's value to specified precision.
     */
    public function round(
        QuantityInterface $quantity,
        int $precision = 0,
        RoundingModeInterface $mode = RoundingMode::HalfUp,
    ): QuantityInterface;

    /**
     * Round a quantity's value down (towards negative infinity).
     */
    public function floor(QuantityInterface $quantity, int $precision = 0): QuantityInterface;

    /**
     * Round a quantity's value up (towards positive infinity).
     */
    public function ceil(QuantityInterface $quantity, int $precision = 0): QuantityInterface;

    /**
     * Raise a quantity to an integer power.
     *
     * The dimensional formula is raised to the same power.
     */
    public function power(
        QuantityInterface $quantity,
        int $exponent,
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface;

    /**
     * Calculate the square root of a quantity.
     *
     * The dimensional formula exponents are halved.
     */
    public function sqrt(
        QuantityInterface $quantity,
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface;

    /**
     * Calculate the ratio between two quantities of the same dimension.
     *
     * Returns a dimensionless number.
     */
    public function ratio(
        QuantityInterface $a,
        QuantityInterface $b,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): NumberInterface;
}
