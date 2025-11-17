<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Formatter;

use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Formatter\FormatOptions;
use Andante\Measurement\Formatter\FormatStyle;
use Andante\Measurement\Formatter\Formatter;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Unit\Digital\SI\BitTransferRateUnit;
use Andante\Measurement\Unit\Energy\ElectricEnergyUnit;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use Andante\Measurement\Unit\SymbolNotation;
use Andante\Measurement\Unit\Volume\MetricVolumeUnit;
use PHPUnit\Framework\TestCase;

/**
 * Simple test quantity for formatter tests.
 */
class FormatterTestQuantity implements QuantityInterface, QuantityFactoryInterface
{
    public function __construct(
        private NumberInterface $value,
        private UnitInterface $unit,
    ) {
    }

    public static function from(NumberInterface $value, UnitInterface $unit): QuantityInterface
    {
        return new self($value, $unit);
    }

    public function getValue(): NumberInterface
    {
        return $this->value;
    }

    public function getUnit(): UnitInterface
    {
        return $this->unit;
    }
}

final class FormatterTest extends TestCase
{
    private Formatter $formatter;

    protected function setUp(): void
    {
        Formatter::reset();
        $this->formatter = new Formatter();
    }

    protected function tearDown(): void
    {
        Formatter::reset();
    }

    // Global service pattern tests

    public function testGlobalReturnsSameInstance(): void
    {
        $instance1 = Formatter::global();
        $instance2 = Formatter::global();

        self::assertSame($instance1, $instance2);
    }

    public function testResetClearsGlobalInstance(): void
    {
        $instance1 = Formatter::global();
        Formatter::reset();
        $instance2 = Formatter::global();

        self::assertNotSame($instance1, $instance2);
    }

    public function testSetGlobalReplacesInstance(): void
    {
        $custom = new Formatter();
        Formatter::setGlobal($custom);

        self::assertSame($custom, Formatter::global());
    }

    // Basic formatting tests

    public function testFormatSimpleQuantity(): void
    {
        $quantity = $this->createQuantity('100', MetricLengthUnit::Meter);

        $result = $this->formatter->format($quantity);

        self::assertSame('100 m', $result);
    }

    public function testFormatDecimalQuantity(): void
    {
        $quantity = $this->createQuantity('123.456', MetricLengthUnit::Meter);

        $result = $this->formatter->format($quantity);

        self::assertSame('123.456 m', $result);
    }

    public function testFormatWithTrailingZerosRemoved(): void
    {
        $quantity = $this->createQuantity('100.00', MetricLengthUnit::Meter);

        $result = $this->formatter->format($quantity);

        self::assertSame('100 m', $result);
    }

    public function testFormatLargeNumberWithThousandSeparator(): void
    {
        $quantity = $this->createQuantity('1234567', MetricLengthUnit::Meter);

        $result = $this->formatter->format($quantity);

        self::assertSame('1,234,567 m', $result);
    }

    public function testFormatNegativeNumber(): void
    {
        $quantity = $this->createQuantity('-100', MetricLengthUnit::Meter);

        $result = $this->formatter->format($quantity);

        self::assertSame('-100 m', $result);
    }

    public function testFormatLargeNegativeNumber(): void
    {
        $quantity = $this->createQuantity('-1234567', MetricLengthUnit::Meter);

        $result = $this->formatter->format($quantity);

        self::assertSame('-1,234,567 m', $result);
    }

    // Precision tests

    public function testFormatWithFixedPrecision(): void
    {
        $quantity = $this->createQuantity('123.456', MetricLengthUnit::Meter);
        $options = FormatOptions::create()->withPrecision(2);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('123.46 m', $result);
    }

    public function testFormatWithZeroPrecision(): void
    {
        $quantity = $this->createQuantity('123.456', MetricLengthUnit::Meter);
        $options = FormatOptions::create()->withPrecision(0);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('123 m', $result);
    }

