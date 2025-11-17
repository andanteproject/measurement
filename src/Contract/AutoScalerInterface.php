<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Math\RoundingMode;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Interface for auto-scaling quantities to optimal units.
 */
interface AutoScalerInterface
{
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
    ): QuantityInterface;
}
