<?php

declare(strict_types=1);

namespace Andante\Measurement\Math\Adapter;

use Andante\Measurement\Contract\Math\MathAdapterInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Exception\InvalidOperationException;
use Andante\Measurement\Math\Number;
use Andante\Measurement\Math\RoundingMode;

/**
 * Math adapter implementation using PHP's bcmath extension.
 *
 * Provides arbitrary precision arithmetic using the bcmath extension.
 * This is a good alternative to brick/math for users who prefer using
 * PHP extensions over Composer dependencies.
 *
 * Requires: ext-bcmath
 */
final class BCMathAdapter implements MathAdapterInterface
{
    public function add(NumberInterface $a, NumberInterface $b): NumberInterface
    {
        $result = \bcadd($this->toNumericString($a), $this->toNumericString($b), $this->getMaxScale($a, $b));

        return new Number($result, $this);
    }

    public function subtract(NumberInterface $a, NumberInterface $b): NumberInterface
    {
        $result = \bcsub($this->toNumericString($a), $this->toNumericString($b), $this->getMaxScale($a, $b));

        return new Number($result, $this);
    }

    public function multiply(NumberInterface $a, NumberInterface $b): NumberInterface
    {
        // For multiplication, we need to add the scales to preserve precision
        $scale = $this->getScale($a) + $this->getScale($b);
        $result = \bcmul($this->toNumericString($a), $this->toNumericString($b), $scale);

        return new Number($result, $this);
    }

    public function divide(
        NumberInterface $a,
        NumberInterface $b,
        int $scale,
        RoundingModeInterface $roundingMode,
    ): NumberInterface {
        if ($this->isZero($b)) {
            throw new InvalidOperationException('Division by zero');
        }

        // bcmath doesn't support rounding modes directly, so we need to handle it ourselves
        $result = \bcdiv($a->value(), $b->value(), $scale + 1);

        // Apply rounding mode
        $rounded = $this->applyRounding($result, $scale, $roundingMode);

        return new Number($rounded, $this);
    }

    public function power(NumberInterface $base, NumberInterface $exponent): NumberInterface
    {
        $baseStr = $this->toNumericString($base);
        $expStr = $this->toNumericString($exponent);

        // Check if exponent is an integer
        if (0 === \bccomp($expStr, (string) (int) $expStr, 0)) {
            $expInt = (int) $expStr;

            if (0 <= $expInt) {
                // Positive integer exponent - use bcpow
                $result = \bcpow($baseStr, $expStr, \max($this->getScale($base), 10));

                return new Number($result, $this);
            }

            // Negative integer exponent: a^(-n) = 1 / a^n
            $positiveResult = \bcpow($baseStr, (string) (-$expInt), \max($this->getScale($base), 10));
            $result = \bcdiv('1', $positiveResult, 20);

            return new Number($result, $this);
        }

        // Fractional exponent - fall back to floating-point
        // This loses some precision but is necessary for fractional powers
        $result = \pow((float) $baseStr, (float) $expStr);

        if (!\is_finite($result)) {
            throw new InvalidOperationException(\sprintf('Invalid power operation: %s ^ %s', $baseStr, $expStr));
        }

        return new Number((string) $result, $this);
    }

    public function sqrt(NumberInterface $value, int $scale): NumberInterface
    {
        $valueStr = $this->toNumericString($value);

        if (0 > \bccomp($valueStr, '0', 0)) {
            throw new InvalidOperationException('Cannot calculate square root of negative number');
        }

        if (0 === \bccomp($valueStr, '0', 0)) {
            return new Number('0', $this);
        }

        // bcmath doesn't have a sqrt function, so we use bcsqrt if available (PHP 8.0+)
        // or implement Newton's method for older versions
        if (\function_exists('bcsqrt')) {
            $result = \bcsqrt($valueStr, $scale);

            return new Number($result, $this);
        }

        // Newton's method: x[n+1] = (x[n] + value/x[n]) / 2
        $x = $valueStr;
        $precision = $scale + 2;

        for ($i = 0; 100 > $i; ++$i) {
            $xNext = \bcdiv(
                \bcadd($x, \bcdiv($valueStr, $x, $precision), $precision),
                '2',
                $precision,
            );

            // Check convergence
            if (0 === \bccomp($x, $xNext, $scale)) {
                break;
            }

            $x = $xNext;
        }

        // Round to requested scale
        $result = \bcadd($x, '0', $scale);

        return new Number($result, $this);
    }

    public function abs(NumberInterface $value): NumberInterface
    {
        $valueStr = $this->toNumericString($value);
        $scale = $this->getScale($value);

        if (0 > \bccomp($valueStr, '0', $scale)) {
            return new Number(\bcmul($valueStr, '-1', $scale), $this);
        }

        return new Number($valueStr, $this);
    }

    public function negate(NumberInterface $value): NumberInterface
    {
        $result = \bcmul($this->toNumericString($value), '-1', $this->getScale($value));

        return new Number($result, $this);
    }

    public function compare(NumberInterface $a, NumberInterface $b): int
    {
        return \bccomp($this->toNumericString($a), $this->toNumericString($b), \max($this->getScale($a), $this->getScale($b)));
    }

