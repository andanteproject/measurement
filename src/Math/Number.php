<?php

declare(strict_types=1);

namespace Andante\Measurement\Math;

use Andante\Measurement\Contract\Math\MathAdapterInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Brick\Math\BigDecimal;

/**
 * Immutable arbitrary-precision numeric value.
 *
 * This class decorates brick/math's BigDecimal, providing a consistent
 * interface for numeric operations while delegating to the math adapter.
 */
final class Number implements NumberInterface
{
    private readonly BigDecimal $decimal;

    public function __construct(
        string|int|float|BigDecimal $value,
        private readonly MathAdapterInterface $math,
    ) {
        $this->decimal = $value instanceof BigDecimal ? $value : BigDecimal::of((string) $value);
    }

    /**
     * Get the internal BigDecimal instance.
     *
     * @internal Used by BrickMathAdapter for efficient operations
     */
    public function getDecimal(): BigDecimal
    {
        return $this->decimal;
    }

    public function value(): string
    {
        return $this->decimal->__toString();
    }

    public function __toString(): string
    {
        return $this->decimal->__toString();
    }

    public function add(NumberInterface $other): NumberInterface
    {
        return $this->math->add($this, $other);
    }

    public function subtract(NumberInterface $other): NumberInterface
    {
        return $this->math->subtract($this, $other);
    }

    public function multiply(NumberInterface $other): NumberInterface
    {
        return $this->math->multiply($this, $other);
    }

    public function divide(
        NumberInterface $other,
        int $scale,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): NumberInterface {
        return $this->math->divide($this, $other, $scale, $roundingMode);
    }

    public function power(NumberInterface $exponent): NumberInterface
    {
        return $this->math->power($this, $exponent);
    }

    public function sqrt(int $scale): NumberInterface
    {
        return $this->math->sqrt($this, $scale);
    }

    public function abs(): NumberInterface
    {
        return $this->math->abs($this);
    }

    public function negate(): NumberInterface
    {
        return $this->math->negate($this);
    }

    public function compareTo(NumberInterface $other): int
    {
        return $this->math->compare($this, $other);
    }

    public function equals(NumberInterface $other, ?NumberInterface $tolerance = null): bool
    {
        return $this->math->equals($this, $other, $tolerance);
    }

    public function isZero(): bool
    {
        return $this->math->isZero($this);
    }

    public function isPositive(): bool
    {
        return $this->math->isPositive($this);
    }

    public function isNegative(): bool
    {
        return $this->math->isNegative($this);
    }

    public function round(int $precision, RoundingModeInterface $mode = RoundingMode::HalfUp): NumberInterface
    {
        return $this->math->round($this, $precision, $mode);
    }

    public function min(NumberInterface $other): NumberInterface
    {
        return $this->math->min($this, $other);
    }

    public function max(NumberInterface $other): NumberInterface
    {
        return $this->math->max($this, $other);
    }
}
