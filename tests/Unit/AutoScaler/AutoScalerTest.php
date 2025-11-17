<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\AutoScaler;

use Andante\Measurement\AutoScaler\AutoScaler;
use Andante\Measurement\Contract\AutoScalableInterface;
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Converter\Converter;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Trait\AutoScalableTrait;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use Andante\Measurement\Unit\UnitSystem;
use PHPUnit\Framework\TestCase;

/**
 * Test quantity class that uses AutoScalableTrait.
 */
class TestAutoScalableQuantity implements QuantityInterface, QuantityFactoryInterface, AutoScalableInterface
{
    use AutoScalableTrait;

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

final class AutoScalerTest extends TestCase
{
    private AutoScaler $autoScaler;

    protected function setUp(): void
    {
        // Reset all global instances first to ensure clean state
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
        Converter::reset();
        AutoScaler::reset();

        // Set fresh empty registries (bypassing registerDefaults)
        $conversionRegistry = new ConversionFactorRegistry();
        ConversionFactorRegistry::setGlobal($conversionRegistry);
        $unitRegistry = new UnitRegistry();
        UnitRegistry::setGlobal($unitRegistry);

        // Register conversion factors for metric length units (only 4 for testing)
        $conversionRegistry->register(MetricLengthUnit::Millimeter, ConversionRule::factor(NumberFactory::create('0.001')));
        $conversionRegistry->register(MetricLengthUnit::Centimeter, ConversionRule::factor(NumberFactory::create('0.01')));
        $conversionRegistry->register(MetricLengthUnit::Meter, ConversionRule::factor(NumberFactory::create('1')));
        $conversionRegistry->register(MetricLengthUnit::Kilometer, ConversionRule::factor(NumberFactory::create('1000')));

        // Register quantity class for units
        $unitRegistry->register(MetricLengthUnit::Millimeter, TestAutoScalableQuantity::class);
        $unitRegistry->register(MetricLengthUnit::Centimeter, TestAutoScalableQuantity::class);
        $unitRegistry->register(MetricLengthUnit::Meter, TestAutoScalableQuantity::class);
        $unitRegistry->register(MetricLengthUnit::Kilometer, TestAutoScalableQuantity::class);

        $this->autoScaler = new AutoScaler();
    }

    protected function tearDown(): void
    {
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
        Converter::reset();
        AutoScaler::reset();
        TestAutoScalableQuantity::resetAutoScaler();
    }

    public function testScaleMetersToKilometers(): void
    {
        // 1200m should become 1.2km
        $meters = new TestAutoScalableQuantity(NumberFactory::create('1200'), MetricLengthUnit::Meter);

        $result = $this->autoScaler->scale($meters);

        self::assertSame(MetricLengthUnit::Kilometer, $result->getUnit());
        self::assertEqualsWithDelta(1.2, (float) $result->getValue()->value(), 0.0001);
    }

    public function testScaleMillimetersToMeters(): void
    {
        // 5000mm should become 5m
        $mm = new TestAutoScalableQuantity(NumberFactory::create('5000'), MetricLengthUnit::Millimeter);

        $result = $this->autoScaler->scale($mm);

        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
        self::assertEqualsWithDelta(5.0, (float) $result->getValue()->value(), 0.0001);
    }

    public function testScaleKilometersToCentimeters(): void
    {
        // 0.0005km = 50cm, should become cm
        $km = new TestAutoScalableQuantity(NumberFactory::create('0.0005'), MetricLengthUnit::Kilometer);

        $result = $this->autoScaler->scale($km);

        self::assertSame(MetricLengthUnit::Centimeter, $result->getUnit());
        self::assertEqualsWithDelta(50.0, (float) $result->getValue()->value(), 0.0001);
    }

    public function testScaleKeepsValueInRange(): void
    {
        // 50m is already in range (1-1000), should stay as meters
        $meters = new TestAutoScalableQuantity(NumberFactory::create('50'), MetricLengthUnit::Meter);

        $result = $this->autoScaler->scale($meters);

        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
        self::assertEqualsWithDelta(50.0, (float) $result->getValue()->value(), 0.0001);
    }

    public function testScaleVerySmallValue(): void
    {
        // 0.005m = 5mm, should become mm
        $meters = new TestAutoScalableQuantity(NumberFactory::create('0.005'), MetricLengthUnit::Meter);

        $result = $this->autoScaler->scale($meters);

        self::assertSame(MetricLengthUnit::Millimeter, $result->getUnit());
        self::assertEqualsWithDelta(5.0, (float) $result->getValue()->value(), 0.0001);
    }

