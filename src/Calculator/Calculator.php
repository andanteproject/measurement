<?php

declare(strict_types=1);

namespace Andante\Measurement\Calculator;

use Andante\Measurement\Contract\CalculatorInterface;
use Andante\Measurement\Contract\ConverterInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Converter\Converter;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Exception\InvalidArgumentException;
use Andante\Measurement\Exception\InvalidOperationException;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Math\RoundingMode;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;

/**
 * Handles arithmetic operations on quantities.
 *
 * All operations return new quantity instances (immutable).
 * Operations between quantities require the same dimension.
 * Results are returned in the unit of the first operand.
 *
 * Example:
 * ```php
 * $calculator = new Calculator();
 *
 * $m100 = Meter::from(NumberFactory::create('100'));
 * $km1 = Kilometer::from(NumberFactory::create('1'));
 *
 * $sum = $calculator->add($m100, $km1); // 1100 meters
 * $diff = $calculator->subtract($km1, $m100); // 0.9 kilometers
 * $double = $calculator->multiplyByScalar($m100, NumberFactory::create('2')); // 200 meters
 * ```
 */
final class Calculator implements CalculatorInterface
{
    private ConverterInterface $converter;
    private UnitRegistry $unitRegistry;
    private ResultQuantityRegistry $resultQuantityRegistry;
    private FormulaUnitRegistry $formulaUnitRegistry;

    private static ?self $instance = null;

