<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Digital\DataTransferRate\DataTransferRate;
use Andante\Measurement\Quantity\Digital\DataTransferRate\IEC\Bit\GibibitPerSecond;
use Andante\Measurement\Quantity\Digital\DataTransferRate\IEC\Bit\KibibitPerSecond;
use Andante\Measurement\Quantity\Digital\DataTransferRate\IEC\Bit\MebibitPerSecond;
use Andante\Measurement\Quantity\Digital\DataTransferRate\IEC\BitTransferRate as IECBitTransferRate;
use Andante\Measurement\Quantity\Digital\DataTransferRate\IEC\Byte\GibibytePerSecond;
use Andante\Measurement\Quantity\Digital\DataTransferRate\IEC\Byte\KibibytePerSecond;
use Andante\Measurement\Quantity\Digital\DataTransferRate\IEC\Byte\MebibytePerSecond;
use Andante\Measurement\Quantity\Digital\DataTransferRate\IEC\ByteTransferRate as IECByteTransferRate;
use Andante\Measurement\Quantity\Digital\DataTransferRate\IEC\TransferRate as IECTransferRate;
use Andante\Measurement\Quantity\Digital\DataTransferRate\SI\Bit\BitPerSecond;
use Andante\Measurement\Quantity\Digital\DataTransferRate\SI\Bit\GigabitPerSecond;
use Andante\Measurement\Quantity\Digital\DataTransferRate\SI\Bit\KilobitPerSecond;
use Andante\Measurement\Quantity\Digital\DataTransferRate\SI\Bit\MegabitPerSecond;
use Andante\Measurement\Quantity\Digital\DataTransferRate\SI\BitTransferRate;
use Andante\Measurement\Quantity\Digital\DataTransferRate\SI\Byte\BytePerSecond;
use Andante\Measurement\Quantity\Digital\DataTransferRate\SI\Byte\GigabytePerSecond;
use Andante\Measurement\Quantity\Digital\DataTransferRate\SI\Byte\KilobytePerSecond;
use Andante\Measurement\Quantity\Digital\DataTransferRate\SI\Byte\MegabytePerSecond;
use Andante\Measurement\Quantity\Digital\DataTransferRate\SI\ByteTransferRate;
use Andante\Measurement\Quantity\Digital\DataTransferRate\SI\TransferRate as SITransferRate;
use Andante\Measurement\Quantity\Digital\DigitalInformation\DigitalInformation;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\BitDigitalInformation as IECBitDigitalInformation;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\ByteDigitalInformation as IECByteDigitalInformation;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\DigitalInformation as IECDigitalInformation;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\BitDigitalInformation;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\ByteDigitalInformation;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\DigitalInformation as SIDigitalInformation;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Digital\IEC\IECBitTransferRateUnit;
use Andante\Measurement\Unit\Digital\IEC\IECByteTransferRateUnit;
use Andante\Measurement\Unit\Digital\IEC\IECTransferRateUnit;
use Andante\Measurement\Unit\Digital\SI\BitTransferRateUnit;
use Andante\Measurement\Unit\Digital\SI\ByteTransferRateUnit;
use Andante\Measurement\Unit\Digital\SI\SITransferRateUnit;

/**
 * Provides default configuration for Data Transfer Rate quantities.
 *
 * Registers all data transfer rate units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to BitPerSecond)
 * - Result quantity mappings for operations
 * - Default formula units
 */
