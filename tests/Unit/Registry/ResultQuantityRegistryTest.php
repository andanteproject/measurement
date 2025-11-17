<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Registry;

use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Exception\InvalidArgumentException;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Mock quantity class that implements QuantityFactoryInterface.
 */
class MockAreaQuantity implements QuantityInterface, QuantityFactoryInterface
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

/**
 * Mock metric area quantity for testing hierarchy.
 */
class MockMetricAreaQuantity extends MockAreaQuantity
{
}

/**
 * Mock imperial area quantity for testing hierarchy.
 */
class MockImperialAreaQuantity extends MockAreaQuantity
{
}

/**
 * Mock base length quantity for testing.
 */
class MockLengthForDerived implements QuantityInterface
{
    public function getValue(): NumberInterface
    {
        throw new \RuntimeException('Not implemented');
    }

    public function getUnit(): UnitInterface
    {
        throw new \RuntimeException('Not implemented');
    }
}

/**
 * Mock metric length quantity.
 */
class MockMetricLengthForDerived extends MockLengthForDerived
{
}

/**
 * Mock imperial length quantity.
 */
class MockImperialLengthForDerived extends MockLengthForDerived
{
}

final class ResultQuantityRegistryTest extends TestCase
{
    private ResultQuantityRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new ResultQuantityRegistry();
    }

    protected function tearDown(): void
    {
        ResultQuantityRegistry::reset();
    }

    public function testRegisterAndRetrieve(): void
    {
        $areaFormula = DimensionalFormula::length()->power(2);

        $this->registry->register(
            MockMetricLengthForDerived::class,
            $areaFormula,
            MockMetricAreaQuantity::class,
        );

        $result = $this->registry->getQuantityClass(MockMetricLengthForDerived::class, $areaFormula);

        self::assertSame(MockMetricAreaQuantity::class, $result);
    }

    public function testHierarchyFallback(): void
    {
        $areaFormula = DimensionalFormula::length()->power(2);

        // Register only for the base class
        $this->registry->register(
            MockLengthForDerived::class,
            $areaFormula,
            MockAreaQuantity::class,
        );

        // Should find mapping through parent class
        $result = $this->registry->getQuantityClass(MockMetricLengthForDerived::class, $areaFormula);

        self::assertSame(MockAreaQuantity::class, $result);
    }

    public function testSpecificMappingTakesPrecedence(): void
    {
        $areaFormula = DimensionalFormula::length()->power(2);

        // Register for base class first
        $this->registry->register(
            MockLengthForDerived::class,
            $areaFormula,
            MockAreaQuantity::class,
        );

        // Register specific mapping for metric
        $this->registry->register(
            MockMetricLengthForDerived::class,
            $areaFormula,
            MockMetricAreaQuantity::class,
        );

        // Metric should get MetricArea
        $metricResult = $this->registry->getQuantityClass(MockMetricLengthForDerived::class, $areaFormula);
        self::assertSame(MockMetricAreaQuantity::class, $metricResult);

        // Imperial should fall back to Area (via parent class)
        $imperialResult = $this->registry->getQuantityClass(MockImperialLengthForDerived::class, $areaFormula);
        self::assertSame(MockAreaQuantity::class, $imperialResult);
    }

    public function testGenericMappingFallback(): void
    {
        $areaFormula = DimensionalFormula::length()->power(2);

        // Register only generic mapping
        $this->registry->registerGeneric($areaFormula, MockAreaQuantity::class);

        // Any source class should get the generic mapping
        $result = $this->registry->getQuantityClass(MockMetricLengthForDerived::class, $areaFormula);

        self::assertSame(MockAreaQuantity::class, $result);
    }

    public function testSpecificMappingTakesPrecedenceOverGeneric(): void
    {
        $areaFormula = DimensionalFormula::length()->power(2);

        // Register generic first
        $this->registry->registerGeneric($areaFormula, MockAreaQuantity::class);

        // Register specific for metric
        $this->registry->register(
            MockMetricLengthForDerived::class,
            $areaFormula,
            MockMetricAreaQuantity::class,
        );

        // Metric should get MetricArea
        $metricResult = $this->registry->getQuantityClass(MockMetricLengthForDerived::class, $areaFormula);
        self::assertSame(MockMetricAreaQuantity::class, $metricResult);

        // Unknown class should get generic Area
        $genericResult = $this->registry->getQuantityClass(\stdClass::class, $areaFormula);
        self::assertSame(MockAreaQuantity::class, $genericResult);
    }

    public function testDifferentFormulasAreSeparate(): void
    {
        $areaFormula = DimensionalFormula::length()->power(2);  // L²
        $volumeFormula = DimensionalFormula::length()->power(3); // L³

        $this->registry->register(
            MockMetricLengthForDerived::class,
            $areaFormula,
            MockMetricAreaQuantity::class,
        );

        $this->registry->register(
            MockMetricLengthForDerived::class,
            $volumeFormula,
            MockImperialAreaQuantity::class, // Using this as a stand-in for volume
        );

        $areaResult = $this->registry->getQuantityClass(MockMetricLengthForDerived::class, $areaFormula);
        $volumeResult = $this->registry->getQuantityClass(MockMetricLengthForDerived::class, $volumeFormula);

        self::assertSame(MockMetricAreaQuantity::class, $areaResult);
        self::assertSame(MockImperialAreaQuantity::class, $volumeResult);
    }

    public function testHasReturnsTrueForExistingMapping(): void
    {
        $areaFormula = DimensionalFormula::length()->power(2);

        $this->registry->register(
            MockMetricLengthForDerived::class,
            $areaFormula,
            MockMetricAreaQuantity::class,
        );

        self::assertTrue($this->registry->has(MockMetricLengthForDerived::class, $areaFormula));
    }

    public function testHasReturnsFalseForMissingMapping(): void
    {
        $areaFormula = DimensionalFormula::length()->power(2);

        self::assertFalse($this->registry->has(MockMetricLengthForDerived::class, $areaFormula));
    }

    public function testHasReturnsTrueForHierarchyFallback(): void
    {
        $areaFormula = DimensionalFormula::length()->power(2);

        $this->registry->register(
            MockLengthForDerived::class,
            $areaFormula,
            MockAreaQuantity::class,
        );

        // Should return true because of hierarchy fallback
        self::assertTrue($this->registry->has(MockMetricLengthForDerived::class, $areaFormula));
    }

    public function testThrowsExceptionForMissingMapping(): void
    {
        $areaFormula = DimensionalFormula::length()->power(2);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No result quantity registered');

        $this->registry->getQuantityClass(MockMetricLengthForDerived::class, $areaFormula);
    }

    public function testThrowsExceptionForInvalidResultClass(): void
    {
        $areaFormula = DimensionalFormula::length()->power(2);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must implement');

        $this->registry->register(
            MockMetricLengthForDerived::class,
            $areaFormula,
            \stdClass::class,
        );
    }

    public function testThrowsExceptionForInvalidGenericResultClass(): void
    {
        $areaFormula = DimensionalFormula::length()->power(2);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must implement');

        $this->registry->registerGeneric(
            $areaFormula,
            \stdClass::class,
        );
    }

    public function testGlobalRegistryIsSingleton(): void
    {
        $registry1 = ResultQuantityRegistry::global();
        $registry2 = ResultQuantityRegistry::global();

        self::assertSame($registry1, $registry2);
    }

    public function testResetClearsGlobalRegistry(): void
    {
        $registry1 = ResultQuantityRegistry::global();
        ResultQuantityRegistry::reset();
        $registry2 = ResultQuantityRegistry::global();

        self::assertNotSame($registry1, $registry2);
    }

    public function testSetGlobalReplacesInstance(): void
    {
        $customRegistry = new ResultQuantityRegistry();
        ResultQuantityRegistry::setGlobal($customRegistry);

        self::assertSame($customRegistry, ResultQuantityRegistry::global());
    }

    public function testComplexFormulas(): void
    {
        // Velocity: L¹T⁻¹
        $velocityFormula = new DimensionalFormula(length: 1, time: -1);

        // Acceleration: L¹T⁻²
        $accelerationFormula = new DimensionalFormula(length: 1, time: -2);

        // Force: L¹M¹T⁻² (mass × acceleration)
        $forceFormula = new DimensionalFormula(length: 1, mass: 1, time: -2);

        $this->registry->registerGeneric($velocityFormula, MockAreaQuantity::class);
        $this->registry->registerGeneric($accelerationFormula, MockMetricAreaQuantity::class);
        $this->registry->registerGeneric($forceFormula, MockImperialAreaQuantity::class);

        self::assertSame(
            MockAreaQuantity::class,
            $this->registry->getQuantityClass(\stdClass::class, $velocityFormula),
        );
        self::assertSame(
            MockMetricAreaQuantity::class,
            $this->registry->getQuantityClass(\stdClass::class, $accelerationFormula),
        );
        self::assertSame(
            MockImperialAreaQuantity::class,
            $this->registry->getQuantityClass(\stdClass::class, $forceFormula),
        );
    }
}