    /**
     * Get the global Calculator instance.
     */
    public static function global(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Set a custom global Calculator instance.
     *
     * @internal Primarily for testing
     */
    public static function setGlobal(self $calculator): void
    {
        self::$instance = $calculator;
    }

    /**
     * Reset the global Calculator.
     *
     * @internal Primarily for testing
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    public function __construct(
        ?ConverterInterface $converter = null,
        ?UnitRegistry $unitRegistry = null,
        ?ResultQuantityRegistry $resultQuantityRegistry = null,
        ?FormulaUnitRegistry $formulaUnitRegistry = null,
    ) {
        $this->converter = $converter ?? Converter::global();
        $this->unitRegistry = $unitRegistry ?? UnitRegistry::global();
        $this->resultQuantityRegistry = $resultQuantityRegistry ?? ResultQuantityRegistry::global();
        $this->formulaUnitRegistry = $formulaUnitRegistry ?? FormulaUnitRegistry::global();
    }

    /**
     * Add two quantities of the same dimension.
     *
     * The result is in the unit of the first operand.
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function add(QuantityInterface $a, QuantityInterface $b): QuantityInterface
    {
        $this->validateSameDimension($a, $b);

        $bConverted = $this->convertToSameUnit($b, $a);
        $resultValue = $a->getValue()->add($bConverted);

        return $this->createQuantity($resultValue, $a);
    }

    /**
     * Subtract second quantity from first.
     *
     * The result is in the unit of the first operand.
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function subtract(QuantityInterface $a, QuantityInterface $b): QuantityInterface
    {
        $this->validateSameDimension($a, $b);

        $bConverted = $this->convertToSameUnit($b, $a);
        $resultValue = $a->getValue()->subtract($bConverted);

        return $this->createQuantity($resultValue, $a);
    }

    /**
     * Multiply a quantity by a scalar value.
     *
     * The result is in the same unit as the input quantity.
     */
    public function multiplyByScalar(QuantityInterface $quantity, NumberInterface $scalar): QuantityInterface
    {
        $resultValue = $quantity->getValue()->multiply($scalar);

        return $this->createQuantity($resultValue, $quantity);
    }

    /**
     * Divide a quantity by a scalar value.
     *
     * The result is in the same unit as the input quantity.
     *
     * @param int                   $scale        Number of decimal places in the result (default: 10)
     * @param RoundingModeInterface $roundingMode Rounding mode for the division (default: HalfUp)
     */
    public function divideByScalar(
        QuantityInterface $quantity,
        NumberInterface $scalar,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface {
        $resultValue = $quantity->getValue()->divide($scalar, $scale, $roundingMode);

        return $this->createQuantity($resultValue, $quantity);
    }

    /**
     * Multiply two quantities, creating a derived quantity.
     *
     * The result dimension is the product of the two input dimensions.
     * For example: Length × Length = Area, Length × Time⁻¹ = Velocity.
     *
     * The result quantity class is determined by the first operand's class
     * and the resulting dimensional formula, using the DerivedQuantityRegistry.
     *
     * Both quantities are converted to their SI base units before multiplication
     * to ensure correct numerical results.
     *
     * @param QuantityInterface     $a            First quantity
     * @param QuantityInterface     $b            Second quantity
     * @param UnitInterface|null    $resultUnit   The unit for the result (optional, uses default if null)
     * @param int                   $scale        Number of decimal places (default: 10)
     * @param RoundingModeInterface $roundingMode Rounding mode (default: HalfUp)
     *
     * @throws InvalidOperationException If no derived quantity is registered for the result
     */
    public function multiply(
        QuantityInterface $a,
        QuantityInterface $b,
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface {
        // Calculate the resulting dimensional formula
        $aFormula = $a->getUnit()->dimension()->getFormula();
        $bFormula = $b->getUnit()->dimension()->getFormula();
        $resultFormula = $aFormula->multiply($bFormula);

        // Get result unit from registry if not provided
        $resultUnit = $resultUnit ?? $this->formulaUnitRegistry->getUnit($resultFormula);

        // Validate result unit has correct dimension
        $this->validateResultDimension($resultUnit, $resultFormula);

        // Convert both quantities to SI base units for calculation
        $aInBase = $this->converter->toBaseUnit($a->getValue(), $a->getUnit());
        $bInBase = $this->converter->toBaseUnit($b->getValue(), $b->getUnit());

        // Multiply the values
        $resultInBase = $aInBase->multiply($bInBase);

        // Convert to the target unit
        $resultValue = $this->converter->fromBaseUnit($resultInBase, $resultUnit, $scale, $roundingMode);

        // Get the quantity class for the result
        return $this->createDerivedQuantity($resultValue, $a, $resultFormula, $resultUnit);
    }

    /**
     * Divide two quantities, creating a derived quantity.
     *
     * The result dimension is the quotient of the two input dimensions.
     * For example: Length / Time = Velocity, Energy / Time = Power.
     *
     * The result quantity class is determined by the first operand's class
     * and the resulting dimensional formula, using the DerivedQuantityRegistry.
     *
     * Both quantities are converted to their SI base units before division
     * to ensure correct numerical results.
     *
     * @param QuantityInterface     $a            First quantity (dividend)
     * @param QuantityInterface     $b            Second quantity (divisor)
     * @param UnitInterface|null    $resultUnit   The unit for the result (optional, uses default if null)
     * @param int                   $scale        Number of decimal places (default: 10)
     * @param RoundingModeInterface $roundingMode Rounding mode (default: HalfUp)
     *
     * @throws InvalidOperationException If no derived quantity is registered for the result
     */
    public function divide(
        QuantityInterface $a,
        QuantityInterface $b,
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface {
        // Calculate the resulting dimensional formula
        $aFormula = $a->getUnit()->dimension()->getFormula();
        $bFormula = $b->getUnit()->dimension()->getFormula();
        $resultFormula = $aFormula->divide($bFormula);

        // Get result unit from registry if not provided
        $resultUnit = $resultUnit ?? $this->formulaUnitRegistry->getUnit($resultFormula);

        // Validate result unit has correct dimension
        $this->validateResultDimension($resultUnit, $resultFormula);

        // Convert both quantities to SI base units for calculation
        $aInBase = $this->converter->toBaseUnit($a->getValue(), $a->getUnit());
        $bInBase = $this->converter->toBaseUnit($b->getValue(), $b->getUnit());

        // Divide the values
        $resultInBase = $aInBase->divide($bInBase, $scale, $roundingMode);

        // Convert to the target unit
        $resultValue = $this->converter->fromBaseUnit($resultInBase, $resultUnit, $scale, $roundingMode);

        // Get the quantity class for the result
        return $this->createDerivedQuantity($resultValue, $a, $resultFormula, $resultUnit);
    }

    /**
     * Calculate the sum of multiple quantities.
     *
     * The result is in the unit of the first quantity.
     *
     * @param QuantityInterface $first         First quantity (at least one required)
     * @param QuantityInterface ...$quantities Additional quantities to sum
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function sum(QuantityInterface $first, QuantityInterface ...$quantities): QuantityInterface
    {
        $result = $first;

        foreach ($quantities as $quantity) {
            $result = $this->add($result, $quantity);
        }

        return $result;
    }

    /**
     * Calculate the average of multiple quantities.
     *
     * The result is in the unit of the first quantity.
     *
     * @param QuantityInterface $first         First quantity (at least one required)
     * @param QuantityInterface ...$quantities Additional quantities to average
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function average(
        QuantityInterface $first,
        QuantityInterface ...$quantities,
    ): QuantityInterface {
        $sum = $this->sum($first, ...$quantities);
        $count = \count($quantities) + 1;

        return $this->divideByScalar($sum, NumberFactory::create((string) $count));
    }

    /**
     * Get the absolute value of a quantity.
     *
     * Returns a new quantity with the absolute value, same unit.
     */
    public function abs(QuantityInterface $quantity): QuantityInterface
    {
        $resultValue = $quantity->getValue()->abs();

        return $this->createQuantity($resultValue, $quantity);
    }

    /**
     * Negate a quantity.
     *
     * Returns a new quantity with the negated value, same unit.
     */
    public function negate(QuantityInterface $quantity): QuantityInterface
    {
        $resultValue = $quantity->getValue()->negate();

        return $this->createQuantity($resultValue, $quantity);
    }

    /**
     * Round a quantity's value to specified precision.
     *
     * Returns a new quantity with the rounded value, same unit.
     *
     * @param QuantityInterface     $quantity  The quantity to round
     * @param int                   $precision Number of decimal places (default: 0)
     * @param RoundingModeInterface $mode      Rounding mode (default: HalfUp)
     */
    public function round(
        QuantityInterface $quantity,
        int $precision = 0,
        RoundingModeInterface $mode = RoundingMode::HalfUp,
    ): QuantityInterface {
        $resultValue = $quantity->getValue()->round($precision, $mode);

        return $this->createQuantity($resultValue, $quantity);
    }

    /**
     * Round a quantity's value down (towards negative infinity).
     *
     * Returns a new quantity with the floored value, same unit.
     *
     * @param QuantityInterface $quantity  The quantity to floor
     * @param int               $precision Number of decimal places (default: 0)
     */
    public function floor(QuantityInterface $quantity, int $precision = 0): QuantityInterface
    {
        $resultValue = $quantity->getValue()->round($precision, RoundingMode::Floor);

        return $this->createQuantity($resultValue, $quantity);
    }

    /**
     * Round a quantity's value up (towards positive infinity).
     *
     * Returns a new quantity with the ceiled value, same unit.
     *
     * @param QuantityInterface $quantity  The quantity to ceil
     * @param int               $precision Number of decimal places (default: 0)
     */
    public function ceil(QuantityInterface $quantity, int $precision = 0): QuantityInterface
    {
        $resultValue = $quantity->getValue()->round($precision, RoundingMode::Ceiling);

        return $this->createQuantity($resultValue, $quantity);
    }

    /**
     * Raise a quantity to an integer power.
     *
     * The dimensional formula is raised to the same power.
     * For example: Length² = Area, Length³ = Volume.
     *
     * Example:
     * ```php
     * $length = Meter::from(NumberFactory::create('3'));
     * $area = $calculator->power($length, 2); // 9 m²
     * $volume = $calculator->power($length, 3); // 27 m³
     * ```
     *
     * @param QuantityInterface     $quantity     The quantity to raise to a power
     * @param int                   $exponent     The integer exponent (can be negative)
     * @param UnitInterface|null    $resultUnit   The unit for the result (optional, uses default if null)
     * @param int                   $scale        Number of decimal places (default: 10)
     * @param RoundingModeInterface $roundingMode Rounding mode (default: HalfUp)
     *
     * @return QuantityInterface The resulting quantity with new dimension
     *
     * @throws InvalidOperationException If no unit is registered for the result formula
     */
    public function power(
        QuantityInterface $quantity,
        int $exponent,
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface {
        // Calculate the resulting dimensional formula
        $formula = $quantity->getUnit()->dimension()->getFormula();
        $resultFormula = $formula->power($exponent);

        // Get result unit from registry if not provided
        $resultUnit = $resultUnit ?? $this->formulaUnitRegistry->getUnit($resultFormula);

        // Validate result unit has correct dimension
        $this->validateResultDimension($resultUnit, $resultFormula);

        // Convert to base unit, apply power, convert back
        $valueInBase = $this->converter->toBaseUnit($quantity->getValue(), $quantity->getUnit());
        $resultInBase = $valueInBase->power(NumberFactory::create((string) $exponent));

        // Convert to the target unit
        $resultValue = $this->converter->fromBaseUnit($resultInBase, $resultUnit, $scale, $roundingMode);

        // Get the quantity class for the result
        return $this->createDerivedQuantity($resultValue, $quantity, $resultFormula, $resultUnit);
    }

    /**
     * Calculate the square root of a quantity.
     *
     * The dimensional formula exponents are halved.
     * For example: √Area = Length, √(m⁴) = m².
     *
     * Note: All dimension exponents must be even for sqrt to be valid.
     * For example, √[L²] = [L¹] is valid, but √[L¹] would result in [L^0.5]
     * which is not a valid integer exponent.
     *
     * Example:
     * ```php
     * $area = SquareMeter::from(NumberFactory::create('9'));
     * $length = $calculator->sqrt($area); // 3 meters
     * ```
     *
     * @param QuantityInterface     $quantity     The quantity to take the square root of
     * @param UnitInterface|null    $resultUnit   The unit for the result (optional, uses default if null)
     * @param int                   $scale        Number of decimal places (default: 10)
     * @param RoundingModeInterface $roundingMode Rounding mode (default: HalfUp)
     *
     * @return QuantityInterface The resulting quantity with halved dimension exponents
     *
     * @throws InvalidOperationException If dimension exponents are not all even
     * @throws InvalidOperationException If no unit is registered for the result formula
     */
    public function sqrt(
        QuantityInterface $quantity,
        ?UnitInterface $resultUnit = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface {
        // Get the dimensional formula and calculate square root (validates even exponents)
        $formula = $quantity->getUnit()->dimension()->getFormula();

        try {
            $resultFormula = $formula->root(2);
        } catch (InvalidArgumentException $e) {
            throw new InvalidOperationException($e->getMessage(), 0, $e);
        }

        // Get result unit from registry if not provided
        $resultUnit = $resultUnit ?? $this->formulaUnitRegistry->getUnit($resultFormula);

        // Validate result unit has correct dimension
        $this->validateResultDimension($resultUnit, $resultFormula);

        // Convert to base unit, apply sqrt, convert back
        $valueInBase = $this->converter->toBaseUnit($quantity->getValue(), $quantity->getUnit());
        $resultInBase = $valueInBase->sqrt($scale);

        // Convert to the target unit
        $resultValue = $this->converter->fromBaseUnit($resultInBase, $resultUnit, $scale, $roundingMode);

        // Get the quantity class for the result
        return $this->createDerivedQuantity($resultValue, $quantity, $resultFormula, $resultUnit);
    }

    /**
     * Calculate the ratio between two quantities of the same dimension.
     *
     * Returns a dimensionless number representing how many times larger
     * the first quantity is compared to the second.
     *
     * Example:
     * ```php
     * $km2 = Kilometer::from(NumberFactory::create('2'));
     * $m500 = Meter::from(NumberFactory::create('500'));
     * $ratio = $calculator->ratio($km2, $m500); // 4.0 (2km is 4x larger than 500m)
     * ```
     *
     * @param QuantityInterface     $a            The dividend quantity
     * @param QuantityInterface     $b            The divisor quantity
     * @param int                   $scale        Number of decimal places (default: 10)
     * @param RoundingModeInterface $roundingMode Rounding mode (default: HalfUp)
     *
     * @return NumberInterface The dimensionless ratio a/b
     *
     * @throws InvalidOperationException If quantities have different dimensions
     */
    public function ratio(
        QuantityInterface $a,
        QuantityInterface $b,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): NumberInterface {
        $this->validateSameDimension($a, $b);

        // Convert both to base units for accurate comparison
        $aInBase = $this->converter->toBaseUnit($a->getValue(), $a->getUnit());
        $bInBase = $this->converter->toBaseUnit($b->getValue(), $b->getUnit());

        return $aInBase->divide($bInBase, $scale, $roundingMode);
    }

    /**
     * Convert quantity b's value to a's unit.
     */
    private function convertToSameUnit(QuantityInterface $b, QuantityInterface $a): NumberInterface
    {
        if ($a->getUnit() === $b->getUnit()) {
            return $b->getValue();
        }

        return $this->converter->convert(
            $b->getValue(),
            $b->getUnit(),
            $a->getUnit(),
        );
    }

    /**
     * Create a new quantity with the given value, using the template quantity's unit.
     */
    private function createQuantity(NumberInterface $value, QuantityInterface $template): QuantityInterface
    {
        /** @var class-string<QuantityFactoryInterface> $quantityClass */
        $quantityClass = $this->unitRegistry->getQuantityClass($template->getUnit());

        return $quantityClass::from($value, $template->getUnit());
    }

    /**
     * Validate that two quantities have the same dimension.
     *
     * @throws InvalidOperationException If dimensions differ
     */
    private function validateSameDimension(QuantityInterface $a, QuantityInterface $b): void
    {
        $aDimension = $a->getUnit()->dimension();
        $bDimension = $b->getUnit()->dimension();

        if (!$aDimension->isCompatibleWith($bDimension)) {
            throw new InvalidOperationException(\sprintf('Cannot perform arithmetic on different dimensions: %s and %s', $aDimension->getName(), $bDimension->getName()));
        }
    }

    /**
     * Validate that the result unit has the expected dimensional formula.
     *
     * @throws InvalidOperationException If the result unit's dimension doesn't match the expected formula
     */
    private function validateResultDimension(UnitInterface $resultUnit, DimensionalFormula $expectedFormula): void
    {
        $resultFormula = $resultUnit->dimension()->getFormula();

        if (!$resultFormula->equals($expectedFormula)) {
            throw new InvalidOperationException(\sprintf('Result unit "%s" has dimension %s, but expected %s', $resultUnit->name(), $resultFormula->toString(), $expectedFormula->toString()));
        }
    }

    /**
     * Create a derived quantity using the DerivedQuantityRegistry.
     *
     * @param NumberInterface    $value          The computed value
     * @param QuantityInterface  $sourceQuantity The source quantity (determines result class)
     * @param DimensionalFormula $resultFormula  The resulting dimensional formula
     * @param UnitInterface      $resultUnit     The unit for the result
     *
     * @return QuantityInterface The derived quantity
     */
    private function createDerivedQuantity(
        NumberInterface $value,
        QuantityInterface $sourceQuantity,
        DimensionalFormula $resultFormula,
        UnitInterface $resultUnit,
    ): QuantityInterface {
        /** @var class-string<QuantityFactoryInterface> $quantityClass */
        $quantityClass = $this->resultQuantityRegistry->getQuantityClassFromInstance($sourceQuantity, $resultFormula);

        // Check if the result unit is compatible with the quantity class
        // If not, try to get a system-specific unit based on the source quantity's unit system
        $sourceSystem = $sourceQuantity->getUnit()->system();
        $resultUnitSystem = $resultUnit->system();

        if (!$sourceSystem->isMetric() && $resultUnitSystem->isMetric()) {
            // Source is imperial but result unit is metric - get imperial unit
            $resultUnit = $this->formulaUnitRegistry->getUnitForSystem($resultFormula, $sourceSystem);
        }

        return $quantityClass::from($value, $resultUnit);
    }
}
