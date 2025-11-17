<?php

declare(strict_types=1);

namespace Andante\Measurement\AutoScaler;

use Andante\Measurement\Contract\AutoScalerInterface;
use Andante\Measurement\Contract\ConverterInterface;
use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Converter\Converter;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Math\RoundingMode;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Automatically scales a quantity to the most human-readable unit.
 *
 * The AutoScaler finds the best unit for a quantity so that the value
 * falls within a target range (by default 1-1000).
 *
 * Example:
 * ```php
 * $autoScaler = new AutoScaler();
 * $result = $autoScaler->scale($meters1200); // 1200m → 1.2km
 * $result = $autoScaler->scale($mm5); // 5mm stays 5mm (already in range)
 * ```
 */
final class AutoScaler implements AutoScalerInterface
{
    private ConversionFactorRegistry $conversionFactorRegistry;
    private ConverterInterface $converter;

    private static ?self $instance = null;

    /**
     * Get the global AutoScaler instance.
     */
    public static function global(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Set a custom global AutoScaler instance.
     *
     * @internal Primarily for testing
     */
    public static function setGlobal(self $autoScaler): void
    {
        self::$instance = $autoScaler;
    }

    /**
     * Reset the global AutoScaler.
     *
     * @internal Primarily for testing
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    public function __construct(
        ?ConversionFactorRegistry $conversionFactorRegistry = null,
        ?ConverterInterface $converter = null,
    ) {
        $this->conversionFactorRegistry = $conversionFactorRegistry ?? ConversionFactorRegistry::global();
        $this->converter = $converter ?? Converter::global();
    }

    /**
     * Scale a quantity to the most human-readable unit.
     *
     * Finds a unit where the value falls within the target range (default 1-1000).
     * By default, uses only units from the same system as the input quantity.
     *
     * @param QuantityInterface    $quantity     The quantity to scale
     * @param UnitSystem|null      $system       Target unit system (null = same as input)
     * @param NumberInterface|null $minValue     Minimum target value (default: 1)
     * @param NumberInterface|null $maxValue     Maximum target value (default: 1000)
     * @param int                  $scale        Decimal places for conversion (default: 10)
     * @param RoundingMode         $roundingMode Rounding mode for conversion
     *
     * @return QuantityInterface The quantity converted to the optimal unit
     */
    public function scale(
        QuantityInterface $quantity,
        ?UnitSystem $system = null,
        ?NumberInterface $minValue = null,
        ?NumberInterface $maxValue = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface {
        $minValue ??= NumberFactory::create('1');
        $maxValue ??= NumberFactory::create('1000');

        $currentUnit = $quantity->getUnit();
        $dimension = $currentUnit->dimension();
        $targetSystem = $system ?? $currentUnit->system();
        $unitFamily = $currentUnit::class;

        // Get all candidate units for this dimension, system, and unit family
        // This ensures WattHour scales to KilowattHour, not to Megajoule
        $candidates = $this->getCandidateUnits($dimension, $targetSystem, $unitFamily);

        if ([] === $candidates) {
            // No candidates found, return original quantity
            return $quantity;
        }

        // Find the best unit
        $bestUnit = $this->findBestUnit($quantity, $candidates, $minValue, $maxValue, $scale, $roundingMode);

        if ($bestUnit === $currentUnit) {
            return $quantity;
        }

        return $this->converter->convertQuantity($quantity, $bestUnit, $scale, $roundingMode);
    }

    /**
     * Get candidate units for a dimension, system, and unit family.
     *
     * @param class-string<UnitInterface>|null $unitFamily The unit enum class to stay within (null = any)
     *
     * @return array<UnitInterface>
     */
    private function getCandidateUnits(
        DimensionInterface $dimension,
        UnitSystem $targetSystem,
        ?string $unitFamily = null,
    ): array {
        $allUnits = $this->conversionFactorRegistry->getRegisteredUnits();
        $candidates = [];

        foreach ($allUnits as $unit) {
            // Must be same dimension
            if (!$unit->dimension()->isCompatibleWith($dimension)) {
                continue;
            }

            // Must match target system (or if target is None, accept all)
            if (UnitSystem::None !== $targetSystem && $unit->system() !== $targetSystem) {
                continue;
            }

            // Must be same unit family (enum class) if specified
            if (null !== $unitFamily && $unit::class !== $unitFamily) {
                continue;
            }

            $candidates[] = $unit;
        }

        return $candidates;
    }

    /**
     * Find the best unit from candidates.
     *
     * Strategy: Convert to each candidate unit and find the one whose
     * absolute value is closest to the middle of the target range,
     * while still being within the range.
     *
     * @param array<UnitInterface> $candidates
     */
    private function findBestUnit(
        QuantityInterface $quantity,
        array $candidates,
        NumberInterface $minValue,
        NumberInterface $maxValue,
        int $scale,
        RoundingModeInterface $roundingMode,
    ): UnitInterface {
        $currentUnit = $quantity->getUnit();
        $bestUnit = $currentUnit;

        $minFloat = (float) $minValue->value();
        $maxFloat = (float) $maxValue->value();
        $targetValue = \sqrt($minFloat * $maxFloat);

        $bestScore = $this->calculateScore(
            (float) $quantity->getValue()->abs()->value(),
            $minFloat,
            $maxFloat,
            $targetValue,
        );

        foreach ($candidates as $candidate) {
            $convertedValue = $this->converter->convert(
                $quantity->getValue(),
                $currentUnit,
                $candidate,
                $scale,
                $roundingMode,
            );

            $absValue = (float) $convertedValue->abs()->value();
            $score = $this->calculateScore($absValue, $minFloat, $maxFloat, $targetValue);

            if ($score < $bestScore) {
                $bestScore = $score;
                $bestUnit = $candidate;
            }
        }

        return $bestUnit;
    }

    /**
     * Calculate a score for how good a value is.
     *
     * Lower score = better fit.
     * Prefers smaller values within range (e.g., 2 km over 20 hm).
     * Values outside range get a penalty.
     */
    private function calculateScore(
        float $value,
        float $minValue,
        float $maxValue,
        float $targetValue,
    ): float {
        // Handle zero or very small values
        if (1e-10 > $value) {
            return \PHP_FLOAT_MAX;
        }

        // Use logarithmic scale for comparisons
        $logValue = \log10($value);
        $logMin = \log10($minValue);
        $logMax = \log10($maxValue);

        // If within range, prefer smaller values (closer to minValue)
        // This makes 2 km preferred over 20 hm, 5 kWh over 50 Wh, etc.
        if ($value >= $minValue && $value <= $maxValue) {
            // Score is distance from minimum (smaller = better)
            return $logValue - $logMin;
        }

        // Outside range: add penalty based on how far outside
        if ($value < $minValue) {
            // Below minimum: penalty + distance below
            return ($logMax - $logMin) + ($logMin - $logValue);
        }

        // value > maxValue: penalty + distance above
        return ($logMax - $logMin) + ($logValue - $logMax);
    }
}