    public function testFormatWithPrecisionAddsZeros(): void
    {
        $quantity = $this->createQuantity('100', MetricLengthUnit::Meter);
        $options = FormatOptions::create()->withPrecision(2);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('100.00 m', $result);
    }

    // Style tests

    public function testFormatShortStyle(): void
    {
        $quantity = $this->createQuantity('100', MetricLengthUnit::Meter);
        $options = FormatOptions::create()->withStyle(FormatStyle::Short);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('100 m', $result);
    }

    public function testFormatLongStyle(): void
    {
        $quantity = $this->createQuantity('100', MetricLengthUnit::Meter);
        $options = FormatOptions::create()->withStyle(FormatStyle::Long);

        $result = $this->formatter->format($quantity, $options);

        // Uses English translation (plural form for 100)
        self::assertSame('100 meters', $result);
    }

    public function testFormatNarrowStyle(): void
    {
        $quantity = $this->createQuantity('100', MetricLengthUnit::Meter);
        $options = FormatOptions::create()->withStyle(FormatStyle::Narrow);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('100m', $result);
    }

    public function testFormatNarrowStyleNoThousandSeparator(): void
    {
        $quantity = $this->createQuantity('1234567', MetricLengthUnit::Meter);
        $options = FormatOptions::create()->withStyle(FormatStyle::Narrow);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('1234567m', $result);
    }

    public function testFormatNumericStyle(): void
    {
        $quantity = $this->createQuantity('100', MetricLengthUnit::Meter);
        $options = FormatOptions::create()->withStyle(FormatStyle::ValueOnly);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('100', $result);
    }

    public function testFormatNumericStyleWithThousandSeparator(): void
    {
        $quantity = $this->createQuantity('1234567', MetricLengthUnit::Meter);
        $options = FormatOptions::create()->withStyle(FormatStyle::ValueOnly);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('1,234,567', $result);
    }

    // Locale tests

    public function testFormatItalianLocale(): void
    {
        $quantity = $this->createQuantity('1234.56', MetricLengthUnit::Meter);
        $options = FormatOptions::fromLocale('it_IT');

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('1.234,56 m', $result);
    }

    public function testFormatGermanLocale(): void
    {
        $quantity = $this->createQuantity('1234.56', MetricLengthUnit::Meter);
        $options = FormatOptions::fromLocale('de_DE');

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('1.234,56 m', $result);
    }

    public function testFormatWithCustomSeparators(): void
    {
        $quantity = $this->createQuantity('1234.56', MetricLengthUnit::Meter);
        $options = FormatOptions::create()
            ->withThousandSeparator(' ')
            ->withDecimalSeparator(',');

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('1 234,56 m', $result);
    }

    public function testFormatSwissStyle(): void
    {
        $quantity = $this->createQuantity('1234.56', MetricLengthUnit::Meter);
        $options = FormatOptions::create()
            ->withThousandSeparator("'")
            ->withDecimalSeparator('.');

        $result = $this->formatter->format($quantity, $options);

        self::assertSame("1'234.56 m", $result);
    }

    // Combined options tests

    public function testFormatWithPrecisionAndLocale(): void
    {
        $quantity = $this->createQuantity('1234.567', MetricLengthUnit::Meter);
        $options = FormatOptions::fromLocale('it_IT')
            ->withPrecision(2);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('1.234,57 m', $result);
    }

    public function testFormatLongStyleWithLocale(): void
    {
        $quantity = $this->createQuantity('1234', MetricLengthUnit::Meter);
        $options = FormatOptions::fromLocale('it_IT')
            ->withStyle(FormatStyle::Long);

        $result = $this->formatter->format($quantity, $options);

        // Uses Italian translation (plural form for 1234)
        self::assertSame('1.234 metri', $result);
    }

    public function testFormatNarrowStyleWithItalianDecimalSeparator(): void
    {
        $quantity = $this->createQuantity('123.45', MetricLengthUnit::Meter);
        $options = FormatOptions::fromLocale('it_IT')
            ->withStyle(FormatStyle::Narrow);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('123,45m', $result);
    }

