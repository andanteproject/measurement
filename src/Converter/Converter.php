<?php

declare(strict_types=1);

namespace Andante\Measurement\Converter;

use Andante\Measurement\Contract\ConverterInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\InvalidArgumentException;
use Andante\Measurement\Exception\InvalidOperationException;
use Andante\Measurement\Math\RoundingMode;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;

/**
 * Handles unit conversions using the ConversionFactorRegistry.
 *
 * Validates that conversions only happen between units of the same dimension
 * and performs the actual conversion math.
 *
 * Conversion process:
 * 1. Validate units are from the same dimension
 * 2. Convert from source unit to base unit: value * fromFactor
 * 3. Convert from base unit to target unit: baseValue / toFactor
 *
 * Example:
 * ```php
 * $converter = new Converter();
 * $kilometers = NumberFactory::create('5');
 * $meters = $converter->convert(
 *     $kilometers,
 *     MetricLengthUnit::Kilometer,
 *     MetricLengthUnit::Meter,
 *     scale: 2,
 *     roundingMode: RoundingMode::HalfUp
 * );
 * // Result: 5000.00 (5 km = 5000 m)
 * ```
 */
final class Converter implements ConverterInterface
{
    private ConversionFactorRegistry $registry;
    private UnitRegistry $unitRegistry;

    private static ?self $instance = null;

    /**
     * Get the global Converter instance.
     */
    public static function global(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Set a custom global Converter instance.
     *
     * @internal Primarily for testing
     */
    public static function setGlobal(self $converter): void
    {
        self::$instance = $converter;
    }

    /**
     * Reset the global Converter.
     *
     * @internal Primarily for testing
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    /**
     * Create a new converter instance.
     *
     * @param ConversionFactorRegistry|null $registry     The conversion factor registry to use.
     *                                                    If null, uses the global registry.
     * @param UnitRegistry|null             $unitRegistry The unit registry for quantity creation.
     *                                                    If null, uses the global registry.
     */
    public function __construct(
        ?ConversionFactorRegistry $registry = null,
        ?UnitRegistry $unitRegistry = null,
    ) {
        $this->registry = $registry ?? ConversionFactorRegistry::global();
        $this->unitRegistry = $unitRegistry ?? UnitRegistry::global();
    }

    /**
     * Convert a value from one unit to another.
     *
     * Supports both simple multiplicative conversions and affine conversions
     * (with offset, like temperature).
     *
     * @param NumberInterface       $value        The value to convert
     * @param UnitInterface         $fromUnit     The source unit
     * @param UnitInterface         $toUnit       The target unit
     * @param int                   $scale        Number of decimal places in the result (default: 10)
     * @param RoundingModeInterface $roundingMode Rounding mode for the division (default: HalfUp)
     *
     * @return NumberInterface The converted value
     *
     * @throws InvalidOperationException If units are from different dimensions
     * @throws InvalidArgumentException  If conversion factors are not registered for the units
     */
    public function convert(
        NumberInterface $value,
        UnitInterface $fromUnit,
        UnitInterface $toUnit,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): NumberInterface {
        // Validate same dimension
        if (!$fromUnit->dimension()->isCompatibleWith($toUnit->dimension())) {
            throw new InvalidOperationException(\sprintf('Cannot convert between different dimensions: %s and %s', $fromUnit->dimension()->getName(), $toUnit->dimension()->getName()));
        }

        // Check if units are registered
        if (!$this->registry->has($fromUnit)) {
            throw new InvalidArgumentException(\sprintf('Unit "%s" not registered in conversion registry', $fromUnit->name()));
        }

        if (!$this->registry->has($toUnit)) {
            throw new InvalidArgumentException(\sprintf('Unit "%s" not registered in conversion registry', $toUnit->name()));
        }

        // Get conversion rules
        $fromRule = $this->registry->getRule($fromUnit);
        $toRule = $this->registry->getRule($toUnit);

        // Convert: source → base → target
        // Using ConversionRule handles both multiplicative and affine conversions
        $inBase = $fromRule->toBase($value);

        return $toRule->fromBase($inBase, $scale, $roundingMode);
    }

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
     *
     * @throws InvalidOperationException If units are from different dimensions
     * @throws InvalidArgumentException  If conversion factors are not registered for the units
     */
    public function convertQuantity(
        QuantityInterface $quantity,
        UnitInterface $toUnit,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface {
        $convertedValue = $this->convert(
            $quantity->getValue(),
            $quantity->getUnit(),
            $toUnit,
            $scale,
            $roundingMode,
        );

        /** @var class-string<QuantityFactoryInterface> $quantityClass */
        $quantityClass = $this->unitRegistry->getQuantityClass($toUnit);

        return $quantityClass::from($convertedValue, $toUnit);
    }

    /**
     * Convert a value to the base unit of its dimension.
     *
     * @param NumberInterface $value The value to convert
     * @param UnitInterface   $unit  The unit the value is in
     *
     * @return NumberInterface The value in base units
     *
     * @throws InvalidArgumentException If conversion factor is not registered
     */
    public function toBaseUnit(NumberInterface $value, UnitInterface $unit): NumberInterface
    {
        if (!$this->registry->has($unit)) {
            throw new InvalidArgumentException(\sprintf('Unit "%s" not registered in conversion registry', $unit->name()));
        }

        return $this->registry->getRule($unit)->toBase($value);
    }

    /**
     * Convert a value from base units to a target unit.
     *
     * @param NumberInterface       $baseValue    The value in base units
     * @param UnitInterface         $toUnit       The target unit
     * @param int                   $scale        Number of decimal places (default: 10)
     * @param RoundingModeInterface $roundingMode Rounding mode (default: HalfUp)
     *
     * @return NumberInterface The value in the target unit
     *
     * @throws InvalidArgumentException If conversion factor is not registered
     */
    public function fromBaseUnit(
        NumberInterface $baseValue,
        UnitInterface $toUnit,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): NumberInterface {
        if (!$this->registry->has($toUnit)) {
            throw new InvalidArgumentException(\sprintf('Unit "%s" not registered in conversion registry', $toUnit->name()));
        }

        return $this->registry->getRule($toUnit)->fromBase($baseValue, $scale, $roundingMode);
    }
}
