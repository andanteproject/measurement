<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Math;

use Andante\Measurement\Math\Adapter\BrickMathAdapter;
use Andante\Measurement\Math\MathAdapterFactory;
use Andante\Measurement\Math\Number;
use Andante\Measurement\Math\NumberFactory;
use PHPUnit\Framework\TestCase;

/**
 * Test case for NumberFactory.
 */
final class NumberFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset and configure adapter for predictable tests
        MathAdapterFactory::reset();
        MathAdapterFactory::setAdapter(new BrickMathAdapter());
    }

    protected function tearDown(): void
    {
        MathAdapterFactory::reset();
    }

    public function testCreateFromString(): void
    {
        $number = NumberFactory::create('10.5');

        self::assertInstanceOf(Number::class, $number);
        self::assertEquals('10.5', $number->value());
    }

    public function testCreateFromInt(): void
    {
        $number = NumberFactory::create(42);

        self::assertInstanceOf(Number::class, $number);
        self::assertEquals('42', $number->value());
    }

    public function testCreateFromFloat(): void
    {
        $number = NumberFactory::create(10.5);

        self::assertInstanceOf(Number::class, $number);
        self::assertEquals('10.5', $number->value());
    }

    public function testOfIsAliasForCreate(): void
    {
        $created = NumberFactory::create('5.5');
        $of = NumberFactory::of('5.5');

        self::assertEquals($created->value(), $of->value());
    }

    public function testZeroCreatesZeroValue(): void
    {
        $zero = NumberFactory::zero();

        self::assertInstanceOf(Number::class, $zero);
        self::assertEquals('0', $zero->value());
        self::assertTrue($zero->isZero());
    }

    public function testOneCreatesOneValue(): void
    {
        $one = NumberFactory::one();

        self::assertInstanceOf(Number::class, $one);
        self::assertEquals('1', $one->value());
    }

    public function testCreatedNumbersUsesConfiguredAdapter(): void
    {
        // The factory should use the configured adapter
        $number = NumberFactory::create('10');

        // Test that math operations work (delegating to the adapter)
        $result = $number->add(NumberFactory::create('5'));

        self::assertEquals('15', $result->value());
    }

    public function testMultipleCallsCreateIndependentInstances(): void
    {
        $a = NumberFactory::create('10');
        $b = NumberFactory::create('10');

        // They should be equal in value
        self::assertEquals($a->value(), $b->value());

        // But independent instances (immutability test)
        $a->add(NumberFactory::create('5'));

        // Original 'a' is unchanged
        self::assertEquals('10', $a->value());
        self::assertEquals('10', $b->value());
    }
}
