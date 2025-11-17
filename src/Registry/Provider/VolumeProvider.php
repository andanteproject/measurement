<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Area\Area;
use Andante\Measurement\Quantity\Area\ImperialArea;
use Andante\Measurement\Quantity\Area\MetricArea;
use Andante\Measurement\Quantity\Length\ImperialLength;
use Andante\Measurement\Quantity\Length\Length;
use Andante\Measurement\Quantity\Length\MetricLength;
use Andante\Measurement\Quantity\Volume\Gas\NormalCubicMeter;
use Andante\Measurement\Quantity\Volume\Gas\StandardCubicFoot;
use Andante\Measurement\Quantity\Volume\Gas\StandardCubicMeter;
use Andante\Measurement\Quantity\Volume\Gas\ThousandCubicFeet;
use Andante\Measurement\Quantity\Volume\GasVolume;
use Andante\Measurement\Quantity\Volume\Imperial\CubicFoot;
use Andante\Measurement\Quantity\Volume\Imperial\CubicInch;
use Andante\Measurement\Quantity\Volume\Imperial\CubicYard;
use Andante\Measurement\Quantity\Volume\Imperial\ImperialFluidOunce;
use Andante\Measurement\Quantity\Volume\Imperial\ImperialGallon;
use Andante\Measurement\Quantity\Volume\Imperial\ImperialPint;
use Andante\Measurement\Quantity\Volume\Imperial\ImperialQuart;
use Andante\Measurement\Quantity\Volume\Imperial\USCup;
use Andante\Measurement\Quantity\Volume\Imperial\USFluidOunce;
use Andante\Measurement\Quantity\Volume\Imperial\USGallon;
use Andante\Measurement\Quantity\Volume\Imperial\USPint;
use Andante\Measurement\Quantity\Volume\Imperial\USQuart;
use Andante\Measurement\Quantity\Volume\Imperial\USTablespoon;
use Andante\Measurement\Quantity\Volume\Imperial\USTeaspoon;
use Andante\Measurement\Quantity\Volume\ImperialVolume;
use Andante\Measurement\Quantity\Volume\Metric\Centiliter;
use Andante\Measurement\Quantity\Volume\Metric\CubicCentimeter;
use Andante\Measurement\Quantity\Volume\Metric\CubicDecimeter;
use Andante\Measurement\Quantity\Volume\Metric\CubicMeter;
use Andante\Measurement\Quantity\Volume\Metric\CubicMillimeter;
use Andante\Measurement\Quantity\Volume\Metric\Deciliter;
use Andante\Measurement\Quantity\Volume\Metric\Hectoliter;
use Andante\Measurement\Quantity\Volume\Metric\Kiloliter;
use Andante\Measurement\Quantity\Volume\Metric\Liter;
use Andante\Measurement\Quantity\Volume\Metric\Milliliter;
use Andante\Measurement\Quantity\Volume\MetricVolume;
use Andante\Measurement\Quantity\Volume\Volume;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\UnitSystem;
use Andante\Measurement\Unit\Volume\GasVolumeUnit;
use Andante\Measurement\Unit\Volume\ImperialVolumeUnit;
use Andante\Measurement\Unit\Volume\MetricVolumeUnit;

/**
 * Provides default configuration for Volume quantities.
 *
 * Registers all metric, imperial, and gas volume units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to CubicMeter)
 * - Result quantity mappings for operations
 * - Default formula units
 */
