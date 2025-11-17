<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Frequency\Frequency;
use Andante\Measurement\Quantity\Frequency\SI\BeatsPerMinute;
use Andante\Measurement\Quantity\Frequency\SI\Gigahertz;
use Andante\Measurement\Quantity\Frequency\SI\Hertz;
use Andante\Measurement\Quantity\Frequency\SI\Kilohertz;
use Andante\Measurement\Quantity\Frequency\SI\Megahertz;
use Andante\Measurement\Quantity\Frequency\SI\Millihertz;
use Andante\Measurement\Quantity\Frequency\SI\RevolutionPerMinute;
use Andante\Measurement\Quantity\Frequency\SI\RevolutionPerSecond;
use Andante\Measurement\Quantity\Frequency\SI\Terahertz;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Frequency\FrequencyUnit;

/**
 * Provides default configuration for Frequency quantities.
 *
 * Registers all frequency units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to hertz)
 * - Result quantity mappings for operations
 * - Default formula units
 *
 * Conversion factors (to hertz):
 * - 1 Hz = 1 Hz (base)
 * - 1 mHz = 0.001 Hz
 * - 1 kHz = 1,000 Hz
 * - 1 MHz = 1,000,000 Hz
 * - 1 GHz = 1,000,000,000 Hz
 * - 1 THz = 1,000,000,000,000 Hz
 * - 1 rpm = 1/60 Hz ≈ 0.01667 Hz
 * - 1 rps = 1 Hz
 * - 1 bpm = 1/60 Hz ≈ 0.01667 Hz
 */
final class FrequencyProvider implements QuantityDefaultConfigProviderInterface
{
    /**
     * Conversion factor for rpm/bpm to Hz.
     * 1 rpm = 1/60 Hz.
     */
    private const PER_MINUTE_TO_HERTZ = '0.01666666666666666666666666666666667';

    private static ?self $instance = null;

    private function __construct()
    {
    }

    public static function global(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * Reset the global instance (for testing).
     *
     * @internal
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    /**
     * Centralized frequency unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to hertz)].
     *
     * @return array<array{FrequencyUnit, class-string, numeric-string}>
     */
    private function getUnits(): array
    {
        return [
            [FrequencyUnit::Hertz, Hertz::class, '1'],
            [FrequencyUnit::Millihertz, Millihertz::class, '0.001'],
            [FrequencyUnit::Kilohertz, Kilohertz::class, '1000'],
            [FrequencyUnit::Megahertz, Megahertz::class, '1000000'],
            [FrequencyUnit::Gigahertz, Gigahertz::class, '1000000000'],
            [FrequencyUnit::Terahertz, Terahertz::class, '1000000000000'],
            [FrequencyUnit::RevolutionPerMinute, RevolutionPerMinute::class, self::PER_MINUTE_TO_HERTZ],
            [FrequencyUnit::RevolutionPerSecond, RevolutionPerSecond::class, '1'],
            [FrequencyUnit::BeatsPerMinute, BeatsPerMinute::class, self::PER_MINUTE_TO_HERTZ],
        ];
    }

    public function registerUnits(UnitRegistry $registry): void
    {
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
    }

    public function registerConversionFactors(ConversionFactorRegistry $registry): void
    {
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
    }

    public function registerResultMappings(ResultQuantityRegistry $registry): void
    {
        $formula = new DimensionalFormula(time: -1);

        // Unit-specific classes → generic class
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, Frequency::class);
        }

        // Generic
        $registry->register(Frequency::class, $formula, Frequency::class);
        $registry->registerGeneric($formula, Frequency::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        $formula = new DimensionalFormula(time: -1);

        // Default unit for frequency dimension (hertz)
        $registry->register($formula, FrequencyUnit::Hertz);
    }
}