final class DataTransferRateProvider implements QuantityDefaultConfigProviderInterface
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
     * SI (decimal) bit rate unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to BitPerSecond)].
     *
     * @return array<array{BitTransferRateUnit, class-string, numeric-string}>
     */
    private function getSIBitUnits(): array
    {
        return [
            [BitTransferRateUnit::BitPerSecond, BitPerSecond::class, '1'],             // base unit
            [BitTransferRateUnit::KilobitPerSecond, KilobitPerSecond::class, '1000'],  // 10^3
            [BitTransferRateUnit::MegabitPerSecond, MegabitPerSecond::class, '1000000'], // 10^6
            [BitTransferRateUnit::GigabitPerSecond, GigabitPerSecond::class, '1000000000'], // 10^9
        ];
    }

    /**
     * SI (decimal) byte rate unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to BitPerSecond)].
     *
     * @return array<array{ByteTransferRateUnit, class-string, numeric-string}>
     */
    private function getSIByteUnits(): array
    {
        return [
            [ByteTransferRateUnit::BytePerSecond, BytePerSecond::class, '8'],            // 8 bps
            [ByteTransferRateUnit::KilobytePerSecond, KilobytePerSecond::class, '8000'], // 1000 * 8
            [ByteTransferRateUnit::MegabytePerSecond, MegabytePerSecond::class, '8000000'], // 10^6 * 8
            [ByteTransferRateUnit::GigabytePerSecond, GigabytePerSecond::class, '8000000000'], // 10^9 * 8
        ];
    }

    /**
     * SI (decimal) combined transfer rate unit configuration.
     * Each entry: [unit, conversionFactor (to BitPerSecond)].
     *
     * @return array<array{SITransferRateUnit, numeric-string}>
     */
    private function getSICombinedUnits(): array
    {
        return [
            // Bit-based
            [SITransferRateUnit::BitPerSecond, '1'],
            [SITransferRateUnit::KilobitPerSecond, '1000'],
            [SITransferRateUnit::MegabitPerSecond, '1000000'],
            [SITransferRateUnit::GigabitPerSecond, '1000000000'],
            // Byte-based
            [SITransferRateUnit::BytePerSecond, '8'],
            [SITransferRateUnit::KilobytePerSecond, '8000'],
            [SITransferRateUnit::MegabytePerSecond, '8000000'],
            [SITransferRateUnit::GigabytePerSecond, '8000000000'],
        ];
    }

    /**
     * IEC (binary) bit rate unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to BitPerSecond)].
     *
     * @return array<array{IECBitTransferRateUnit, class-string, numeric-string}>
     */
    private function getIECBitUnits(): array
    {
        return [
            [IECBitTransferRateUnit::KibibitPerSecond, KibibitPerSecond::class, '1024'],      // 2^10
            [IECBitTransferRateUnit::MebibitPerSecond, MebibitPerSecond::class, '1048576'],   // 2^20
            [IECBitTransferRateUnit::GibibitPerSecond, GibibitPerSecond::class, '1073741824'], // 2^30
        ];
    }

    /**
     * IEC (binary) byte rate unit configuration.
     * Each entry: [unit, quantityClass, conversionFactor (to BitPerSecond)].
     *
     * @return array<array{IECByteTransferRateUnit, class-string, numeric-string}>
     */
    private function getIECByteUnits(): array
    {
        return [
            [IECByteTransferRateUnit::KibibytePerSecond, KibibytePerSecond::class, '8192'],       // 1024 * 8
            [IECByteTransferRateUnit::MebibytePerSecond, MebibytePerSecond::class, '8388608'],    // 1024^2 * 8
            [IECByteTransferRateUnit::GibibytePerSecond, GibibytePerSecond::class, '8589934592'], // 1024^3 * 8
        ];
    }

    /**
     * IEC (binary) combined transfer rate unit configuration.
     * Each entry: [unit, conversionFactor (to BitPerSecond)].
     *
     * @return array<array{IECTransferRateUnit, numeric-string}>
     */
    private function getIECCombinedUnits(): array
    {
        return [
            // Bit-based
            [IECTransferRateUnit::KibibitPerSecond, '1024'],
            [IECTransferRateUnit::MebibitPerSecond, '1048576'],
            [IECTransferRateUnit::GibibitPerSecond, '1073741824'],
            // Byte-based
            [IECTransferRateUnit::KibibytePerSecond, '8192'],
            [IECTransferRateUnit::MebibytePerSecond, '8388608'],
            [IECTransferRateUnit::GibibytePerSecond, '8589934592'],
        ];
    }

    public function registerUnits(UnitRegistry $registry): void
    {
        // Register specific unit enums → concrete classes
        foreach ($this->getSIBitUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
        foreach ($this->getSIByteUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
        foreach ($this->getIECBitUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
        foreach ($this->getIECByteUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }

        // Register combined unit enums → mid-level TransferRate classes
        foreach ($this->getSICombinedUnits() as [$unit, $factor]) {
            $registry->register($unit, SITransferRate::class);
        }
        foreach ($this->getIECCombinedUnits() as [$unit, $factor]) {
            $registry->register($unit, IECTransferRate::class);
        }
    }

    public function registerConversionFactors(ConversionFactorRegistry $registry): void
    {
        foreach ($this->getSIBitUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
        foreach ($this->getSIByteUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
        foreach ($this->getIECBitUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
        foreach ($this->getIECByteUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }

        // Combined unit enums
        foreach ($this->getSICombinedUnits() as [$unit, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
        foreach ($this->getIECCombinedUnits() as [$unit, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
    }

    public function registerResultMappings(ResultQuantityRegistry $registry): void
    {
        $dataRateFormula = new DimensionalFormula(digital: 1, time: -1);
        $digitalFormula = new DimensionalFormula(digital: 1);

        // DataTransferRate → DataTransferRate (D¹T⁻¹) mappings
        // SI bit rate classes → mid-level BitTransferRate class
        foreach ($this->getSIBitUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $dataRateFormula, BitTransferRate::class);
        }

        // SI byte rate classes → mid-level ByteTransferRate class
        foreach ($this->getSIByteUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $dataRateFormula, ByteTransferRate::class);
        }

        // IEC bit rate classes → mid-level IECBitTransferRate class
        foreach ($this->getIECBitUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $dataRateFormula, IECBitTransferRate::class);
        }

        // IEC byte rate classes → mid-level IECByteTransferRate class
        foreach ($this->getIECByteUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $dataRateFormula, IECByteTransferRate::class);
        }

        // Bit/Byte specific mid-level → system-level TransferRate
        $registry->register(BitTransferRate::class, $dataRateFormula, SITransferRate::class);
        $registry->register(ByteTransferRate::class, $dataRateFormula, SITransferRate::class);
        $registry->register(IECBitTransferRate::class, $dataRateFormula, IECTransferRate::class);
        $registry->register(IECByteTransferRate::class, $dataRateFormula, IECTransferRate::class);

        // System-level TransferRate → themselves
        $registry->register(SITransferRate::class, $dataRateFormula, SITransferRate::class);
        $registry->register(IECTransferRate::class, $dataRateFormula, IECTransferRate::class);

        // Generic
        $registry->register(DataTransferRate::class, $dataRateFormula, DataTransferRate::class);
        $registry->registerGeneric($dataRateFormula, DataTransferRate::class);

        // DataTransferRate × Time → DigitalInformation (D¹) mappings
        // SI bit rate classes → SI BitDigitalInformation
        foreach ($this->getSIBitUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $digitalFormula, BitDigitalInformation::class);
        }

        // SI byte rate classes → SI ByteDigitalInformation
        foreach ($this->getSIByteUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $digitalFormula, ByteDigitalInformation::class);
        }

        // IEC bit rate classes → IEC BitDigitalInformation
        foreach ($this->getIECBitUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $digitalFormula, IECBitDigitalInformation::class);
        }

        // IEC byte rate classes → IEC ByteDigitalInformation
        foreach ($this->getIECByteUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $digitalFormula, IECByteDigitalInformation::class);
        }

        // Mid-level TransferRate → mid-level DigitalInformation
        $registry->register(BitTransferRate::class, $digitalFormula, BitDigitalInformation::class);
        $registry->register(ByteTransferRate::class, $digitalFormula, ByteDigitalInformation::class);
        $registry->register(IECBitTransferRate::class, $digitalFormula, IECBitDigitalInformation::class);
        $registry->register(IECByteTransferRate::class, $digitalFormula, IECByteDigitalInformation::class);

        // System-level TransferRate → system-level DigitalInformation
        $registry->register(SITransferRate::class, $digitalFormula, SIDigitalInformation::class);
        $registry->register(IECTransferRate::class, $digitalFormula, IECDigitalInformation::class);

        // Generic DataTransferRate → generic DigitalInformation
        $registry->register(DataTransferRate::class, $digitalFormula, DigitalInformation::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // D¹T⁻¹ → BitPerSecond (default unit for data transfer rate dimension)
        $registry->register(
            new DimensionalFormula(digital: 1, time: -1),
            BitTransferRateUnit::BitPerSecond,
        );
    }
}