    public function equals(NumberInterface $a, NumberInterface $b, ?NumberInterface $tolerance = null): bool
    {
        if (null === $tolerance) {
            return 0 === \bccomp($this->toNumericString($a), $this->toNumericString($b), \max($this->getScale($a), $this->getScale($b)));
        }

        $diff = $this->abs($this->subtract($a, $b));
        // Use max scale between diff and tolerance for accurate comparison
        $scale = \max($this->getScale($diff), $this->getScale($tolerance));

        return 0 >= \bccomp($this->toNumericString($diff), $this->toNumericString($tolerance), $scale);
    }

    public function round(
        NumberInterface $value,
        int $precision,
        RoundingModeInterface $mode,
    ): NumberInterface {
        $valueStr = $this->toNumericString($value);
        $rounded = $this->applyRounding($valueStr, $precision, $mode);

        return new Number($rounded, $this);
    }

    public function isZero(NumberInterface $value): bool
    {
        return 0 === \bccomp($this->toNumericString($value), '0', $this->getScale($value));
    }

    public function isNegative(NumberInterface $value): bool
    {
        return 0 > \bccomp($this->toNumericString($value), '0', 0);
    }

    public function isPositive(NumberInterface $value): bool
    {
        return 0 < \bccomp($this->toNumericString($value), '0', 0);
    }

    public function min(NumberInterface $a, NumberInterface $b): NumberInterface
    {
        return 0 >= \bccomp($this->toNumericString($a), $this->toNumericString($b), \max($this->getScale($a), $this->getScale($b))) ? $a : $b;
    }

    public function max(NumberInterface $a, NumberInterface $b): NumberInterface
    {
        return 0 <= \bccomp($this->toNumericString($a), $this->toNumericString($b), \max($this->getScale($a), $this->getScale($b))) ? $a : $b;
    }

    /**
     * Get the scale (decimal places) from a numeric string.
     */
    private function getScale(NumberInterface $value): int
    {
        $valueStr = $value->value();
        $pos = \strpos($valueStr, '.');

        if (false === $pos) {
            return 0;
        }

        return \strlen($valueStr) - $pos - 1;
    }

    /**
     * Get the maximum scale between two values.
     */
    private function getMaxScale(NumberInterface $a, NumberInterface $b): int
    {
        return \max($this->getScale($a), $this->getScale($b));
    }

    /**
     * Apply rounding mode to a value.
     *
     * @param numeric-string $value
     *
     * @return numeric-string
     */
    private function applyRounding(string $value, int $precision, RoundingModeInterface $mode): string
    {
        // If value already has the desired precision or less, just return it
        $currentScale = $this->getScaleFromString($value);
        if ($currentScale <= $precision) {
            return \bcadd($value, '0', $precision);
        }

        // Get the digit at precision + 1 position
        $parts = \explode('.', $value);
        $intPart = $parts[0] ?? '0';
        $decPart = $parts[1] ?? '';

        if (\strlen($decPart) <= $precision) {
            return \bcadd($value, '0', $precision);
        }

        $isNegative = 0 > \bccomp($value, '0', 0);
        $absValue = $isNegative ? \bcmul($value, '-1', $currentScale) : $value;

        // Truncate to precision
        $truncated = \bcadd($absValue, '0', $precision);

        // Determine if we need to round up
        $nextDigit = (int) $decPart[$precision];
        $shouldRoundUp = $this->shouldRoundUp($mode, $truncated, $nextDigit, $isNegative);

        if ($shouldRoundUp) {
            $increment = \bcpow('10', (string) -$precision, $precision);
            $rounded = \bcadd($truncated, $increment, $precision);
        } else {
            $rounded = $truncated;
        }

        return $isNegative ? \bcmul($rounded, '-1', $precision) : $rounded;
    }

    /**
     * Determine if value should round up based on rounding mode.
     */
    private function shouldRoundUp(RoundingModeInterface $mode, string $truncated, int $nextDigit, bool $isNegative): bool
    {
        return match ($mode->value()) {
            RoundingMode::Up->value() => 0 < $nextDigit,
            RoundingMode::Down->value() => false,
            RoundingMode::Ceiling->value() => !$isNegative && 0 < $nextDigit,
            RoundingMode::Floor->value() => $isNegative && 0 < $nextDigit,
            RoundingMode::HalfUp->value() => 5 <= $nextDigit,
            RoundingMode::HalfDown->value() => 5 < $nextDigit,
            RoundingMode::HalfEven->value() => 5 < $nextDigit || (5 === $nextDigit && $this->isOdd($truncated)),
            RoundingMode::HalfOdd->value() => 5 < $nextDigit || (5 === $nextDigit && !$this->isOdd($truncated)),
            default => 5 <= $nextDigit, // Default to HalfUp behavior
        };
    }

    /**
     * Check if the last digit of a value is odd.
     */
    private function isOdd(string $value): bool
    {
        $parts = \explode('.', $value);
        $decPart = $parts[1] ?? '';

        if ('' === $decPart) {
            $lastDigit = (int) \substr($parts[0], -1);
        } else {
            $lastDigit = (int) \substr($decPart, -1);
        }

        return 1 === $lastDigit % 2;
    }

    /**
     * Get scale from a string value.
     *
     * @param numeric-string $value
     */
    private function getScaleFromString(string $value): int
    {
        $pos = \strpos($value, '.');

        if (false === $pos) {
            return 0;
        }

        return \strlen($value) - $pos - 1;
    }

    /**
     * Get numeric string from NumberInterface.
     *
     * @return numeric-string
     */
    private function toNumericString(NumberInterface $value): string
    {
        $str = $value->value();
        \assert(\is_numeric($str));

        /* @var numeric-string */
        return $str;
    }
}
