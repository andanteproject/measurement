<?php

declare(strict_types=1);

namespace Andante\Measurement\Converter;

use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Math\RoundingMode;

/**
 * Represents a conversion rule for transforming values to/from a base unit.
 *
 * Most unit conversions are simple multiplication:
 *   base_value = value × factor
 *
 * However, some units (like temperature) require affine transformations:
 *   base_value = value × factor + offset
 *
 * Examples:
 * - Kilometer to Meter: factor=1000, offset=0 (base = km × 1000)
 * - Celsius to Kelvin: factor=1, offset=273.15 (K = °C × 1 + 273.15)
 * - Fahrenheit to Kelvin: factor=5/9, offset=2298.35/9 (K = °F × 5/9 + 255.372...)
 *
 * The inverse transformation (from base to unit) is:
 *   value = (base_value - offset) / factor
 */
final class ConversionRule
{
    private function __construct(
        private readonly NumberInterface $factor,
        private readonly NumberInterface $offset,
    ) {
    }

    /**
     * Create a conversion rule with a factor and optional offset.
     *
     * base_value = value × factor + offset
     *
     * For simple multiplicative conversions (most units), omit the offset:
     *   ConversionRule::factor(NumberFactory::create('1000'))
     *
     * For affine conversions (like temperature), provide both:
     *   ConversionRule::factor(NumberFactory::create('1'), NumberFactory::create('273.15'))
     *
     * @param NumberInterface      $factor The multiplication factor
     * @param NumberInterface|null $offset The offset to add (defaults to 0)
     */
    public static function factor(NumberInterface $factor, ?NumberInterface $offset = null): self
    {
        return new self($factor, $offset ?? NumberFactory::zero());
    }

    /**
     * Get the multiplication factor.
     */
    public function getFactor(): NumberInterface
    {
        return $this->factor;
    }

    /**
     * Get the offset (0 for simple multiplicative conversions).
     */
    public function getOffset(): NumberInterface
    {
        return $this->offset;
    }

    /**
     * Check if this is a simple multiplicative conversion (no offset).
     */
    public function isMultiplicative(): bool
    {
        return $this->offset->isZero();
    }

    /**
     * Convert a value to the base unit.
     *
     * base_value = value × factor + offset
     */
    public function toBase(NumberInterface $value): NumberInterface
    {
        $result = $value->multiply($this->factor);

        if (!$this->offset->isZero()) {
            $result = $result->add($this->offset);
        }

        return $result;
    }

    /**
     * Convert a value from the base unit.
     *
     * value = (base_value - offset) / factor
     */
    public function fromBase(
        NumberInterface $baseValue,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): NumberInterface {
        $result = $baseValue;

        if (!$this->offset->isZero()) {
            $result = $result->subtract($this->offset);
        }

        return $result->divide($this->factor, $scale, $roundingMode);
    }
}
