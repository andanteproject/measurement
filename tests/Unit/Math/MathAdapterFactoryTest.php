<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Math;

use Andante\Measurement\Math\Adapter\BCMathAdapter;
use Andante\Measurement\Math\Adapter\BrickMathAdapter;
use Andante\Measurement\Math\MathAdapterFactory;
use PHPUnit\Framework\TestCase;

/**
 * Test case for MathAdapterFactory.
 */
final class MathAdapterFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset factory state before each test
        MathAdapterFactory::reset();
    }

    protected function tearDown(): void
    {
        // Reset factory state after each test
        MathAdapterFactory::reset();
    }

    public function testAutoDetectsBrickMath(): void
    {
        $adapter = MathAdapterFactory::getAdapter();

        // brick/math is available in our test environment
        self::assertInstanceOf(BrickMathAdapter::class, $adapter);
        self::assertTrue(MathAdapterFactory::isAutoDetected());
        self::assertFalse(MathAdapterFactory::hasAdapter());
    }

    public function testExplicitlySetAdapter(): void
    {
        $customAdapter = new BCMathAdapter();

        MathAdapterFactory::setAdapter($customAdapter);

        $adapter = MathAdapterFactory::getAdapter();

        self::assertSame($customAdapter, $adapter);
        self::assertFalse(MathAdapterFactory::isAutoDetected());
        self::assertTrue(MathAdapterFactory::hasAdapter());
    }

    public function testExplicitAdapterOverridesAutoDetection(): void
    {
        // First call auto-detects
        $autoDetected = MathAdapterFactory::getAdapter();
        self::assertInstanceOf(BrickMathAdapter::class, $autoDetected);

        // Reset and set explicit adapter
        MathAdapterFactory::reset();
        $explicit = new BCMathAdapter();
        MathAdapterFactory::setAdapter($explicit);

        $adapter = MathAdapterFactory::getAdapter();

        self::assertSame($explicit, $adapter);
    }

    public function testGetAdapterReturnsSameInstanceMultipleTimes(): void
    {
        $adapter1 = MathAdapterFactory::getAdapter();
        $adapter2 = MathAdapterFactory::getAdapter();

        self::assertSame($adapter1, $adapter2);
    }

    public function testResetClearsAdapter(): void
    {
        MathAdapterFactory::getAdapter();
        self::assertTrue(MathAdapterFactory::isAutoDetected());

        MathAdapterFactory::reset();

        self::assertFalse(MathAdapterFactory::isAutoDetected());
        self::assertFalse(MathAdapterFactory::hasAdapter());

        // Getting adapter again will auto-detect
        $adapter = MathAdapterFactory::getAdapter();
        self::assertInstanceOf(BrickMathAdapter::class, $adapter);
    }

    public function testHasAdapterReturnsFalseForAutoDetected(): void
    {
        MathAdapterFactory::getAdapter(); // Auto-detects

        self::assertFalse(MathAdapterFactory::hasAdapter());
        self::assertTrue(MathAdapterFactory::isAutoDetected());
    }

    public function testHasAdapterReturnsTrueForExplicitlySet(): void
    {
        MathAdapterFactory::setAdapter(new BCMathAdapter());

        self::assertTrue(MathAdapterFactory::hasAdapter());
        self::assertFalse(MathAdapterFactory::isAutoDetected());
    }
}
