<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Dimension\DimensionalFormula;

/**
 * Represents a physical dimension (Length, Mass, Time, etc.).
 *
 * A dimension defines the physical nature of a quantity,
 * expressed as a combination of base SI dimensions.
 */
interface DimensionInterface
{
    /**
     * Get the dimensional formula.
     *
     * The formula expresses this dimension in terms of the seven base SI dimensions:
     * Length (L), Mass (M), Time (T), Electric Current (I),
     * Temperature (Θ), Amount of Substance (N), Luminous Intensity (J).
     */
    public function getFormula(): DimensionalFormula;

    /**
     * Get the name of this dimension.
     *
     * Examples: "Length", "Area", "Energy", "Power"
     */
    public function getName(): string;

    /**
     * Get the symbol for this dimension.
     *
     * Examples: "L" for Length, "A" for Area, "E" for Energy
     */
    public function getSymbol(): string;

    /**
     * Check if this dimension is compatible with another for addition/subtraction.
     *
     * Two dimensions are compatible if their formulas are identical.
     */
    public function isCompatibleWith(DimensionInterface $other): bool;

    /**
     * Check if this dimension is dimensionless (all exponents are zero).
     */
    public function isDimensionless(): bool;
}
