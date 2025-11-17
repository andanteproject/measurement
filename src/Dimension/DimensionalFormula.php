<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Exception\InvalidArgumentException;

/**
 * Represents a dimensional formula in terms of the seven base SI dimensions
 * plus a pseudo-dimension for digital information.
 *
 * The seven base SI dimensions are:
 * - L: Length
 * - M: Mass
 * - T: Time
 * - I: Electric Current
 * - Θ: Thermodynamic Temperature
 * - N: Amount of Substance
 * - J: Luminous Intensity
 *
 * Plus one pseudo-dimension for non-SI quantities:
 * - D: Digital (for bits/bytes - not a true SI dimension)
 *
 * Each dimension is expressed as an exponent of these base dimensions.
 * For example:
 * - Length: [L¹M⁰T⁰I⁰Θ⁰N⁰J⁰D⁰] = [L¹]
 * - Area: [L²M⁰T⁰I⁰Θ⁰N⁰J⁰D⁰] = [L²]
 * - Velocity: [L¹M⁰T⁻¹I⁰Θ⁰N⁰J⁰D⁰] = [L¹T⁻¹]
 * - Energy: [L²M¹T⁻²I⁰Θ⁰N⁰J⁰D⁰] = [L²M¹T⁻²]
 * - DigitalInformation: [D¹] = [D¹]
 * - DataTransferRate: [D¹T⁻¹] = [D¹T⁻¹]
 *
 * This class is immutable.
 */
final class DimensionalFormula implements \Stringable
{
    /**
     * @param int $length            Length exponent (L)
     * @param int $mass              Mass exponent (M)
     * @param int $time              Time exponent (T)
     * @param int $electricCurrent   Electric Current exponent (I)
     * @param int $temperature       Temperature exponent (Θ)
     * @param int $amountOfSubstance Amount of Substance exponent (N)
     * @param int $luminousIntensity Luminous Intensity exponent (J)
     * @param int $digital           Digital pseudo-dimension exponent (D) - for bits/bytes
     */
    public function __construct(
        public readonly int $length = 0,
        public readonly int $mass = 0,
        public readonly int $time = 0,
        public readonly int $electricCurrent = 0,
        public readonly int $temperature = 0,
        public readonly int $amountOfSubstance = 0,
        public readonly int $luminousIntensity = 0,
        public readonly int $digital = 0,
    ) {
    }

    /**
     * Create a dimensionless formula (all exponents are zero).
     */
    public static function dimensionless(): self
    {
        return new self();
    }

    /**
     * Create a length formula [L¹].
     */
    public static function length(): self
    {
        return new self(length: 1);
    }

    /**
     * Create a mass formula [M¹].
     */
    public static function mass(): self
    {
        return new self(mass: 1);
    }

    /**
     * Create a time formula [T¹].
     */
    public static function time(): self
    {
        return new self(time: 1);
    }

    /**
     * Create an electric current formula [I¹].
     */
    public static function electricCurrent(): self
    {
        return new self(electricCurrent: 1);
    }

    /**
     * Create a temperature formula [Θ¹].
     */
    public static function temperature(): self
    {
        return new self(temperature: 1);
    }

    /**
     * Create an amount of substance formula [N¹].
     */
    public static function amountOfSubstance(): self
    {
        return new self(amountOfSubstance: 1);
    }

    /**
     * Create a luminous intensity formula [J¹].
     */
    public static function luminousIntensity(): self
    {
        return new self(luminousIntensity: 1);
    }

    /**
     * Create a digital information formula [D¹].
     *
     * This is a pseudo-dimension for non-SI quantities like bits and bytes.
     */
    public static function digital(): self
    {
        return new self(digital: 1);
    }

    /**
     * Multiply two dimensional formulas (add exponents).
     *
     * Used when multiplying quantities: Length × Length = Area
     */
    public function multiply(self $other): self
    {
        return new self(
            length: $this->length + $other->length,
            mass: $this->mass + $other->mass,
            time: $this->time + $other->time,
            electricCurrent: $this->electricCurrent + $other->electricCurrent,
            temperature: $this->temperature + $other->temperature,
            amountOfSubstance: $this->amountOfSubstance + $other->amountOfSubstance,
            luminousIntensity: $this->luminousIntensity + $other->luminousIntensity,
            digital: $this->digital + $other->digital,
        );
    }

    /**
     * Divide two dimensional formulas (subtract exponents).
     *
     * Used when dividing quantities: Energy / Time = Power
     */
    public function divide(self $other): self
    {
        return new self(
            length: $this->length - $other->length,
            mass: $this->mass - $other->mass,
            time: $this->time - $other->time,
            electricCurrent: $this->electricCurrent - $other->electricCurrent,
            temperature: $this->temperature - $other->temperature,
            amountOfSubstance: $this->amountOfSubstance - $other->amountOfSubstance,
            luminousIntensity: $this->luminousIntensity - $other->luminousIntensity,
            digital: $this->digital - $other->digital,
        );
    }

    /**
     * Raise this dimensional formula to a power (multiply all exponents).
     *
     * Used for power operations: Length² = Area, Length³ = Volume
     */
    public function power(int $exponent): self
    {
        return new self(
            length: $this->length * $exponent,
            mass: $this->mass * $exponent,
            time: $this->time * $exponent,
            electricCurrent: $this->electricCurrent * $exponent,
            temperature: $this->temperature * $exponent,
            amountOfSubstance: $this->amountOfSubstance * $exponent,
            luminousIntensity: $this->luminousIntensity * $exponent,
            digital: $this->digital * $exponent,
        );
    }

