<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Converter;

use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Converter\Converter;
use Andante\Measurement\Exception\InvalidArgumentException;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Math\RoundingMode;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use PHPUnit\Framework\TestCase;

/**
 * Mock quantity class for testing convertQuantity.
 */
class MockConverterQuantity implements QuantityInterface, QuantityFactoryInterface
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

final class ConverterTest extends TestCase
{
    private ConversionFactorRegistry $registry;
    private UnitRegistry $unitRegistry;
    private Converter $converter;

    protected function setUp(): void
    {
        $this->registry = new ConversionFactorRegistry();
        $this->unitRegistry = new UnitRegistry();
        $this->converter = new Converter($this->registry, $this->unitRegistry);

        // Register some basic conversion factors
        $this->registry->register(MetricLengthUnit::Meter, ConversionRule::factor(NumberFactory::create('1')));
        $this->registry->register(MetricLengthUnit::Kilometer, ConversionRule::factor(NumberFactory::create('1000')));
        $this->registry->register(MetricLengthUnit::Centimeter, ConversionRule::factor(NumberFactory::create('0.01')));
        $this->registry->register(MetricLengthUnit::Millimeter, ConversionRule::factor(NumberFactory::create('0.001')));

        // Register quantity classes for convertQuantity tests
        $this->unitRegistry->register(MetricLengthUnit::Meter, MockConverterQuantity::class);
        $this->unitRegistry->register(MetricLengthUnit::Kilometer, MockConverterQuantity::class);
        $this->unitRegistry->register(MetricLengthUnit::Centimeter, MockConverterQuantity::class);
    }

    protected function tearDown(): void
    {
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
    }

    public function testConvertKilometersToMeters(): void
    {
        $kilometers = NumberFactory::create('5');

        $meters = $this->converter->convert(
            $kilometers,
            MetricLengthUnit::Kilometer,
            MetricLengthUnit::Meter,
        );

        self::assertEqualsWithDelta(5000.0, (float) $meters->value(), 0.0001);
    }

    public function testConvertMetersToCentimeters(): void
    {
        $meters = NumberFactory::create('2.5');

        $centimeters = $this->converter->convert(
            $meters,
            MetricLengthUnit::Meter,
            MetricLengthUnit::Centimeter,
        );

        self::assertEqualsWithDelta(250.0, (float) $centimeters->value(), 0.0001);
    }

    public function testConvertCentimetersToKilometers(): void
    {
        $centimeters = NumberFactory::create('150000');

        $kilometers = $this->converter->convert(
            $centimeters,
            MetricLengthUnit::Centimeter,
            MetricLengthUnit::Kilometer,
        );

        self::assertEqualsWithDelta(1.5, (float) $kilometers->value(), 0.0001);
    }

    public function testConvertWithCustomScale(): void
    {
        $meters = NumberFactory::create('1');

        $kilometers = $this->converter->convert(
            $meters,
            MetricLengthUnit::Meter,
            MetricLengthUnit::Kilometer,
            scale: 6,
        );

        // 1 meter = 0.001 kilometers = 0.001000 (6 decimals)
        self::assertSame('0.001000', $kilometers->value());
    }

    public function testConvertWithCustomRoundingMode(): void
    {
        $value = NumberFactory::create('1.555');

        $result = $this->converter->convert(
            $value,
            MetricLengthUnit::Meter,
            MetricLengthUnit::Meter,
            scale: 1,
            roundingMode: RoundingMode::Up,
        );

        // 1.555 rounded up to 1 decimal = 1.6
        self::assertSame('1.6', $result->value());
    }

    public function testConvertThrowsExceptionForUnregisteredFromUnit(): void
    {
        // Create a fresh registry with no units
        $emptyRegistry = new ConversionFactorRegistry();
        $converter = new Converter($emptyRegistry);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unit "meter" not registered in conversion registry');

        $converter->convert(
            NumberFactory::create('5'),
            MetricLengthUnit::Meter,
            MetricLengthUnit::Kilometer,
        );
    }

