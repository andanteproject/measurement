<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Time\Day;
use Andante\Measurement\Quantity\Time\Hour;
use Andante\Measurement\Quantity\Time\Microsecond;
use Andante\Measurement\Quantity\Time\Millisecond;
use Andante\Measurement\Quantity\Time\Minute;
use Andante\Measurement\Quantity\Time\Nanosecond;
use Andante\Measurement\Quantity\Time\Second;
use Andante\Measurement\Quantity\Time\Time;
use Andante\Measurement\Quantity\Time\Week;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Time\TimeUnit;

/**
 * Provides default configuration for Time quantities.
 *
 * Registers all time units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to Second)
 * - Result quantity mappings for operations
 * - Default formula units
 */
final class TimeProvider implements QuantityDefaultConfigProviderInterface
{
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
     * Centralized time unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to Second)].
     *
     * @return array<array{TimeUnit, class-string, numeric-string}>
     */
    private function getUnits(): array
    {
        return [
            // SI prefixed units
            [TimeUnit::Nanosecond, Nanosecond::class, '0.000000001'],    // 10⁻⁹ s
            [TimeUnit::Microsecond, Microsecond::class, '0.000001'],     // 10⁻⁶ s
            [TimeUnit::Millisecond, Millisecond::class, '0.001'],        // 10⁻³ s
            [TimeUnit::Second, Second::class, '1'],                       // base unit

            // Derived time units
            [TimeUnit::Minute, Minute::class, '60'],                     // 60 s
            [TimeUnit::Hour, Hour::class, '3600'],                       // 3600 s
            [TimeUnit::Day, Day::class, '86400'],                        // 86400 s
            [TimeUnit::Week, Week::class, '604800'],                     // 604800 s
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
        $formula = new DimensionalFormula(time: 1);

        // Unit-specific classes → generic Time class
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, Time::class);
        }

        // Generic
        $registry->register(Time::class, $formula, Time::class);
        $registry->registerGeneric($formula, Time::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // T¹ → Second (default unit for time dimension)
        $registry->register(
            new DimensionalFormula(time: 1),
            TimeUnit::Second,
        );
    }
}