final class VolumeProvider implements QuantityDefaultConfigProviderInterface
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
     * Centralized metric volume unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to CubicMeter)].
     *
     * @return array<array{MetricVolumeUnit, class-string, numeric-string}>
     */
    private function getMetricUnits(): array
    {
        return [
            // Cubic units
            [MetricVolumeUnit::CubicMeter, CubicMeter::class, '1'],
            [MetricVolumeUnit::CubicDecimeter, CubicDecimeter::class, '0.001'],         // 1 dm³ = 0.001 m³
            [MetricVolumeUnit::CubicCentimeter, CubicCentimeter::class, '0.000001'],    // 1 cm³ = 10⁻⁶ m³
            [MetricVolumeUnit::CubicMillimeter, CubicMillimeter::class, '0.000000001'], // 1 mm³ = 10⁻⁹ m³
            // Liter units (1 L = 0.001 m³ = 1 dm³)
            [MetricVolumeUnit::Liter, Liter::class, '0.001'],
            [MetricVolumeUnit::Deciliter, Deciliter::class, '0.0001'],       // 0.1 L
            [MetricVolumeUnit::Centiliter, Centiliter::class, '0.00001'],    // 0.01 L
            [MetricVolumeUnit::Milliliter, Milliliter::class, '0.000001'],   // 0.001 L = 1 cm³
            [MetricVolumeUnit::Hectoliter, Hectoliter::class, '0.1'],        // 100 L
            [MetricVolumeUnit::Kiloliter, Kiloliter::class, '1'],            // 1000 L = 1 m³
        ];
    }

    /**
     * Centralized imperial volume unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to CubicMeter)].
     *
     * @return array<array{ImperialVolumeUnit, class-string, numeric-string}>
     */
    private function getImperialUnits(): array
    {
        return [
            // Cubic units
            [ImperialVolumeUnit::CubicFoot, CubicFoot::class, '0.028316846592'],      // 1 ft³
            [ImperialVolumeUnit::CubicInch, CubicInch::class, '0.000016387064'],      // 1 in³
            [ImperialVolumeUnit::CubicYard, CubicYard::class, '0.764554857984'],      // 1 yd³

            // US liquid measures
            [ImperialVolumeUnit::USGallon, USGallon::class, '0.003785411784'],        // 1 US gal
            [ImperialVolumeUnit::USQuart, USQuart::class, '0.000946352946'],          // 1/4 US gal
            [ImperialVolumeUnit::USPint, USPint::class, '0.000473176473'],            // 1/8 US gal
            [ImperialVolumeUnit::USCup, USCup::class, '0.0002365882365'],             // 1/16 US gal
            [ImperialVolumeUnit::USFluidOunce, USFluidOunce::class, '0.0000295735295625'], // 1/128 US gal
            [ImperialVolumeUnit::USTablespoon, USTablespoon::class, '0.00001478676478125'], // 1/2 fl oz
            [ImperialVolumeUnit::USTeaspoon, USTeaspoon::class, '0.00000492892159375'],     // 1/6 fl oz

            // Imperial (UK) liquid measures
            [ImperialVolumeUnit::ImperialGallon, ImperialGallon::class, '0.00454609'],  // 1 imp gal
            [ImperialVolumeUnit::ImperialQuart, ImperialQuart::class, '0.0011365225'],  // 1/4 imp gal
            [ImperialVolumeUnit::ImperialPint, ImperialPint::class, '0.00056826125'],   // 1/8 imp gal
            [ImperialVolumeUnit::ImperialFluidOunce, ImperialFluidOunce::class, '0.0000284130625'], // 1/160 imp gal
        ];
    }

    /**
     * Centralized gas volume unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to CubicMeter)].
     *
     * Note: Gas volumes at standard conditions. Conversion factors are approximate
     * as they depend on pressure and temperature conditions.
     *
     * Smc: 15°C, 101.325 kPa (European standard)
     * Nmc: 0°C, 101.325 kPa (ISO normal conditions)
     * scf: 60°F (15.56°C), 14.696 psi (US standard)
     *
     * At same actual conditions, Smc ≈ Nmc × (273.15 + 15) / 273.15 ≈ 1.0548 Nmc
     *
     * @return array<array{GasVolumeUnit, class-string, numeric-string}>
     */
    private function getGasUnits(): array
    {
        return [
            // For conversion purposes, we treat these as equivalent to actual cubic meters
            // The temperature/pressure adjustment is a conceptual difference, not a physical volume difference
            [GasVolumeUnit::StandardCubicMeter, StandardCubicMeter::class, '1'],
            [GasVolumeUnit::NormalCubicMeter, NormalCubicMeter::class, '0.9481'],        // Nmc → Smc: T(0°C)/T(15°C)
            [GasVolumeUnit::StandardCubicFoot, StandardCubicFoot::class, '0.028316846592'],  // Same as ft³
            [GasVolumeUnit::ThousandCubicFeet, ThousandCubicFeet::class, '28.316846592'],    // 1000 × ft³
        ];
    }

    public function registerUnits(UnitRegistry $registry): void
    {
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
        foreach ($this->getGasUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
    }

    public function registerConversionFactors(ConversionFactorRegistry $registry): void
    {
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
        foreach ($this->getGasUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
    }

    public function registerResultMappings(ResultQuantityRegistry $registry): void
    {
        $lengthFormula = new DimensionalFormula(length: 1);
        $areaFormula = new DimensionalFormula(length: 2);
        $volumeFormula = new DimensionalFormula(length: 3);

        // Volume → Volume (L³) mappings
        // Unit-specific classes → mid-level class (preserves system)
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $volumeFormula, MetricVolume::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $volumeFormula, ImperialVolume::class);
        }
        foreach ($this->getGasUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $volumeFormula, GasVolume::class);
        }

        // Mid-level classes → themselves
        $registry->register(MetricVolume::class, $volumeFormula, MetricVolume::class);
        $registry->register(ImperialVolume::class, $volumeFormula, ImperialVolume::class);
        $registry->register(GasVolume::class, $volumeFormula, GasVolume::class);

        // Generic
        $registry->register(Volume::class, $volumeFormula, Volume::class);
        $registry->registerGeneric($volumeFormula, Volume::class);

        // ∛Volume → Length (L¹) mappings (cube root of volume)
        // Unit-specific classes → mid-level Length class (preserves system)
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $lengthFormula, MetricLength::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $lengthFormula, ImperialLength::class);
        }
        foreach ($this->getGasUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $lengthFormula, MetricLength::class);
        }

        // Mid-level Volume classes → mid-level Length classes
        $registry->register(MetricVolume::class, $lengthFormula, MetricLength::class);
        $registry->register(ImperialVolume::class, $lengthFormula, ImperialLength::class);
        $registry->register(GasVolume::class, $lengthFormula, MetricLength::class);

        // Generic Volume → generic Length
        $registry->register(Volume::class, $lengthFormula, Length::class);

        // Volume / Length → Area (L²) mappings
        // Unit-specific classes → mid-level Area class (preserves system)
        foreach ($this->getMetricUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $areaFormula, MetricArea::class);
        }
        foreach ($this->getImperialUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $areaFormula, ImperialArea::class);
        }
        foreach ($this->getGasUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $areaFormula, MetricArea::class);
        }

        // Mid-level Volume classes → mid-level Area classes
        $registry->register(MetricVolume::class, $areaFormula, MetricArea::class);
        $registry->register(ImperialVolume::class, $areaFormula, ImperialArea::class);
        $registry->register(GasVolume::class, $areaFormula, MetricArea::class);

        // Generic Volume → generic Area
        $registry->register(Volume::class, $areaFormula, Area::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        $formula = new DimensionalFormula(length: 3);

        // L³ → CubicMeter (default unit for volume dimension)
        $registry->register($formula, MetricVolumeUnit::CubicMeter);

        // L³ → CubicFoot for Imperial system
        $registry->registerForSystem($formula, UnitSystem::Imperial, ImperialVolumeUnit::CubicFoot);
    }
}