    public function testScaleVeryLargeValue(): void
    {
        // 100000m = 100km
        $meters = new TestAutoScalableQuantity(NumberFactory::create('100000'), MetricLengthUnit::Meter);

        $result = $this->autoScaler->scale($meters);

        self::assertSame(MetricLengthUnit::Kilometer, $result->getUnit());
        self::assertEqualsWithDelta(100.0, (float) $result->getValue()->value(), 0.0001);
    }

    public function testScaleWithCustomRange(): void
    {
        // 50m with range 1-100 should stay as meters
        $meters = new TestAutoScalableQuantity(NumberFactory::create('50'), MetricLengthUnit::Meter);

        $result = $this->autoScaler->scale(
            $meters,
            minValue: NumberFactory::create('1'),
            maxValue: NumberFactory::create('100'),
        );

        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());

        // 500m with range 1-100 might scale to 0.5km or 50000cm depending on which is closer to target
        $meters500 = new TestAutoScalableQuantity(NumberFactory::create('500'), MetricLengthUnit::Meter);
        $result500 = $this->autoScaler->scale(
            $meters500,
            minValue: NumberFactory::create('1'),
            maxValue: NumberFactory::create('100'),
        );

        // 500m = 0.5km (outside 1-100 range) or 50000cm (outside range)
        // Both are outside range, but 0.5km is closer to the geometric mean
        self::assertSame(MetricLengthUnit::Kilometer, $result500->getUnit());
    }

    public function testScaleNegativeValue(): void
    {
        // -1200m should become -1.2km
        $meters = new TestAutoScalableQuantity(NumberFactory::create('-1200'), MetricLengthUnit::Meter);

        $result = $this->autoScaler->scale($meters);

        self::assertSame(MetricLengthUnit::Kilometer, $result->getUnit());
        self::assertEqualsWithDelta(-1.2, (float) $result->getValue()->value(), 0.0001);
    }

    public function testAutoScalableTrait(): void
    {
        // Test using the trait directly
        $meters = new TestAutoScalableQuantity(NumberFactory::create('1200'), MetricLengthUnit::Meter);

        $result = $meters->autoScale();

        self::assertSame(MetricLengthUnit::Kilometer, $result->getUnit());
        self::assertEqualsWithDelta(1.2, (float) $result->getValue()->value(), 0.0001);
    }

    public function testAutoScaleTraitWithCustomAutoScaler(): void
    {
        $meters = new TestAutoScalableQuantity(NumberFactory::create('1200'), MetricLengthUnit::Meter);

        // Set custom auto scaler
        $customAutoScaler = new AutoScaler();
        TestAutoScalableQuantity::setAutoScaler($customAutoScaler);

        $result = $meters->autoScale();

        self::assertSame(MetricLengthUnit::Kilometer, $result->getUnit());
    }

    public function testGlobalInstance(): void
    {
        $global1 = AutoScaler::global();
        $global2 = AutoScaler::global();

        self::assertSame($global1, $global2);
    }

    public function testSetGlobal(): void
    {
        $custom = new AutoScaler();
        AutoScaler::setGlobal($custom);

        self::assertSame($custom, AutoScaler::global());
    }

    public function testReset(): void
    {
        $global1 = AutoScaler::global();
        AutoScaler::reset();
        $global2 = AutoScaler::global();

        self::assertNotSame($global1, $global2);
    }

    public function testScaleWithSpecificSystem(): void
    {
        // Even though we only have metric units, specifying Metric should work
        $meters = new TestAutoScalableQuantity(NumberFactory::create('1200'), MetricLengthUnit::Meter);

        $result = $this->autoScaler->scale($meters, UnitSystem::Metric);

        self::assertSame(MetricLengthUnit::Kilometer, $result->getUnit());
    }

    public function testScaleReturnsOriginalWhenNoCandidates(): void
    {
        // Request Imperial system but we only have Metric units registered
        $meters = new TestAutoScalableQuantity(NumberFactory::create('1200'), MetricLengthUnit::Meter);

        $result = $this->autoScaler->scale($meters, UnitSystem::Imperial);

        // Should return original since no Imperial units are registered
        self::assertSame(MetricLengthUnit::Meter, $result->getUnit());
        self::assertEqualsWithDelta(1200.0, (float) $result->getValue()->value(), 0.0001);
    }
}
