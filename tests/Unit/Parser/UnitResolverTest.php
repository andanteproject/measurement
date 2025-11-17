<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Parser;

use Andante\Measurement\Exception\ParsingException;
use Andante\Measurement\Parser\UnitResolver;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Digital\SI\BitTransferRateUnit;
use Andante\Measurement\Unit\Energy\ElectricEnergyUnit;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use Andante\Measurement\Unit\Volume\MetricVolumeUnit;
use PHPUnit\Framework\TestCase;

final class UnitResolverTest extends TestCase
{
    private UnitResolver $resolver;
    private UnitRegistry $registry;

    protected function setUp(): void
    {
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();

        $this->registry = new UnitRegistry();

        // Register test units using the local ParserTestQuantity
        $this->registry->register(MetricLengthUnit::Meter, ParserTestQuantity::class);
        $this->registry->register(MetricLengthUnit::Kilometer, ParserTestQuantity::class);
        $this->registry->register(MetricLengthUnit::Centimeter, ParserTestQuantity::class);
        $this->registry->register(MetricLengthUnit::Micrometer, ParserTestQuantity::class);

        // Register units with notation variants for testing
        $this->registry->register(BitTransferRateUnit::GigabitPerSecond, ParserTestQuantity::class);
        $this->registry->register(BitTransferRateUnit::MegabitPerSecond, ParserTestQuantity::class);
        $this->registry->register(ElectricEnergyUnit::KilowattHour, ParserTestQuantity::class);
        $this->registry->register(MetricVolumeUnit::CubicMeter, ParserTestQuantity::class);

        $this->resolver = new UnitResolver($this->registry);
    }

    protected function tearDown(): void
    {
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
    }

    public function testResolveBySymbol(): void
    {
        $unit = $this->resolver->resolve('m');

        self::assertSame(MetricLengthUnit::Meter, $unit);
    }

    public function testResolveBySymbolCaseInsensitive(): void
    {
        $unit = $this->resolver->resolve('M');

        self::assertSame(MetricLengthUnit::Meter, $unit);
    }

    public function testResolveBySymbolKilometer(): void
    {
        $unit = $this->resolver->resolve('km');

        self::assertSame(MetricLengthUnit::Kilometer, $unit);
    }

    public function testResolveByName(): void
    {
        $unit = $this->resolver->resolve('meter');

        self::assertSame(MetricLengthUnit::Meter, $unit);
    }

    public function testResolveByNameCaseInsensitive(): void
    {
        $unit = $this->resolver->resolve('METER');

        self::assertSame(MetricLengthUnit::Meter, $unit);
    }

    public function testResolveWithDefaultUnitWhenEmpty(): void
    {
        $unit = $this->resolver->resolve('', null, MetricLengthUnit::Kilometer);

        self::assertSame(MetricLengthUnit::Kilometer, $unit);
    }

    public function testResolveEmptyWithoutDefaultThrows(): void
    {
        $this->expectException(ParsingException::class);
        $this->expectExceptionMessage('No unit specified');

        $this->resolver->resolve('');
    }

    public function testResolveUnknownUnitThrows(): void
    {
        $this->expectException(ParsingException::class);
        $this->expectExceptionMessage('Unknown unit: "xyz"');

        $this->resolver->resolve('xyz');
    }

    public function testResolveTrimsWhitespace(): void
    {
        $unit = $this->resolver->resolve('  km  ');

        self::assertSame(MetricLengthUnit::Kilometer, $unit);
    }

    public function testTryResolveReturnsNullOnFailure(): void
    {
        $unit = $this->resolver->tryResolve('unknown');

        self::assertNull($unit);
    }

    public function testTryResolveReturnsUnitOnSuccess(): void
    {
        $unit = $this->resolver->tryResolve('km');

        self::assertSame(MetricLengthUnit::Kilometer, $unit);
    }

    public function testTryResolveWithDefaultUnitWhenEmpty(): void
    {
        $unit = $this->resolver->tryResolve('', null, MetricLengthUnit::Meter);

        self::assertSame(MetricLengthUnit::Meter, $unit);
    }

