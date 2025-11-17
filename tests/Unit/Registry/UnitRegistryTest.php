<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Registry;

use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Dimension\Length;
use Andante\Measurement\Exception\InvalidArgumentException;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use Andante\Measurement\Unit\UnitSystem;
use PHPUnit\Framework\TestCase;

// Stub classes for testing
interface StubQuantityInterface extends QuantityInterface
{
}

final class UnitRegistryTest extends TestCase
{
    private UnitRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new UnitRegistry();
    }

    protected function tearDown(): void
    {
        UnitRegistry::reset();
    }

    public function testRegisterAndGetQuantityClass(): void
    {
        $this->registry->register(MetricLengthUnit::Meter, StubQuantityInterface::class);

        $quantityClass = $this->registry->getQuantityClass(MetricLengthUnit::Meter);

        self::assertSame(StubQuantityInterface::class, $quantityClass);
    }

    public function testRegisterThrowsExceptionForInvalidQuantityClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Quantity class must implement');

        $this->registry->register(MetricLengthUnit::Meter, 'stdClass');
    }

    public function testGetQuantityClassThrowsExceptionForUnregisteredUnit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unit "meter" is not registered');

        $this->registry->getQuantityClass(MetricLengthUnit::Meter);
    }

    public function testHasReturnsTrueForRegisteredUnit(): void
    {
        $this->registry->register(MetricLengthUnit::Meter, StubQuantityInterface::class);

        self::assertTrue($this->registry->has(MetricLengthUnit::Meter));
    }

    public function testHasReturnsFalseForUnregisteredUnit(): void
    {
        self::assertFalse($this->registry->has(MetricLengthUnit::Meter));
    }

    public function testUnregister(): void
    {
        $this->registry->register(MetricLengthUnit::Meter, StubQuantityInterface::class);
        self::assertTrue($this->registry->has(MetricLengthUnit::Meter));

        $this->registry->unregister(MetricLengthUnit::Meter);

        self::assertFalse($this->registry->has(MetricLengthUnit::Meter));
    }

    public function testGetUnitsForDimension(): void
    {
        $this->registry->register(MetricLengthUnit::Meter, StubQuantityInterface::class);
        $this->registry->register(MetricLengthUnit::Kilometer, StubQuantityInterface::class);

        $units = $this->registry->getUnitsForDimension(Length::instance());

        self::assertCount(2, $units);
        self::assertContains(MetricLengthUnit::Meter, $units);
        self::assertContains(MetricLengthUnit::Kilometer, $units);
    }

    public function testGetUnitsForDimensionReturnsEmptyArrayWhenNoneRegistered(): void
    {
        $units = $this->registry->getUnitsForDimension(Length::instance());

        self::assertSame([], $units);
    }

    public function testGetUnitsForSystem(): void
    {
        $this->registry->register(MetricLengthUnit::Meter, StubQuantityInterface::class);
        $this->registry->register(MetricLengthUnit::Kilometer, StubQuantityInterface::class);

        $units = $this->registry->getUnitsForSystem(Length::instance(), UnitSystem::Metric);

        self::assertCount(2, $units);
        self::assertContains(MetricLengthUnit::Meter, $units);
        self::assertContains(MetricLengthUnit::Kilometer, $units);
    }

    public function testGetMetricUnits(): void
    {
        $this->registry->register(MetricLengthUnit::Meter, StubQuantityInterface::class);
        $this->registry->register(MetricLengthUnit::Kilometer, StubQuantityInterface::class);

        $units = $this->registry->getMetricUnits(Length::instance());

        self::assertCount(2, $units);
        self::assertContains(MetricLengthUnit::Meter, $units);
        self::assertContains(MetricLengthUnit::Kilometer, $units);
    }

    public function testGetImperialUnits(): void
    {
        $units = $this->registry->getImperialUnits(Length::instance());

        self::assertSame([], $units);
    }

    public function testGetSIUnits(): void
    {
        $units = $this->registry->getSIUnits(Length::instance());

        self::assertSame([], $units);
    }

    public function testFilter(): void
    {
        $this->registry->register(MetricLengthUnit::Meter, StubQuantityInterface::class);
        $this->registry->register(MetricLengthUnit::Kilometer, StubQuantityInterface::class);
        $this->registry->register(MetricLengthUnit::Centimeter, StubQuantityInterface::class);

        $filtered = $this->registry->filter(
            fn ($unit) => 'm' === $unit->symbol() || 'km' === $unit->symbol(),
        );

        self::assertCount(2, $filtered);
        self::assertContains(MetricLengthUnit::Meter, $filtered);
        self::assertContains(MetricLengthUnit::Kilometer, $filtered);
    }

    public function testGlobalReturnsSharedInstance(): void
    {
        $instance1 = UnitRegistry::global();
        $instance2 = UnitRegistry::global();

        self::assertSame($instance1, $instance2);
    }

    public function testSetGlobal(): void
    {
        $customRegistry = new UnitRegistry();
        UnitRegistry::setGlobal($customRegistry);

        $global = UnitRegistry::global();

        self::assertSame($customRegistry, $global);
    }

    public function testReset(): void
    {
        $instance1 = UnitRegistry::global();
        UnitRegistry::reset();
        $instance2 = UnitRegistry::global();

        self::assertNotSame($instance1, $instance2);
    }

    public function testMultipleUnitsFromDifferentEnums(): void
    {
        // This test demonstrates that WeakMap properly handles
        // enum cases from the same enum as distinct keys
        $this->registry->register(MetricLengthUnit::Meter, StubQuantityInterface::class);
        $this->registry->register(MetricLengthUnit::Kilometer, StubQuantityInterface::class);
        $this->registry->register(MetricLengthUnit::Centimeter, StubQuantityInterface::class);

        self::assertSame(StubQuantityInterface::class, $this->registry->getQuantityClass(MetricLengthUnit::Meter));
        self::assertSame(StubQuantityInterface::class, $this->registry->getQuantityClass(MetricLengthUnit::Kilometer));
        self::assertSame(StubQuantityInterface::class, $this->registry->getQuantityClass(MetricLengthUnit::Centimeter));
    }
}
