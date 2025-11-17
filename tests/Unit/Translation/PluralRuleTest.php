<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Translation;

use Andante\Measurement\Translation\PluralRule;
use PHPUnit\Framework\TestCase;

final class PluralRuleTest extends TestCase
{
    public function testSelectReturnsOneForExactlyOne(): void
    {
        self::assertSame(PluralRule::One, PluralRule::select(1, 'en'));
        self::assertSame(PluralRule::One, PluralRule::select(1.0, 'en'));
        self::assertSame(PluralRule::One, PluralRule::select('1', 'en'));
        self::assertSame(PluralRule::One, PluralRule::select('1.0', 'en'));
    }

    public function testSelectReturnsOneForNegativeOne(): void
    {
        self::assertSame(PluralRule::One, PluralRule::select(-1, 'en'));
        self::assertSame(PluralRule::One, PluralRule::select(-1.0, 'en'));
        self::assertSame(PluralRule::One, PluralRule::select('-1', 'en'));
    }

    public function testSelectReturnsOtherForZero(): void
    {
        self::assertSame(PluralRule::Other, PluralRule::select(0, 'en'));
        self::assertSame(PluralRule::Other, PluralRule::select('0', 'en'));
    }

    public function testSelectReturnsOtherForMultiple(): void
    {
        self::assertSame(PluralRule::Other, PluralRule::select(2, 'en'));
        self::assertSame(PluralRule::Other, PluralRule::select(5, 'en'));
        self::assertSame(PluralRule::Other, PluralRule::select(100, 'en'));
    }

    public function testSelectReturnsOtherForDecimalOne(): void
    {
        // 1.5 is not exactly 1
        self::assertSame(PluralRule::Other, PluralRule::select(1.5, 'en'));
        self::assertSame(PluralRule::Other, PluralRule::select('1.5', 'en'));
    }

    public function testSelectWorksWithItalianLocale(): void
    {
        self::assertSame(PluralRule::One, PluralRule::select(1, 'it_IT'));
        self::assertSame(PluralRule::Other, PluralRule::select(5, 'it_IT'));
    }

    public function testSelectWorksWithGermanLocale(): void
    {
        self::assertSame(PluralRule::One, PluralRule::select(1, 'de_DE'));
        self::assertSame(PluralRule::Other, PluralRule::select(2, 'de_DE'));
    }

    public function testAllCasesExist(): void
    {
        $cases = PluralRule::cases();

        self::assertCount(6, $cases);
        self::assertContains(PluralRule::Zero, $cases);
        self::assertContains(PluralRule::One, $cases);
        self::assertContains(PluralRule::Two, $cases);
        self::assertContains(PluralRule::Few, $cases);
        self::assertContains(PluralRule::Many, $cases);
        self::assertContains(PluralRule::Other, $cases);
    }

    public function testCasesHaveCorrectValues(): void
    {
        self::assertSame('zero', PluralRule::Zero->value);
        self::assertSame('one', PluralRule::One->value);
        self::assertSame('two', PluralRule::Two->value);
        self::assertSame('few', PluralRule::Few->value);
        self::assertSame('many', PluralRule::Many->value);
        self::assertSame('other', PluralRule::Other->value);
    }
}