    // Different units

    public function testFormatKilometer(): void
    {
        $quantity = $this->createQuantity('100', MetricLengthUnit::Kilometer);

        $result = $this->formatter->format($quantity);

        self::assertSame('100 km', $result);
    }

    public function testFormatKilometerLongStyle(): void
    {
        $quantity = $this->createQuantity('100', MetricLengthUnit::Kilometer);
        $options = FormatOptions::create()->withStyle(FormatStyle::Long);

        $result = $this->formatter->format($quantity, $options);

        // Uses English translation (plural form for 100)
        self::assertSame('100 kilometers', $result);
    }

    // Translation tests

    public function testFormatSingularEnglish(): void
    {
        $quantity = $this->createQuantity('1', MetricLengthUnit::Meter);
        $options = FormatOptions::create()->withStyle(FormatStyle::Long);

        $result = $this->formatter->format($quantity, $options);

        // Uses singular form for exactly 1
        self::assertSame('1 meter', $result);
    }

    public function testFormatSingularItalian(): void
    {
        $quantity = $this->createQuantity('1', MetricLengthUnit::Meter);
        $options = FormatOptions::fromLocale('it_IT')->withStyle(FormatStyle::Long);

        $result = $this->formatter->format($quantity, $options);

        // Uses Italian singular form for exactly 1
        self::assertSame('1 metro', $result);
    }

    public function testFormatPluralItalian(): void
    {
        $quantity = $this->createQuantity('5', MetricLengthUnit::Kilometer);
        $options = FormatOptions::fromLocale('it_IT')->withStyle(FormatStyle::Long);

        $result = $this->formatter->format($quantity, $options);

        // Uses Italian plural form
        self::assertSame('5 chilometri', $result);
    }

    // Unit-only format style tests

    public function testFormatSymbolStyle(): void
    {
        $quantity = $this->createQuantity('5', MetricLengthUnit::Kilometer);
        $options = FormatOptions::create()->withStyle(FormatStyle::UnitSymbolOnly);

        $result = $this->formatter->format($quantity, $options);

        // Returns just the symbol
        self::assertSame('km', $result);
    }

    public function testFormatSymbolStyleMeter(): void
    {
        $quantity = $this->createQuantity('100', MetricLengthUnit::Meter);
        $options = FormatOptions::create()->withStyle(FormatStyle::UnitSymbolOnly);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('m', $result);
    }

    public function testFormatNameStyle(): void
    {
        $quantity = $this->createQuantity('5', MetricLengthUnit::Kilometer);
        $options = FormatOptions::create()->withStyle(FormatStyle::UnitNameOnly);

        $result = $this->formatter->format($quantity, $options);

        // Returns plural form based on value
        self::assertSame('kilometers', $result);
    }

    public function testFormatNameStyleSingular(): void
    {
        $quantity = $this->createQuantity('1', MetricLengthUnit::Kilometer);
        $options = FormatOptions::create()->withStyle(FormatStyle::UnitNameOnly);

        $result = $this->formatter->format($quantity, $options);

        // Returns singular form for value 1
        self::assertSame('kilometer', $result);
    }

    public function testFormatNameStyleItalian(): void
    {
        $quantity = $this->createQuantity('5', MetricLengthUnit::Kilometer);
        $options = FormatOptions::fromLocale('it_IT')->withStyle(FormatStyle::UnitNameOnly);

        $result = $this->formatter->format($quantity, $options);

        // Returns Italian plural form
        self::assertSame('chilometri', $result);
    }

    public function testFormatNameStyleItalianSingular(): void
    {
        $quantity = $this->createQuantity('1', MetricLengthUnit::Meter);
        $options = FormatOptions::fromLocale('it_IT')->withStyle(FormatStyle::UnitNameOnly);

        $result = $this->formatter->format($quantity, $options);

        // Returns Italian singular form
        self::assertSame('metro', $result);
    }