    public function testConvertThrowsExceptionForUnregisteredToUnit(): void
    {
        // Register only the from unit
        $partialRegistry = new ConversionFactorRegistry();
        $partialRegistry->register(MetricLengthUnit::Meter, ConversionRule::factor(NumberFactory::create('1')));
        $converter = new Converter($partialRegistry);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unit "kilometer" not registered in conversion registry');

        $converter->convert(
            NumberFactory::create('5'),
            MetricLengthUnit::Meter,
            MetricLengthUnit::Kilometer,
        );
    }

    public function testConvertUsesGlobalRegistryByDefault(): void
    {
        // Register in global registry
        $globalRegistry = ConversionFactorRegistry::global();
        $globalRegistry->register(MetricLengthUnit::Meter, ConversionRule::factor(NumberFactory::create('1')));
        $globalRegistry->register(MetricLengthUnit::Kilometer, ConversionRule::factor(NumberFactory::create('1000')));

        // Create converter without specifying registry
        $converter = new Converter();

        $result = $converter->convert(
            NumberFactory::create('3'),
            MetricLengthUnit::Kilometer,
            MetricLengthUnit::Meter,
        );

        self::assertEqualsWithDelta(3000.0, (float) $result->value(), 0.0001);
    }

    public function testConvertSameUnitReturnsOriginalValue(): void
    {
        $value = NumberFactory::create('42.5');

        $result = $this->converter->convert(
            $value,
            MetricLengthUnit::Meter,
            MetricLengthUnit::Meter,
        );

        // The result will have the default scale of 10 decimal places
        self::assertEqualsWithDelta(42.5, (float) $result->value(), 0.0001);
    }

    public function testConvertPreservesArbitraryPrecision(): void
    {
        $value = NumberFactory::create('123456789.123456789');

        $result = $this->converter->convert(
            $value,
            MetricLengthUnit::Meter,
            MetricLengthUnit::Centimeter,
            scale: 15,
        );

        // 123456789.123456789 m = 12345678912.3456789 cm
        self::assertSame('12345678912.345678900000000', $result->value());
    }

    public function testConvertQuantityReturnsNewQuantity(): void
    {
        $quantity = new MockConverterQuantity(
            NumberFactory::create('5'),
            MetricLengthUnit::Kilometer,
        );

        $result = $this->converter->convertQuantity($quantity, MetricLengthUnit::Meter);

        self::assertInstanceOf(MockConverterQuantity::class, $result);
        self::assertEqualsWithDelta(5000.0, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }

    public function testConvertQuantityPreservesImmutability(): void
    {
        $original = new MockConverterQuantity(
            NumberFactory::create('100'),
            MetricLengthUnit::Meter,
        );

        $converted = $this->converter->convertQuantity($original, MetricLengthUnit::Centimeter);

        // Original should be unchanged
        self::assertEqualsWithDelta(100.0, (float) $original->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $original->getUnit());

        // Converted should have new values
        self::assertEqualsWithDelta(10000.0, (float) $converted->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Centimeter, $converted->getUnit());
    }

    public function testConvertQuantityWithCustomScale(): void
    {
        $quantity = new MockConverterQuantity(
            NumberFactory::create('1'),
            MetricLengthUnit::Meter,
        );

        $result = $this->converter->convertQuantity(
            $quantity,
            MetricLengthUnit::Kilometer,
            scale: 6,
        );

        self::assertSame('0.001000', $result->getValue()->value());
    }

    public function testConvertQuantitySameUnitReturnsSameValue(): void
    {
        $quantity = new MockConverterQuantity(
            NumberFactory::create('42.5'),
            MetricLengthUnit::Meter,
        );

        $result = $this->converter->convertQuantity($quantity, MetricLengthUnit::Meter);

        self::assertEqualsWithDelta(42.5, (float) $result->getValue()->value(), 0.0001);
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
    }
}
