<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Trait;

use Andante\Measurement\AutoScaler\AutoScaler;
use Andante\Measurement\Contract\AutoScalerInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Math\RoundingMode;
use Andante\Measurement\Unit\UnitSystem;

/**
 * Trait that implements AutoScalableInterface using the global AutoScaler service.
 *
 * This trait expects the class to implement QuantityInterface.
 *
 * @phpstan-require-implements QuantityInterface
 */
trait AutoScalableTrait
{
    private static ?AutoScalerInterface $autoScaler = null;

    /**
     * Get the AutoScaler instance to use.
     */
    private static function getAutoScaler(): AutoScalerInterface
    {
        return self::$autoScaler ?? AutoScaler::global();
    }

    /**
     * Set a custom AutoScaler instance.
     *
     * Useful for testing or custom configurations.
     */
    public static function setAutoScaler(?AutoScalerInterface $autoScaler): void
    {
        self::$autoScaler = $autoScaler;
    }

    /**
     * Reset the AutoScaler to use the default global instance.
     */
    public static function resetAutoScaler(): void
    {
        self::$autoScaler = null;
    }

    /**
     * @see AutoScalableInterface::autoScale()
     */
    public function autoScale(
        ?UnitSystem $system = null,
        ?NumberInterface $minValue = null,
        ?NumberInterface $maxValue = null,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface {
        return self::getAutoScaler()->scale($this, $system, $minValue, $maxValue, $scale, $roundingMode);
    }
}
