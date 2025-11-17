<?php

declare(strict_types=1);

namespace Andante\Measurement\Registry\Provider;

use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Digital\DataTransferRate\DataTransferRate;
use Andante\Measurement\Quantity\Digital\DataTransferRate\IEC\BitTransferRate as IECBitTransferRate;
use Andante\Measurement\Quantity\Digital\DataTransferRate\IEC\ByteTransferRate as IECByteTransferRate;
use Andante\Measurement\Quantity\Digital\DataTransferRate\IEC\TransferRate as IECTransferRate;
use Andante\Measurement\Quantity\Digital\DataTransferRate\SI\BitTransferRate;
use Andante\Measurement\Quantity\Digital\DataTransferRate\SI\ByteTransferRate;
use Andante\Measurement\Quantity\Digital\DataTransferRate\SI\TransferRate as SITransferRate;
use Andante\Measurement\Quantity\Digital\DigitalInformation\DigitalInformation;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\Bit\Gibibit;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\Bit\Kibibit;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\Bit\Mebibit;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\Bit\Pebibit;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\Bit\Tebibit;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\BitDigitalInformation as IECBitDigitalInformation;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\Byte\Gibibyte;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\Byte\Kibibyte;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\Byte\Mebibyte;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\Byte\Pebibyte;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\Byte\Tebibyte;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\ByteDigitalInformation as IECByteDigitalInformation;
use Andante\Measurement\Quantity\Digital\DigitalInformation\IEC\DigitalInformation as IECDigitalInformation;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\Bit\Bit;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\Bit\Gigabit;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\Bit\Kilobit;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\Bit\Megabit;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\Bit\Petabit;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\Bit\Terabit;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\BitDigitalInformation;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\Byte\Byte;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\Byte\Gigabyte;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\Byte\Kilobyte;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\Byte\Megabyte;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\Byte\Petabyte;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\Byte\Terabyte;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\ByteDigitalInformation;
use Andante\Measurement\Quantity\Digital\DigitalInformation\SI\DigitalInformation as SIDigitalInformation;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Digital\IEC\IECBitUnit;
use Andante\Measurement\Unit\Digital\IEC\IECByteUnit;
use Andante\Measurement\Unit\Digital\IEC\IECDigitalUnit;
use Andante\Measurement\Unit\Digital\SI\SIBitUnit;
use Andante\Measurement\Unit\Digital\SI\SIByteUnit;
use Andante\Measurement\Unit\Digital\SI\SIDigitalUnit;

/**
 * Provides default configuration for Digital Information quantities.
 *
 * Registers all digital information units with their:
 * - Quantity class mappings
 * - Conversion factors (relative to Bit)
 * - Result quantity mappings for operations
 * - Default formula units
 */
