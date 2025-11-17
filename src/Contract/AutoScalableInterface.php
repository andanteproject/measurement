<?php

declare(strict_types=1);

namespace Andante\Measurement\Contract;

use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Math\RoundingMode;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Interface for quantities that can be auto-scaled to the most human-readable unit.
 */
interface AutoScalableInterface
{
    /**
     * Scale the quantity to the most human-readable unit.
     *
     * Finds a unit where the value falls within the target range (default 1-1000).
     *
     * @param UnitSystem|null      $system       Target unit system (null = same as current)
     * @param NumberInterface|null $minValue     Minimum target value (default: 1)
     * @param NumberInterface|null $maxValue     Maximum target value (default: 1000)
     * @param int                  $scale        Decimal places for conversion (default: 10)
     * @param RoundingMode         $roundingMode Rounding mode for conversion
     *
     * @return QuantityInterface The quantity converted to the optimal unit
     */
    public function autoScale(
        ?UnitSystem $system = null,
        ?NumberInterface $minValue = null,
        ?NumberInterface $maxValue = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface;
}