    /**
     * Take the nth root of this dimensional formula (divide all exponents).
     *
     * Used for root operations: √Area = Length, ∛Volume = Length
     *
     * All exponents must be evenly divisible by n, otherwise the result
     * would have fractional exponents which are not valid.
     *
     * @param int $n The root to take (2 for square root, 3 for cube root, etc.)
     *
     * @throws InvalidArgumentException If any exponent is not evenly divisible by n
     * @throws InvalidArgumentException If n is zero
     */
    public function root(int $n): self
    {
        if (0 === $n) {
            throw new InvalidArgumentException('Cannot take zeroth root of a dimensional formula');
        }

        // Validate all exponents are evenly divisible
        $exponents = $this->toArray();
        foreach ($exponents as $name => $value) {
            if (0 !== $value % $n) {
                throw new InvalidArgumentException(\sprintf('Cannot take %s root: %s exponent %d is not divisible by %d', 2 === $n ? 'square' : (3 === $n ? 'cube' : "{$n}th"), $name, $value, $n));
            }
        }

        return new self(
            length: \intdiv($this->length, $n),
            mass: \intdiv($this->mass, $n),
            time: \intdiv($this->time, $n),
            electricCurrent: \intdiv($this->electricCurrent, $n),
            temperature: \intdiv($this->temperature, $n),
            amountOfSubstance: \intdiv($this->amountOfSubstance, $n),
            luminousIntensity: \intdiv($this->luminousIntensity, $n),
            digital: \intdiv($this->digital, $n),
        );
    }

    /**
     * Check if this formula equals another.
     */
    public function equals(self $other): bool
    {
        return $this->length === $other->length
            && $this->mass === $other->mass
            && $this->time === $other->time
            && $this->electricCurrent === $other->electricCurrent
            && $this->temperature === $other->temperature
            && $this->amountOfSubstance === $other->amountOfSubstance
            && $this->luminousIntensity === $other->luminousIntensity
            && $this->digital === $other->digital;
    }

    /**
     * Check if this formula is dimensionless (all exponents are zero).
     */
    public function isDimensionless(): bool
    {
        return 0 === $this->length
            && 0 === $this->mass
            && 0 === $this->time
            && 0 === $this->electricCurrent
            && 0 === $this->temperature
            && 0 === $this->amountOfSubstance
            && 0 === $this->luminousIntensity
            && 0 === $this->digital;
    }

    /**
     * Get a human-readable representation of the formula.
     *
     * Returns a compact notation showing only non-zero exponents.
     * Examples:
     * - [L¹] for Length
     * - [L²] for Area
     * - [L¹T⁻¹] for Velocity
     * - [L²M¹T⁻²] for Energy
     * - [] for dimensionless
     */
    public function toString(): string
    {
        if ($this->isDimensionless()) {
            return '[]';
        }

        $parts = [];

        if (0 !== $this->length) {
            $parts[] = 'L'.$this->formatExponent($this->length);
        }
        if (0 !== $this->mass) {
            $parts[] = 'M'.$this->formatExponent($this->mass);
        }
        if (0 !== $this->time) {
            $parts[] = 'T'.$this->formatExponent($this->time);
        }
        if (0 !== $this->electricCurrent) {
            $parts[] = 'I'.$this->formatExponent($this->electricCurrent);
        }
        if (0 !== $this->temperature) {
            $parts[] = 'Θ'.$this->formatExponent($this->temperature);
        }
        if (0 !== $this->amountOfSubstance) {
            $parts[] = 'N'.$this->formatExponent($this->amountOfSubstance);
        }
        if (0 !== $this->luminousIntensity) {
            $parts[] = 'J'.$this->formatExponent($this->luminousIntensity);
        }
        if (0 !== $this->digital) {
            $parts[] = 'D'.$this->formatExponent($this->digital);
        }

        return '['.\implode('', $parts).']';
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Format an exponent for display.
     */
    private function formatExponent(int $exponent): string
    {
        if (1 === $exponent) {
            return '¹';
        }

        // Convert to superscript
        $str = (string) $exponent;

        return \strtr($str, [
            '0' => '⁰',
            '1' => '¹',
            '2' => '²',
            '3' => '³',
            '4' => '⁴',
            '5' => '⁵',
            '6' => '⁶',
            '7' => '⁷',
            '8' => '⁸',
            '9' => '⁹',
            '-' => '⁻',
        ]);
    }

    /**
     * Get an array representation of all exponents.
     *
     * @return array{length: int, mass: int, time: int, electricCurrent: int, temperature: int, amountOfSubstance: int, luminousIntensity: int, digital: int}
     */
    public function toArray(): array
    {
        return [
            'length' => $this->length,
            'mass' => $this->mass,
            'time' => $this->time,
            'electricCurrent' => $this->electricCurrent,
            'temperature' => $this->temperature,
            'amountOfSubstance' => $this->amountOfSubstance,
            'luminousIntensity' => $this->luminousIntensity,
            'digital' => $this->digital,
        ];
    }
}
