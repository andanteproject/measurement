<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Trait;

use Andante\Measurement\Calculator\Calculator;
use Andante\Measurement\Contract\CalculatorInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Math\RoundingMode;

/**
 * Trait that implements CalculableInterface using the global Calculator service.
 *
 * This trait expects the class to implement QuantityInterface.
 *
 * @phpstan-require-implements QuantityInterface
 */
trait CalculableTrait
{
    private static ?CalculatorInterface $calculator = null;

    /**
     * Get the Calculator instance to use.
     */
    private static function getCalculator(): CalculatorInterface
    {
        return self::$calculator ?? Calculator::global();
    }

    /**
     * Set a custom Calculator instance.
     *
     * Useful for testing or custom configurations.
     */
    public static function setCalculator(?CalculatorInterface $calculator): void
    {
        self::$calculator = $calculator;
    }

    /**
     * Reset the Calculator to use the default global instance.
     */
    public static function resetCalculator(): void
    {
        self::$calculator = null;
    }

    /**
     * @see CalculableInterface::add()
     */
    public function add(QuantityInterface $other): QuantityInterface
    {
        return self::getCalculator()->add($this, $other);
    }

    /**
     * @see CalculableInterface::subtract()
     */
    public function subtract(QuantityInterface $other): QuantityInterface
    {
        return self::getCalculator()->subtract($this, $other);
    }

    /**
     * @see CalculableInterface::multiplyBy()
     */
    public function multiplyBy(NumberInterface $scalar): QuantityInterface
    {
        return self::getCalculator()->multiplyByScalar($this, $scalar);
    }

    /**
     * @see CalculableInterface::divideBy()
     */
    public function divideBy(
        NumberInterface $scalar,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface {
        return self::getCalculator()->divideByScalar($this, $scalar, $scale, $roundingMode);
    }

    /**
     * @see CalculableInterface::multiply()
     */
    public function multiply(
        QuantityInterface $other,
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface {
        return self::getCalculator()->multiply($this, $other, $resultUnit, $scale, $roundingMode);
    }

    /**
     * @see CalculableInterface::divide()
     */
    public function divide(
        QuantityInterface $other,
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface {
        return self::getCalculator()->divide($this, $other, $resultUnit, $scale, $roundingMode);
    }

    /**
     * @see CalculableInterface::abs()
     */
    public function abs(): QuantityInterface
    {
        return self::getCalculator()->abs($this);
    }

    /**
     * @see CalculableInterface::negate()
     */
    public function negate(): QuantityInterface
    {
        return self::getCalculator()->negate($this);
    }

    /**
     * @see CalculableInterface::round()
     */
    public function round(int $precision = 0, RoundingModeInterface $mode = RoundingMode::HalfUp): QuantityInterface
    {
        return self::getCalculator()->round($this, $precision, $mode);
    }

    /**
     * @see CalculableInterface::floor()
     */
    public function floor(int $precision = 0): QuantityInterface
    {
        return self::getCalculator()->floor($this, $precision);
    }

    /**
     * @see CalculableInterface::ceil()
     */
    public function ceil(int $precision = 0): QuantityInterface
    {
        return self::getCalculator()->ceil($this, $precision);
    }

    /**
     * @see CalculableInterface::power()
     */
    public function power(
        int $exponent,
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface {
        return self::getCalculator()->power($this, $exponent, $resultUnit, $scale, $roundingMode);
    }

    /**
     * @see CalculableInterface::sqrt()
     */
    public function sqrt(
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface {
        return self::getCalculator()->sqrt($this, $resultUnit, $scale, $roundingMode);
    }

    /**
     * @see CalculableInterface::ratio()
     */
    public function ratio(
        QuantityInterface $other,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): NumberInterface {
        return self::getCalculator()->ratio($this, $other, $scale, $roundingMode);
    }
}