    // Dual locale tests (separate number and unit locales)

    public function testFormatItalianNumbersWithEnglishUnitNames(): void
    {
        $quantity = $this->createQuantity('1234', MetricLengthUnit::Meter);
        $options = FormatOptions::fromLocale('it_IT')
            ->withUnitLocale('en')
            ->withStyle(FormatStyle::Long);

        $result = $this->formatter->format($quantity, $options);

        // Italian number formatting (dot as thousand separator) with English unit name
        self::assertSame('1.234 meters', $result);
    }

    public function testFormatEnglishNumbersWithItalianUnitNames(): void
    {
        $quantity = $this->createQuantity('1234', MetricLengthUnit::Meter);
        $options = FormatOptions::fromLocale('en_US')
            ->withUnitLocale('it_IT')
            ->withStyle(FormatStyle::Long);

        $result = $this->formatter->format($quantity, $options);

        // English number formatting (comma as thousand separator) with Italian unit name
        self::assertSame('1,234 metri', $result);
    }

    public function testFormatGermanNumbersWithEnglishUnitNames(): void
    {
        $quantity = $this->createQuantity('1234.56', MetricLengthUnit::Kilometer);
        $options = FormatOptions::fromLocale('de_DE')
            ->withUnitLocale('en')
            ->withStyle(FormatStyle::Long);

        $result = $this->formatter->format($quantity, $options);

        // German number formatting with English unit name
        self::assertSame('1.234,56 kilometers', $result);
    }

    public function testUnitLocaleDefaultsToNumberLocale(): void
    {
        $quantity = $this->createQuantity('1234', MetricLengthUnit::Meter);
        $options = FormatOptions::fromLocale('it_IT')
            ->withStyle(FormatStyle::Long);

        $result = $this->formatter->format($quantity, $options);

        // Both number and unit should use Italian locale
        self::assertSame('1.234 metri', $result);
    }

    public function testUnitLocaleWithSingularForm(): void
    {
        $quantity = $this->createQuantity('1', MetricLengthUnit::Meter);
        $options = FormatOptions::fromLocale('it_IT')
            ->withUnitLocale('en')
            ->withStyle(FormatStyle::Long);

        $result = $this->formatter->format($quantity, $options);

        // Italian number (no thousand separator for 1) with English singular unit
        self::assertSame('1 meter', $result);
    }

    public function testUnitLocaleWithUnitNameOnlyStyle(): void
    {
        $quantity = $this->createQuantity('5', MetricLengthUnit::Kilometer);
        $options = FormatOptions::fromLocale('it_IT')
            ->withUnitLocale('de')
            ->withStyle(FormatStyle::UnitNameOnly);

        $result = $this->formatter->format($quantity, $options);

        // Returns German unit name
        self::assertSame('Kilometer', $result);
    }

    // Symbol notation tests

    public function testFormatWithDefaultNotation(): void
    {
        $quantity = $this->createQuantity('100', BitTransferRateUnit::GigabitPerSecond);

        $result = $this->formatter->format($quantity);

        self::assertSame('100 Gbps', $result);
    }

    public function testFormatWithIEEENotation(): void
    {
        $quantity = $this->createQuantity('100', BitTransferRateUnit::GigabitPerSecond);
        $options = FormatOptions::create()->withSymbolNotation(SymbolNotation::IEEE);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('100 Gbit/s', $result);
    }

    public function testFormatMegabitPerSecondWithIEEENotation(): void
    {
        $quantity = $this->createQuantity('50', BitTransferRateUnit::MegabitPerSecond);
        $options = FormatOptions::create()->withSymbolNotation(SymbolNotation::IEEE);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('50 Mbit/s', $result);
    }

    public function testFormatKilowattHourWithDefaultNotation(): void
    {
        $quantity = $this->createQuantity('100', ElectricEnergyUnit::KilowattHour);

        $result = $this->formatter->format($quantity);

        self::assertSame('100 kWh', $result);
    }

