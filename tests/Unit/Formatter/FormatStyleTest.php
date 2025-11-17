<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Formatter;

use Andante\Measurement\Formatter\FormatStyle;
use PHPUnit\Framework\TestCase;

final class FormatStyleTest extends TestCase
{
    public function testShortExists(): void
    {
        self::assertSame('Short', FormatStyle::Short->name);
    }

    public function testLongExists(): void
    {
        self::assertSame('Long', FormatStyle::Long->name);
    }

    public function testNarrowExists(): void
    {
        self::assertSame('Narrow', FormatStyle::Narrow->name);
    }

    public function testValueOnlyExists(): void
    {
        self::assertSame('ValueOnly', FormatStyle::ValueOnly->name);
    }

    public function testUnitSymbolOnlyExists(): void
    {
        self::assertSame('UnitSymbolOnly', FormatStyle::UnitSymbolOnly->name);
    }

    public function testUnitNameOnlyExists(): void
    {
        self::assertSame('UnitNameOnly', FormatStyle::UnitNameOnly->name);
    }

    public function testAllCasesReturnsAllStyles(): void
    {
        $cases = FormatStyle::cases();

        self::assertCount(6, $cases);
        self::assertContains(FormatStyle::Short, $cases);
        self::assertContains(FormatStyle::Long, $cases);
        self::assertContains(FormatStyle::Narrow, $cases);
        self::assertContains(FormatStyle::ValueOnly, $cases);
        self::assertContains(FormatStyle::UnitSymbolOnly, $cases);
        self::assertContains(FormatStyle::UnitNameOnly, $cases);
    }
}
