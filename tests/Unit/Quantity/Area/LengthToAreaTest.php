<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Area;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Area\Area;
use Andante\Measurement\Quantity\Area\ImperialArea;
use Andante\Measurement\Quantity\Area\MetricArea;
use Andante\Measurement\Quantity\Length\Imperial\Foot;
use Andante\Measurement\Quantity\Length\ImperialLength;
use Andante\Measurement\Quantity\Length\Length;
use Andante\Measurement\Quantity\Length\Metric\Meter;
use Andante\Measurement\Quantity\Length\MetricLength;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Area\MetricAreaUnit;
use Andante\Measurement\Unit\Length\ImperialLengthUnit;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use PHPUnit\Framework\TestCase;

/**
 * Test that multiplying Length quantities produces correct Area types.
 */
final class LengthToAreaTest extends TestCase
{
    protected function setUp(): void
    {
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
        ResultQuantityRegistry::reset();
        FormulaUnitRegistry::reset();
    }

    protected function tearDown(): void
    {
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
        ResultQuantityRegistry::reset();
        FormulaUnitRegistry::reset();
    }

    public function testMeterMultiplyMeterReturnsMetricArea(): void
    {
        $length1 = Meter::of(NumberFactory::create(100));
        $length2 = Meter::of(NumberFactory::create(50));

        $area = $length1->multiply($length2);

        self::assertInstanceOf(MetricArea::class, $area);
        self::assertSame(MetricAreaUnit::SquareMeter, $area->getUnit());
        // 100m × 50m = 5000 m²
        self::assertEqualsWithDelta(5000.0, (float) $area->getValue()->value(), 0.0001);
    }

    public function testMetricLengthMultiplyMetricLengthReturnsMetricArea(): void
    {
        $length1 = MetricLength::of(NumberFactory::create(2000), MetricLengthUnit::Meter);
        $length2 = MetricLength::of(NumberFactory::create(2000), MetricLengthUnit::Meter);

        $area = $length1->multiply($length2);

        self::assertInstanceOf(MetricArea::class, $area);
        self::assertSame(MetricAreaUnit::SquareMeter, $area->getUnit());
        // 2000m × 2000m = 4,000,000 m²
        self::assertEqualsWithDelta(4000000.0, (float) $area->getValue()->value(), 0.0001);
    }

    public function testFootMultiplyFootReturnsImperialArea(): void
    {
        $length1 = Foot::of(NumberFactory::create(10));
        $length2 = Foot::of(NumberFactory::create(20));

        $area = $length1->multiply($length2);

        self::assertInstanceOf(ImperialArea::class, $area);
        // Result should be in square meters (base unit), converted to ft²
        // 10ft × 20ft = 200 ft² (but result is in base unit m²)
        // Let's verify the value makes sense: 10ft = 3.048m, 20ft = 6.096m
        // 3.048 × 6.096 = 18.580608 m²
        self::assertEqualsWithDelta(18.580608, (float) $area->getValue()->value(), 0.0001);
    }

    public function testImperialLengthMultiplyImperialLengthReturnsImperialArea(): void
    {
        $length1 = ImperialLength::of(NumberFactory::create(100), ImperialLengthUnit::Foot);
        $length2 = ImperialLength::of(NumberFactory::create(100), ImperialLengthUnit::Foot);

        $area = $length1->multiply($length2);

        self::assertInstanceOf(ImperialArea::class, $area);
        // 100ft × 100ft = 10000 ft² in imperial
        // But internally: 100ft = 30.48m, so 30.48 × 30.48 = 929.0304 m²
        self::assertEqualsWithDelta(929.0304, (float) $area->getValue()->value(), 0.0001);
    }

    public function testGenericLengthMultiplyLengthReturnsArea(): void
    {
        $length1 = Length::of(NumberFactory::create(5), MetricLengthUnit::Meter);
        $length2 = Length::of(NumberFactory::create(10), MetricLengthUnit::Meter);

        $area = $length1->multiply($length2);

        self::assertInstanceOf(Area::class, $area);
        // 5m × 10m = 50 m²
        self::assertEqualsWithDelta(50.0, (float) $area->getValue()->value(), 0.0001);
    }

    public function testMixedUnitMultiplicationConvertsToBase(): void
    {
        $meters = Meter::of(NumberFactory::create(1000)); // 1000m = 1km
        $kilometers = MetricLength::of(NumberFactory::create(1), MetricLengthUnit::Kilometer);

        $area = $meters->multiply($kilometers);

        self::assertInstanceOf(MetricArea::class, $area);
        // 1000m × 1km = 1000m × 1000m = 1,000,000 m²
        self::assertEqualsWithDelta(1000000.0, (float) $area->getValue()->value(), 0.0001);
    }
}