    // Locale-based resolution tests

    public function testResolveByItalianName(): void
    {
        $unit = $this->resolver->resolve('metro', 'it_IT');

        self::assertSame(MetricLengthUnit::Meter, $unit);
    }

    public function testResolveByItalianPluralName(): void
    {
        $unit = $this->resolver->resolve('metri', 'it_IT');

        self::assertSame(MetricLengthUnit::Meter, $unit);
    }

    public function testResolveByItalianKilometerName(): void
    {
        $unit = $this->resolver->resolve('chilometri', 'it_IT');

        self::assertSame(MetricLengthUnit::Kilometer, $unit);
    }

    public function testResolveItalianNameCaseInsensitive(): void
    {
        $unit = $this->resolver->resolve('CHILOMETRI', 'it_IT');

        self::assertSame(MetricLengthUnit::Kilometer, $unit);
    }

    public function testSymbolTakesPriorityOverTranslatedName(): void
    {
        // Even with Italian locale, symbol should work
        $unit = $this->resolver->resolve('km', 'it_IT');

        self::assertSame(MetricLengthUnit::Kilometer, $unit);
    }

    // Symbol notation variant resolution tests

    public function testResolveByDefaultNotationSymbol(): void
    {
        // Default notation: Gbps
        $unit = $this->resolver->resolve('Gbps');

        self::assertSame(BitTransferRateUnit::GigabitPerSecond, $unit);
    }

    public function testResolveByIEEENotationSymbol(): void
    {
        // IEEE notation: Gbit/s
        $unit = $this->resolver->resolve('Gbit/s');

        self::assertSame(BitTransferRateUnit::GigabitPerSecond, $unit);
    }

    public function testResolveByIEEENotationSymbolCaseInsensitive(): void
    {
        // IEEE notation case-insensitive
        $unit = $this->resolver->resolve('gbit/s');

        self::assertSame(BitTransferRateUnit::GigabitPerSecond, $unit);
    }

    public function testResolveMegabitPerSecondByDefaultNotation(): void
    {
        $unit = $this->resolver->resolve('Mbps');

        self::assertSame(BitTransferRateUnit::MegabitPerSecond, $unit);
    }

    public function testResolveMegabitPerSecondByIEEENotation(): void
    {
        $unit = $this->resolver->resolve('Mbit/s');

        self::assertSame(BitTransferRateUnit::MegabitPerSecond, $unit);
    }

    public function testResolveKilowattHourByDefaultNotation(): void
    {
        $unit = $this->resolver->resolve('kWh');

        self::assertSame(ElectricEnergyUnit::KilowattHour, $unit);
    }

    public function testResolveKilowattHourByIEEENotation(): void
    {
        // IEEE notation: kW·h (middle dot)
        $unit = $this->resolver->resolve('kW·h');

        self::assertSame(ElectricEnergyUnit::KilowattHour, $unit);
    }

    public function testResolveKilowattHourByASCIINotation(): void
    {
        // ASCII notation: kW*h
        $unit = $this->resolver->resolve('kW*h');

        self::assertSame(ElectricEnergyUnit::KilowattHour, $unit);
    }

    public function testResolveCubicMeterByDefaultNotation(): void
    {
        // Default/Unicode notation: m³
        $unit = $this->resolver->resolve('m³');

        self::assertSame(MetricVolumeUnit::CubicMeter, $unit);
    }

    public function testResolveCubicMeterByASCIINotation(): void
    {
        // ASCII notation: m3
        $unit = $this->resolver->resolve('m3');

        self::assertSame(MetricVolumeUnit::CubicMeter, $unit);
    }

    public function testResolveMicrometerByDefaultNotation(): void
    {
        // Default/Unicode notation: μm
        $unit = $this->resolver->resolve('μm');

        self::assertSame(MetricLengthUnit::Micrometer, $unit);
    }

    public function testResolveMicrometerByASCIINotation(): void
    {
        // ASCII notation: um
        $unit = $this->resolver->resolve('um');

        self::assertSame(MetricLengthUnit::Micrometer, $unit);
    }
}
