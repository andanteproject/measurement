<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Quantity\Time;

use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Time\Day;
use Andante\Measurement\Quantity\Time\Hour;
use Andante\Measurement\Quantity\Time\Microsecond;
use Andante\Measurement\Quantity\Time\Millisecond;
use Andante\Measurement\Quantity\Time\Minute;
use Andante\Measurement\Quantity\Time\Nanosecond;
use Andante\Measurement\Quantity\Time\Second;
use Andante\Measurement\Quantity\Time\Time;
use Andante\Measurement\Quantity\Time\Week;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Time\TimeUnit;
use PHPUnit\Framework\TestCase;

/**
 * Tests for time quantity conversions and DateInterval integration.
 */
final class TimeConversionTest extends TestCase
{
    protected function setUp(): void
    {
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
    }

    protected function tearDown(): void
    {
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
    }

    // ========================================================================
    // Basic Conversions
    // ========================================================================

    public function testSecondToMinute(): void
    {
        $seconds = Second::of(NumberFactory::create('120'));
        $minutes = $seconds->to(TimeUnit::Minute);

        self::assertEqualsWithDelta(2.0, (float) $minutes->getValue()->value(), 0.001);
    }

    public function testMinuteToSecond(): void
    {
        $minutes = Minute::of(NumberFactory::create('5'));
        $seconds = $minutes->to(TimeUnit::Second);

        self::assertEqualsWithDelta(300.0, (float) $seconds->getValue()->value(), 0.001);
    }

    public function testHourToMinute(): void
    {
        $hours = Hour::of(NumberFactory::create('2'));
        $minutes = $hours->to(TimeUnit::Minute);

        self::assertEqualsWithDelta(120.0, (float) $minutes->getValue()->value(), 0.001);
    }

    public function testDayToHour(): void
    {
        $days = Day::of(NumberFactory::create('1'));
        $hours = $days->to(TimeUnit::Hour);

        self::assertEqualsWithDelta(24.0, (float) $hours->getValue()->value(), 0.001);
    }

    public function testWeekToDay(): void
    {
        $weeks = Week::of(NumberFactory::create('1'));
        $days = $weeks->to(TimeUnit::Day);

        self::assertEqualsWithDelta(7.0, (float) $days->getValue()->value(), 0.001);
    }

    public function testMillisecondToSecond(): void
    {
        $milliseconds = Millisecond::of(NumberFactory::create('1500'));
        $seconds = $milliseconds->to(TimeUnit::Second);

        self::assertEqualsWithDelta(1.5, (float) $seconds->getValue()->value(), 0.001);
    }

    public function testMicrosecondToMillisecond(): void
    {
        $microseconds = Microsecond::of(NumberFactory::create('2500'));
        $milliseconds = $microseconds->to(TimeUnit::Millisecond);

        self::assertEqualsWithDelta(2.5, (float) $milliseconds->getValue()->value(), 0.001);
    }

    public function testNanosecondToMicrosecond(): void
    {
        $nanoseconds = Nanosecond::of(NumberFactory::create('5000'));
        $microseconds = $nanoseconds->to(TimeUnit::Microsecond);

        self::assertEqualsWithDelta(5.0, (float) $microseconds->getValue()->value(), 0.001);
    }

    // ========================================================================
    // Generic Time class
    // ========================================================================

    public function testGenericTimeConversion(): void
    {
        $time = Time::from(NumberFactory::create('3600'), TimeUnit::Second);
        $hours = $time->to(TimeUnit::Hour);

        self::assertEqualsWithDelta(1.0, (float) $hours->getValue()->value(), 0.001);
    }

    // ========================================================================
    // DateInterval Integration - toPhpDateInterval()
    // ========================================================================

    public function testSecondToPhpDateInterval(): void
    {
        $seconds = Second::of(NumberFactory::create('3661')); // 1h 1m 1s
        $interval = $seconds->toPhpDateInterval();

        self::assertSame(0, $interval->d);
        self::assertSame(1, $interval->h);
        self::assertSame(1, $interval->i);
        self::assertSame(1, $interval->s);
        self::assertSame(0, $interval->invert);
    }

    public function testHourToPhpDateInterval(): void
    {
        $hours = Hour::of(NumberFactory::create('2.5')); // 2h 30m
        $interval = $hours->toPhpDateInterval();

        self::assertSame(0, $interval->d);
        self::assertSame(2, $interval->h);
        self::assertSame(30, $interval->i);
        self::assertSame(0, $interval->s);
    }

    public function testDayToPhpDateInterval(): void
    {
        $days = Day::of(NumberFactory::create('1.5')); // 1d 12h
        $interval = $days->toPhpDateInterval();

        self::assertSame(1, $interval->d);
        self::assertSame(12, $interval->h);
        self::assertSame(0, $interval->i);
        self::assertSame(0, $interval->s);
    }

    public function testMillisecondToPhpDateInterval(): void
    {
        $milliseconds = Millisecond::of(NumberFactory::create('1500')); // 1.5s
        $interval = $milliseconds->toPhpDateInterval();

        self::assertSame(0, $interval->d);
        self::assertSame(0, $interval->h);
        self::assertSame(0, $interval->i);
        self::assertSame(1, $interval->s);
        self::assertEqualsWithDelta(0.5, $interval->f, 0.001);
    }

    public function testMicrosecondToPhpDateInterval(): void
    {
        $microseconds = Microsecond::of(NumberFactory::create('1500000')); // 1.5s
        $interval = $microseconds->toPhpDateInterval();

        self::assertSame(1, $interval->s);
        self::assertEqualsWithDelta(0.5, $interval->f, 0.001);
    }

