<?php

declare(strict_types=1);

namespace Andante\Measurement\Dimension;

use Andante\Measurement\Contract\DimensionInterface;

/**
 * Data Transfer Rate dimension [D¹T⁻¹].
 *
 * Data transfer rate measures how much digital information is transferred
 * per unit of time. It is expressed as digital information divided by time.
 *
 * Common units: bit per second (bps), byte per second (B/s), kilobit per second (kbps), etc.
 */
final class DataTransferRate implements DimensionInterface
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
            // Data transfer rate = Digital Information / Time = D¹T⁻¹
            self::$formula = new DimensionalFormula(digital: 1, time: -1);
        }

        return self::$formula;
    }

    public function getName(): string
    {
        return 'Data Transfer Rate';
    }

    public function getSymbol(): string
    {
        return 'D/T';
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