    public function testFormatKilowattHourWithIEEENotation(): void
    {
        $quantity = $this->createQuantity('100', ElectricEnergyUnit::KilowattHour);
        $options = FormatOptions::create()->withSymbolNotation(SymbolNotation::IEEE);

        $result = $this->formatter->format($quantity, $options);

        // IEEE/Unicode notation uses middle dot
        self::assertSame('100 kW·h', $result);
    }

    public function testFormatKilowattHourWithASCIINotation(): void
    {
        $quantity = $this->createQuantity('100', ElectricEnergyUnit::KilowattHour);
        $options = FormatOptions::create()->withSymbolNotation(SymbolNotation::ASCII);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('100 kW*h', $result);
    }

    public function testFormatCubicMeterWithDefaultNotation(): void
    {
        $quantity = $this->createQuantity('50', MetricVolumeUnit::CubicMeter);

        $result = $this->formatter->format($quantity);

        // Default notation uses superscript
        self::assertSame('50 m³', $result);
    }

    public function testFormatCubicMeterWithASCIINotation(): void
    {
        $quantity = $this->createQuantity('50', MetricVolumeUnit::CubicMeter);
        $options = FormatOptions::create()->withSymbolNotation(SymbolNotation::ASCII);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('50 m3', $result);
    }

    public function testFormatMicrometerWithDefaultNotation(): void
    {
        $quantity = $this->createQuantity('100', MetricLengthUnit::Micrometer);

        $result = $this->formatter->format($quantity);

        // Default notation uses Greek mu
        self::assertSame('100 μm', $result);
    }

    public function testFormatMicrometerWithASCIINotation(): void
    {
        $quantity = $this->createQuantity('100', MetricLengthUnit::Micrometer);
        $options = FormatOptions::create()->withSymbolNotation(SymbolNotation::ASCII);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('100 um', $result);
    }

    public function testFormatNarrowStyleWithNotation(): void
    {
        $quantity = $this->createQuantity('100', BitTransferRateUnit::GigabitPerSecond);
        $options = FormatOptions::create()
            ->withStyle(FormatStyle::Narrow)
            ->withSymbolNotation(SymbolNotation::IEEE);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('100Gbit/s', $result);
    }

    public function testFormatUnitSymbolOnlyWithNotation(): void
    {
        $quantity = $this->createQuantity('100', BitTransferRateUnit::GigabitPerSecond);
        $options = FormatOptions::create()
            ->withStyle(FormatStyle::UnitSymbolOnly)
            ->withSymbolNotation(SymbolNotation::IEEE);

        $result = $this->formatter->format($quantity, $options);

        self::assertSame('Gbit/s', $result);
    }

    public function testFormatNotationWithLocale(): void
    {
        $quantity = $this->createQuantity('1234.56', BitTransferRateUnit::GigabitPerSecond);
        $options = FormatOptions::fromLocale('it_IT')
            ->withSymbolNotation(SymbolNotation::IEEE);

        $result = $this->formatter->format($quantity, $options);

        // Italian locale with IEEE notation
        self::assertSame('1.234,56 Gbit/s', $result);
    }

    public function testFormatNotationDoesNotAffectLongStyle(): void
    {
        $quantity = $this->createQuantity('100', BitTransferRateUnit::GigabitPerSecond);
        $options = FormatOptions::create()
            ->withStyle(FormatStyle::Long)
            ->withSymbolNotation(SymbolNotation::IEEE);

        $result = $this->formatter->format($quantity, $options);

        // Long style uses name, not symbol, so notation doesn't apply
        self::assertSame('100 gigabits per second', $result);
    }

    /**
     * @param numeric-string $value
     */
    private function createQuantity(string $value, UnitInterface $unit): QuantityInterface
    {
        return new FormatterTestQuantity(
            NumberFactory::create($value),
            $unit,
        );
    }
}
