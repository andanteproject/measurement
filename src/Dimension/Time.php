<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Time dimension [T¹].
 *
 * Time is one of the seven SI base dimensions.
 * The SI base unit is the second (s).
 *
 * Common units: second, millisecond, microsecond, nanosecond, minute, hour, day, week, etc.
 */
final class Time implements DimensionInterface
{
    private static ?self $instance = null;
    private static ?DimensionalFormula $formula = null;

    private function __construct()
    {
    }

    public static function instance(): self
    {
        return self::$instance ??= new self();
    }

    public function getFormula(): DimensionalFormula
    {
        if (null === self::$formula) {
            self::$formula = new DimensionalFormula(time: 1);
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Time';
    }

    public function getSymbol(): string
    {
        return 'T';
    }

    public function isCompatibleWith(DimensionInterface $other): bool
    {
        return $this->getFormula()->equals($other->getFormula());
    }

    public function isDimensionless(): bool
    {
        return false;
    }
}
