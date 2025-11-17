<?php

declare(strict_types=1);

namespace Andante\Measurement\Math\Adapter;

use Andante\Measurement\Contract\Math\MathAdapterInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Exception\InvalidOperationException;
use Andante\Measurement\Math\Number;
use Andante\Measurement\Math\RoundingMode;
use Brick\Math\BigDecimal;
use Brick\Math\Exception\DivisionByZeroException;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NegativeNumberException;
use Brick\Math\RoundingMode as BrickRoundingMode;

/**
 * Math adapter implementation using brick/math library.
 *
 * Provides arbitrary precision arithmetic using the brick/math library.
 * This is the default and recommended adapter for production use.
 *
 * Works efficiently with NumericValue objects that wrap BigDecimal internally.
 */
final class BrickMathAdapter implements MathAdapterInterface
{
    public function add(NumberInterface $a, NumberInterface $b): NumberInterface
    {
        $result = $this->getDecimal($a)->plus($this->getDecimal($b));

        return new Number($result, $this);
    }

    public function subtract(NumberInterface $a, NumberInterface $b): NumberInterface
    {
        $result = $this->getDecimal($a)->minus($this->getDecimal($b));

        return new Number($result, $this);
    }

    public function multiply(NumberInterface $a, NumberInterface $b): NumberInterface
    {
        $result = $this->getDecimal($a)->multipliedBy($this->getDecimal($b));

        return new Number($result, $this);
    }

    public function divide(
        NumberInterface $a,
        NumberInterface $b,
        int $scale,
        RoundingModeInterface $roundingMode,
    ): NumberInterface {
        try {
            $result = $this->getDecimal($a)
                ->dividedBy($this->getDecimal($b), $scale, BrickMathAdapter::toBrickMathRoundingMode($roundingMode));

            return new Number($result, $this);
        } catch (DivisionByZeroException $e) {
            throw new InvalidOperationException('Division by zero', 0, $e);
        }
    }

    public function power(NumberInterface $base, NumberInterface $exponent): NumberInterface
    {
        try {
            $baseDecimal = $this->getDecimal($base);
            $exponentDecimal = $this->getDecimal($exponent);

            // Handle integer exponents (brick/math only supports positive integers)
            if (0 === $exponentDecimal->getScale()) {
                $expInt = (int) $exponentDecimal->__toString();

                if (0 <= $expInt) {
                    // Positive integer exponent - use brick/math directly
                    $result = $baseDecimal->power($expInt);

                    return new Number($result, $this);
                }

                // Negative integer exponent: a^(-n) = 1 / a^n
                $positiveResult = $baseDecimal->power(-$expInt);
                $result = BigDecimal::one()->dividedBy($positiveResult, 20, BrickRoundingMode::HALF_UP);

                return new Number($result, $this);
            }

            // For fractional exponents, we need to use floating-point
            // This loses some precision but is necessary for fractional powers
            $result = \pow(
                (float) $baseDecimal->__toString(),
                (float) $exponentDecimal->__toString(),
            );

            return new Number((string) $result, $this);
        } catch (MathException $e) {
            throw new InvalidOperationException(\sprintf('Invalid power operation: %s ^ %s', $base->value(), $exponent->value()), 0, $e);
        }
    }

    public function sqrt(NumberInterface $value, int $scale): NumberInterface
    {
        try {
            $result = $this->getDecimal($value)->sqrt($scale);

            return new Number($result, $this);
        } catch (NegativeNumberException $e) {
            throw new InvalidOperationException('Cannot calculate square root of negative number', 0, $e);
        } catch (MathException $e) {
            throw new InvalidOperationException('Invalid square root operation', 0, $e);
        }
    }

    public function abs(NumberInterface $value): NumberInterface
    {
        $result = $this->getDecimal($value)->abs();

        return new Number($result, $this);
    }

    public function negate(NumberInterface $value): NumberInterface
    {
        $result = $this->getDecimal($value)->negated();

        return new Number($result, $this);
    }

    public function compare(NumberInterface $a, NumberInterface $b): int
    {
        return $this->getDecimal($a)->compareTo($this->getDecimal($b));
    }

    public function equals(NumberInterface $a, NumberInterface $b, ?NumberInterface $tolerance = null): bool
    {
        $aDecimal = $this->getDecimal($a);
        $bDecimal = $this->getDecimal($b);

        if (null === $tolerance) {
            return $aDecimal->isEqualTo($bDecimal);
        }

        $diff = $aDecimal->minus($bDecimal)->abs();

        return $diff->isLessThanOrEqualTo($this->getDecimal($tolerance));
    }

    public function round(
        NumberInterface $value,
        int $precision,
        RoundingModeInterface $mode,
    ): NumberInterface {
        $result = $this->getDecimal($value)->toScale($precision, BrickMathAdapter::toBrickMathRoundingMode($mode));

        return new Number($result, $this);
    }

    public function isZero(NumberInterface $value): bool
    {
        return $this->getDecimal($value)->isZero();
    }

    public function isNegative(NumberInterface $value): bool
    {
        return $this->getDecimal($value)->isNegative();
    }

    public function isPositive(NumberInterface $value): bool
    {
        return $this->getDecimal($value)->isPositive();
    }

    public function min(NumberInterface $a, NumberInterface $b): NumberInterface
    {
        $aDecimal = $this->getDecimal($a);
        $bDecimal = $this->getDecimal($b);
        $result = $aDecimal->isLessThanOrEqualTo($bDecimal) ? $aDecimal : $bDecimal;

        return new Number($result, $this);
    }

    public function max(NumberInterface $a, NumberInterface $b): NumberInterface
    {
        $aDecimal = $this->getDecimal($a);
        $bDecimal = $this->getDecimal($b);
        $result = $aDecimal->isGreaterThanOrEqualTo($bDecimal) ? $aDecimal : $bDecimal;

        return new Number($result, $this);
    }

    /**
     * Extract BigDecimal from NumericValue efficiently.
     *
     * If the value is our NumericValue class, we can access the internal
     * BigDecimal directly. Otherwise, fall back to string conversion.
     */
    private function getDecimal(NumberInterface $value): BigDecimal
    {
        if ($value instanceof Number) {
            return $value->getDecimal();
        }

        // Fallback for custom implementations
        return BigDecimal::of($value->value());
    }

    /**
     * Convert RoundingModeInterface to brick/math RoundingMode constant.
     */
    private static function toBrickMathRoundingMode(RoundingModeInterface $mode): int
    {
        return match ($mode->value()) {
            RoundingMode::Ceiling->value() => BrickRoundingMode::CEILING,
            RoundingMode::Floor->value() => BrickRoundingMode::FLOOR,
            RoundingMode::Down->value() => BrickRoundingMode::DOWN,
            RoundingMode::Up->value() => BrickRoundingMode::UP,
            RoundingMode::HalfUp->value() => BrickRoundingMode::HALF_UP,
            RoundingMode::HalfDown->value() => BrickRoundingMode::HALF_DOWN,
            RoundingMode::HalfEven->value() => BrickRoundingMode::HALF_EVEN,
            RoundingMode::HalfOdd->value() => BrickRoundingMode::HALF_CEILING,
            default => BrickRoundingMode::HALF_UP,
        };
    }
}