final class DigitalInformationProvider implements QuantityDefaultConfigProviderInterface
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
     * SI Bit unit configuration (specific unit enum).
     * Each entry: [unit, quantityClass, conversionFactor (to Bit)].
     *
     * @return array<array{SIBitUnit, class-string, numeric-string}>
     */
    private function getSIBitUnits(): array
    {
        return [
            [SIBitUnit::Bit, Bit::class, '1'],                             // base unit
            [SIBitUnit::Kilobit, Kilobit::class, '1000'],                  // 10^3
            [SIBitUnit::Megabit, Megabit::class, '1000000'],               // 10^6
            [SIBitUnit::Gigabit, Gigabit::class, '1000000000'],            // 10^9
            [SIBitUnit::Terabit, Terabit::class, '1000000000000'],         // 10^12
            [SIBitUnit::Petabit, Petabit::class, '1000000000000000'],      // 10^15
        ];
    }

    /**
     * SI Byte unit configuration (specific unit enum).
     * Each entry: [unit, quantityClass, conversionFactor (to Bit)].
     *
     * @return array<array{SIByteUnit, class-string, numeric-string}>
     */
    private function getSIByteUnits(): array
    {
        return [
            [SIByteUnit::Byte, Byte::class, '8'],                        // 8 bits
            [SIByteUnit::Kilobyte, Kilobyte::class, '8000'],             // 1000 * 8
            [SIByteUnit::Megabyte, Megabyte::class, '8000000'],          // 1000^2 * 8
            [SIByteUnit::Gigabyte, Gigabyte::class, '8000000000'],       // 1000^3 * 8
            [SIByteUnit::Terabyte, Terabyte::class, '8000000000000'],    // 1000^4 * 8
            [SIByteUnit::Petabyte, Petabyte::class, '8000000000000000'], // 1000^5 * 8
        ];
    }

    /**
     * SI combined unit configuration (for mid-level SIDigitalInformation).
     * Each entry: [unit, conversionFactor (to Bit)].
     *
     * @return array<array{SIDigitalUnit, numeric-string}>
     */
    private function getSICombinedUnits(): array
    {
        return [
            // Bit-based
            [SIDigitalUnit::Bit, '1'],
            [SIDigitalUnit::Kilobit, '1000'],
            [SIDigitalUnit::Megabit, '1000000'],
            [SIDigitalUnit::Gigabit, '1000000000'],
            [SIDigitalUnit::Terabit, '1000000000000'],
            [SIDigitalUnit::Petabit, '1000000000000000'],
            // Byte-based
            [SIDigitalUnit::Byte, '8'],
            [SIDigitalUnit::Kilobyte, '8000'],
            [SIDigitalUnit::Megabyte, '8000000'],
            [SIDigitalUnit::Gigabyte, '8000000000'],
            [SIDigitalUnit::Terabyte, '8000000000000'],
            [SIDigitalUnit::Petabyte, '8000000000000000'],
        ];
    }

    /**
     * IEC Bit unit configuration (specific unit enum).
     * Each entry: [unit, quantityClass, conversionFactor (to Bit)].
     *
     * @return array<array{IECBitUnit, class-string, numeric-string}>
     */
    private function getIECBitUnits(): array
    {
        return [
            [IECBitUnit::Kibibit, Kibibit::class, '1024'],                // 2^10
            [IECBitUnit::Mebibit, Mebibit::class, '1048576'],             // 2^20
            [IECBitUnit::Gibibit, Gibibit::class, '1073741824'],          // 2^30
            [IECBitUnit::Tebibit, Tebibit::class, '1099511627776'],       // 2^40
            [IECBitUnit::Pebibit, Pebibit::class, '1125899906842624'],    // 2^50
        ];
    }

    /**
     * IEC Byte unit configuration (specific unit enum).
     * Each entry: [unit, quantityClass, conversionFactor (to Bit)].
     *
     * @return array<array{IECByteUnit, class-string, numeric-string}>
     */
    private function getIECByteUnits(): array
    {
        return [
            [IECByteUnit::Kibibyte, Kibibyte::class, '8192'],              // 1024 * 8
            [IECByteUnit::Mebibyte, Mebibyte::class, '8388608'],           // 1024^2 * 8
            [IECByteUnit::Gibibyte, Gibibyte::class, '8589934592'],        // 1024^3 * 8
            [IECByteUnit::Tebibyte, Tebibyte::class, '8796093022208'],     // 1024^4 * 8
            [IECByteUnit::Pebibyte, Pebibyte::class, '9007199254740992'],  // 1024^5 * 8
        ];
    }

    /**
     * IEC combined unit configuration (for mid-level IECDigitalInformation).
     * Each entry: [unit, conversionFactor (to Bit)].
     *
     * @return array<array{IECDigitalUnit, numeric-string}>
     */
    private function getIECCombinedUnits(): array
    {
        return [
            // Bit-based
            [IECDigitalUnit::Kibibit, '1024'],
            [IECDigitalUnit::Mebibit, '1048576'],
            [IECDigitalUnit::Gibibit, '1073741824'],
            [IECDigitalUnit::Tebibit, '1099511627776'],
            [IECDigitalUnit::Pebibit, '1125899906842624'],
            // Byte-based
            [IECDigitalUnit::Kibibyte, '8192'],
            [IECDigitalUnit::Mebibyte, '8388608'],
            [IECDigitalUnit::Gibibyte, '8589934592'],
            [IECDigitalUnit::Tebibyte, '8796093022208'],
            [IECDigitalUnit::Pebibyte, '9007199254740992'],
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

        // Register combined unit enums → mid-level DigitalInformation classes
        foreach ($this->getSICombinedUnits() as [$unit, $factor]) {
            $registry->register($unit, SIDigitalInformation::class);
        }
        foreach ($this->getIECCombinedUnits() as [$unit, $factor]) {
            $registry->register($unit, IECDigitalInformation::class);
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
        $digitalFormula = new DimensionalFormula(digital: 1);
        $dataRateFormula = new DimensionalFormula(digital: 1, time: -1);

        // DigitalInformation → DigitalInformation (D¹) mappings
        // SI Bit classes → mid-level BitDigitalInformation class
        foreach ($this->getSIBitUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $digitalFormula, BitDigitalInformation::class);
        }

        // SI Byte classes → mid-level ByteDigitalInformation class
        foreach ($this->getSIByteUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $digitalFormula, ByteDigitalInformation::class);
        }

        // IEC Bit classes → mid-level IECBitDigitalInformation class
        foreach ($this->getIECBitUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $digitalFormula, IECBitDigitalInformation::class);
        }

        // IEC Byte classes → mid-level IECByteDigitalInformation class
        foreach ($this->getIECByteUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $digitalFormula, IECByteDigitalInformation::class);
        }

        // Bit/Byte specific mid-level → system-level DigitalInformation
        $registry->register(BitDigitalInformation::class, $digitalFormula, SIDigitalInformation::class);
        $registry->register(ByteDigitalInformation::class, $digitalFormula, SIDigitalInformation::class);
        $registry->register(IECBitDigitalInformation::class, $digitalFormula, IECDigitalInformation::class);
        $registry->register(IECByteDigitalInformation::class, $digitalFormula, IECDigitalInformation::class);

        // System-level DigitalInformation → themselves
        $registry->register(SIDigitalInformation::class, $digitalFormula, SIDigitalInformation::class);
        $registry->register(IECDigitalInformation::class, $digitalFormula, IECDigitalInformation::class);

        // Generic
        $registry->register(DigitalInformation::class, $digitalFormula, DigitalInformation::class);
        $registry->registerGeneric($digitalFormula, DigitalInformation::class);

        // DigitalInformation / Time → DataTransferRate (D¹T⁻¹) mappings
        // SI Bit classes → SI BitTransferRate
        foreach ($this->getSIBitUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $dataRateFormula, BitTransferRate::class);
        }

        // SI Byte classes → SI ByteTransferRate
        foreach ($this->getSIByteUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $dataRateFormula, ByteTransferRate::class);
        }

        // IEC Bit classes → IEC BitTransferRate
        foreach ($this->getIECBitUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $dataRateFormula, IECBitTransferRate::class);
        }

        // IEC Byte classes → IEC ByteTransferRate
        foreach ($this->getIECByteUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $dataRateFormula, IECByteTransferRate::class);
        }

        // Mid-level DigitalInformation → mid-level TransferRate
        $registry->register(BitDigitalInformation::class, $dataRateFormula, BitTransferRate::class);
        $registry->register(ByteDigitalInformation::class, $dataRateFormula, ByteTransferRate::class);
        $registry->register(IECBitDigitalInformation::class, $dataRateFormula, IECBitTransferRate::class);
        $registry->register(IECByteDigitalInformation::class, $dataRateFormula, IECByteTransferRate::class);

        // System-level DigitalInformation → system-level TransferRate
        $registry->register(SIDigitalInformation::class, $dataRateFormula, SITransferRate::class);
        $registry->register(IECDigitalInformation::class, $dataRateFormula, IECTransferRate::class);

        // Generic DigitalInformation → generic DataTransferRate
        $registry->register(DigitalInformation::class, $dataRateFormula, DataTransferRate::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // D¹ → Byte (default unit for digital information dimension)
        $registry->register(
            new DimensionalFormula(digital: 1),
            SIByteUnit::Byte,
        );
    }
}
