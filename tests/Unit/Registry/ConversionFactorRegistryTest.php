<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Registry;

use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Exception\InvalidArgumentException;
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use PHPUnit\Framework\TestCase;

final class ConversionFactorRegistryTest extends TestCase
{
    private ConversionFactorRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new ConversionFactorRegistry();
    }

    protected function tearDown(): void
    {
        ConversionFactorRegistry::reset();
    }

    public function testRegisterAndGetFactorToBase(): void
    {
        $factor = NumberFactory::create('1000');
        $this->registry->register(MetricLengthUnit::Kilometer, ConversionRule::factor($factor));

        $retrieved = $this->registry->getFactorToBase(MetricLengthUnit::Kilometer);

        self::assertSame($factor->value(), $retrieved->value());
    }

    public function testGetFactorToBaseThrowsExceptionForUnregisteredUnit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No conversion rule registered for unit "meter"');

        $this->registry->getFactorToBase(MetricLengthUnit::Meter);
    }

    public function testHasReturnsTrueForRegisteredUnit(): void
    {
        $factor = NumberFactory::create('1');
        $this->registry->register(MetricLengthUnit::Meter, ConversionRule::factor($factor));

        self::assertTrue($this->registry->has(MetricLengthUnit::Meter));
    }

    public function testHasReturnsFalseForUnregisteredUnit(): void
    {
        self::assertFalse($this->registry->has(MetricLengthUnit::Meter));
    }

    public function testUnregister(): void
    {
        $factor = NumberFactory::create('1');
        $this->registry->register(MetricLengthUnit::Meter, ConversionRule::factor($factor));
        self::assertTrue($this->registry->has(MetricLengthUnit::Meter));

        $this->registry->unregister(MetricLengthUnit::Meter);

        self::assertFalse($this->registry->has(MetricLengthUnit::Meter));
    }

    public function testRegisterMultipleUnits(): void
    {
        $meterFactor = NumberFactory::create('1');
        $kilometerFactor = NumberFactory::create('1000');
        $centimeterFactor = NumberFactory::create('0.01');

        $this->registry->register(MetricLengthUnit::Meter, ConversionRule::factor($meterFactor));
        $this->registry->register(MetricLengthUnit::Kilometer, ConversionRule::factor($kilometerFactor));
        $this->registry->register(MetricLengthUnit::Centimeter, ConversionRule::factor($centimeterFactor));

        self::assertSame($meterFactor->value(), $this->registry->getFactorToBase(MetricLengthUnit::Meter)->value());
        self::assertSame($kilometerFactor->value(), $this->registry->getFactorToBase(MetricLengthUnit::Kilometer)->value());
        self::assertSame($centimeterFactor->value(), $this->registry->getFactorToBase(MetricLengthUnit::Centimeter)->value());
    }

    public function testOverwriteExistingFactor(): void
    {
        $oldFactor = NumberFactory::create('100');
        $newFactor = NumberFactory::create('1000');

        $this->registry->register(MetricLengthUnit::Kilometer, ConversionRule::factor($oldFactor));
        $this->registry->register(MetricLengthUnit::Kilometer, ConversionRule::factor($newFactor));

        $retrieved = $this->registry->getFactorToBase(MetricLengthUnit::Kilometer);

        self::assertSame($newFactor->value(), $retrieved->value());
        self::assertNotSame($oldFactor->value(), $retrieved->value());
    }

    public function testGlobalReturnsSharedInstance(): void
    {
        $instance1 = ConversionFactorRegistry::global();
        $instance2 = ConversionFactorRegistry::global();

        self::assertSame($instance1, $instance2);
    }

    public function testSetGlobal(): void
    {
        $customRegistry = new ConversionFactorRegistry();
        ConversionFactorRegistry::setGlobal($customRegistry);

        $global = ConversionFactorRegistry::global();

        self::assertSame($customRegistry, $global);
    }

    public function testReset(): void
    {
        $instance1 = ConversionFactorRegistry::global();
        ConversionFactorRegistry::reset();
        $instance2 = ConversionFactorRegistry::global();

        self::assertNotSame($instance1, $instance2);
    }

    public function testWeakMapHandlesEnumCasesAsDistinctKeys(): void
    {
        // Verify that different enum cases are treated as distinct keys
        $meterFactor = NumberFactory::create('1');
        $kilometerFactor = NumberFactory::create('1000');

        $this->registry->register(MetricLengthUnit::Meter, ConversionRule::factor($meterFactor));
        $this->registry->register(MetricLengthUnit::Kilometer, ConversionRule::factor($kilometerFactor));

        self::assertNotSame(
            $this->registry->getFactorToBase(MetricLengthUnit::Meter),
            $this->registry->getFactorToBase(MetricLengthUnit::Kilometer),
        );
    }
}