    public function testWeekToPhpDateInterval(): void
    {
        $weeks = Week::of(NumberFactory::create('1'));
        $interval = $weeks->toPhpDateInterval();

        self::assertSame(7, $interval->d);
        self::assertSame(0, $interval->h);
        self::assertSame(0, $interval->i);
        self::assertSame(0, $interval->s);
    }

    public function testNegativeTimeToPhpDateInterval(): void
    {
        $seconds = Second::of(NumberFactory::create('-3600')); // -1h
        $interval = $seconds->toPhpDateInterval();

        self::assertSame(1, $interval->h);
        self::assertSame(1, $interval->invert);
    }

    // ========================================================================
    // DateInterval Integration - ofPhpDateInterval()
    // ========================================================================

    public function testSecondOfPhpDateInterval(): void
    {
        $interval = new \DateInterval('PT1H30M45S');
        $seconds = Second::ofPhpDateInterval($interval);

        // 1h = 3600s, 30m = 1800s, 45s = 45s => 5445s
        self::assertEqualsWithDelta(5445.0, (float) $seconds->getValue()->value(), 0.001);
    }

    public function testMinuteOfPhpDateInterval(): void
    {
        $interval = new \DateInterval('PT2H');
        $minutes = Minute::ofPhpDateInterval($interval);

        self::assertEqualsWithDelta(120.0, (float) $minutes->getValue()->value(), 0.001);
    }

    public function testHourOfPhpDateInterval(): void
    {
        $interval = new \DateInterval('P1D');
        $hours = Hour::ofPhpDateInterval($interval);

        self::assertEqualsWithDelta(24.0, (float) $hours->getValue()->value(), 0.001);
    }

    public function testDayOfPhpDateInterval(): void
    {
        $interval = new \DateInterval('P7D');
        $days = Day::ofPhpDateInterval($interval);

        self::assertEqualsWithDelta(7.0, (float) $days->getValue()->value(), 0.001);
    }

    public function testWeekOfPhpDateInterval(): void
    {
        $interval = new \DateInterval('P14D');
        $weeks = Week::ofPhpDateInterval($interval);

        self::assertEqualsWithDelta(2.0, (float) $weeks->getValue()->value(), 0.001);
    }

    public function testMillisecondOfPhpDateInterval(): void
    {
        $interval = new \DateInterval('PT1S');
        $interval->f = 0.5; // 500 milliseconds
        $milliseconds = Millisecond::ofPhpDateInterval($interval);

        self::assertEqualsWithDelta(1500.0, (float) $milliseconds->getValue()->value(), 0.001);
    }

    public function testMicrosecondOfPhpDateInterval(): void
    {
        $interval = new \DateInterval('PT1S');
        $microseconds = Microsecond::ofPhpDateInterval($interval);

        self::assertEqualsWithDelta(1000000.0, (float) $microseconds->getValue()->value(), 0.001);
    }

    public function testNanosecondOfPhpDateInterval(): void
    {
        $interval = new \DateInterval('PT0S');
        $interval->f = 0.001; // 1 millisecond = 1,000,000 nanoseconds
        $nanoseconds = Nanosecond::ofPhpDateInterval($interval);

        self::assertEqualsWithDelta(1000000.0, (float) $nanoseconds->getValue()->value(), 0.001);
    }

    public function testNegativeDateIntervalToSecond(): void
    {
        $interval = new \DateInterval('PT1H');
        $interval->invert = 1; // negative
        $seconds = Second::ofPhpDateInterval($interval);

        self::assertEqualsWithDelta(-3600.0, (float) $seconds->getValue()->value(), 0.001);
    }

    // ========================================================================
    // Round-trip conversions
    // ========================================================================

    public function testSecondRoundTripThroughDateInterval(): void
    {
        $original = Second::of(NumberFactory::create('7265')); // 2h 1m 5s
        $interval = $original->toPhpDateInterval();
        $restored = Second::ofPhpDateInterval($interval);

        self::assertEqualsWithDelta(
            (float) $original->getValue()->value(),
            (float) $restored->getValue()->value(),
            0.001,
        );
    }

    public function testMinuteRoundTripThroughDateInterval(): void
    {
        $original = Minute::of(NumberFactory::create('90')); // 1h 30m
        $interval = $original->toPhpDateInterval();
        $restored = Minute::ofPhpDateInterval($interval);

        self::assertEqualsWithDelta(
            (float) $original->getValue()->value(),
            (float) $restored->getValue()->value(),
            0.001,
        );
    }

    public function testHourRoundTripThroughDateInterval(): void
    {
        $original = Hour::of(NumberFactory::create('25.5')); // 1d 1h 30m
        $interval = $original->toPhpDateInterval();
        $restored = Hour::ofPhpDateInterval($interval);

        self::assertEqualsWithDelta(
            (float) $original->getValue()->value(),
            (float) $restored->getValue()->value(),
            0.001,
        );
    }

    public function testTimeRoundTrip(): void
    {
        $originalSeconds = Second::of(NumberFactory::create('3600'));
        $hours = $originalSeconds->to(TimeUnit::Hour);
        $backToSeconds = Hour::of($hours->getValue())->to(TimeUnit::Second);

        self::assertEqualsWithDelta(
            (float) $originalSeconds->getValue()->value(),
            (float) $backToSeconds->getValue()->value(),
            0.001,
        );
    }
}
