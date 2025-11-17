<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Formatter;

use Andante\Measurement\Formatter\FormatOptions;
use Andante\Measurement\Formatter\FormatStyle;
use PHPUnit\Framework\TestCase;

final class FormatOptionsTest extends TestCase
{
    public function testCreateReturnsDefaultOptions(): void
    {
        $options = FormatOptions::create();

        self::assertNull($options->getLocale());
        self::assertNull($options->getUnitLocale());
        self::assertNull($options->getPrecision());
        self::assertSame(',', $options->getThousandSeparator());
        self::assertSame('.', $options->getDecimalSeparator());
        self::assertSame(FormatStyle::Short, $options->getStyle());
    }

    public function testFromLocaleCreatesOptionsWithLocale(): void
    {
        $options = FormatOptions::fromLocale('it_IT');

        self::assertSame('it_IT', $options->getLocale());
        self::assertSame(FormatStyle::Short, $options->getStyle());
    }

    public function testWithLocaleReturnsNewInstance(): void
    {
        $original = FormatOptions::create();
        $modified = $original->withLocale('de_DE');

        self::assertNotSame($original, $modified);
        self::assertNull($original->getLocale());
        self::assertSame('de_DE', $modified->getLocale());
    }

    public function testWithPrecisionReturnsNewInstance(): void
    {
        $original = FormatOptions::create();
        $modified = $original->withPrecision(2);

        self::assertNotSame($original, $modified);
        self::assertNull($original->getPrecision());
        self::assertSame(2, $modified->getPrecision());
    }

    public function testWithPrecisionNull(): void
    {
        $options = FormatOptions::create()
            ->withPrecision(2)
            ->withPrecision(null);

        self::assertNull($options->getPrecision());
    }

    public function testWithThousandSeparatorReturnsNewInstance(): void
    {
        $original = FormatOptions::create();
        $modified = $original->withThousandSeparator('.');

        self::assertNotSame($original, $modified);
        self::assertSame(',', $original->getThousandSeparator());
        self::assertSame('.', $modified->getThousandSeparator());
    }

    public function testWithDecimalSeparatorReturnsNewInstance(): void
    {
        $original = FormatOptions::create();
        $modified = $original->withDecimalSeparator(',');

        self::assertNotSame($original, $modified);
        self::assertSame('.', $original->getDecimalSeparator());
        self::assertSame(',', $modified->getDecimalSeparator());
    }

    public function testWithStyleReturnsNewInstance(): void
    {
        $original = FormatOptions::create();
        $modified = $original->withStyle(FormatStyle::Long);

        self::assertNotSame($original, $modified);
        self::assertSame(FormatStyle::Short, $original->getStyle());
        self::assertSame(FormatStyle::Long, $modified->getStyle());
    }

    public function testItalianLocaleSeparators(): void
    {
        $options = FormatOptions::fromLocale('it_IT');

        // Italian uses . for thousands and , for decimals
        self::assertSame('.', $options->getThousandSeparator());
        self::assertSame(',', $options->getDecimalSeparator());
    }

    public function testGermanLocaleSeparators(): void
    {
        $options = FormatOptions::fromLocale('de_DE');

        // German uses . for thousands and , for decimals
        self::assertSame('.', $options->getThousandSeparator());
        self::assertSame(',', $options->getDecimalSeparator());
    }

    public function testFrenchLocaleSeparators(): void
    {
        $options = FormatOptions::fromLocale('fr_FR');

        // French uses space for thousands and , for decimals
        $thousandSep = $options->getThousandSeparator();
        self::assertTrue(\in_array($thousandSep, [' ', "\u{00A0}", "\u{202F}"], true));
        self::assertSame(',', $options->getDecimalSeparator());
    }

    public function testExplicitSeparatorsOverrideLocale(): void
    {
        $options = FormatOptions::fromLocale('it_IT')
            ->withThousandSeparator("'")
            ->withDecimalSeparator('.');

        self::assertSame("'", $options->getThousandSeparator());
        self::assertSame('.', $options->getDecimalSeparator());
    }

    public function testFluentBuilderChaining(): void
    {
        $options = FormatOptions::create()
            ->withLocale('en_US')
            ->withPrecision(2)
            ->withThousandSeparator(',')
            ->withDecimalSeparator('.')
            ->withStyle(FormatStyle::Long);

        self::assertSame('en_US', $options->getLocale());
        self::assertSame(2, $options->getPrecision());
        self::assertSame(',', $options->getThousandSeparator());
        self::assertSame('.', $options->getDecimalSeparator());
        self::assertSame(FormatStyle::Long, $options->getStyle());
    }

    // Unit locale tests

    public function testWithUnitLocaleReturnsNewInstance(): void
    {
        $original = FormatOptions::create();
        $modified = $original->withUnitLocale('de_DE');

        self::assertNotSame($original, $modified);
        self::assertNull($original->getUnitLocale());
        self::assertSame('de_DE', $modified->getUnitLocale());
    }

    public function testUnitLocaleDefaultsToNumberLocale(): void
    {
        $options = FormatOptions::fromLocale('it_IT');

        // When unitLocale is not set, getUnitLocale() returns the number locale
        self::assertSame('it_IT', $options->getUnitLocale());
    }

    public function testUnitLocaleCanBeDifferentFromNumberLocale(): void
    {
        $options = FormatOptions::fromLocale('it_IT')
            ->withUnitLocale('en');

        self::assertSame('it_IT', $options->getLocale());
        self::assertSame('en', $options->getUnitLocale());
    }

    public function testUnitLocalePreservedInBuilderChain(): void
    {
        $options = FormatOptions::create()
            ->withLocale('it_IT')
            ->withUnitLocale('en')
            ->withPrecision(2)
            ->withStyle(FormatStyle::Long);

        self::assertSame('it_IT', $options->getLocale());
        self::assertSame('en', $options->getUnitLocale());
        self::assertSame(2, $options->getPrecision());
        self::assertSame(FormatStyle::Long, $options->getStyle());
    }

    public function testChangingNumberLocaleDoesNotAffectUnitLocale(): void
    {
        $options = FormatOptions::create()
            ->withLocale('it_IT')
            ->withUnitLocale('en')
            ->withLocale('de_DE');

        // Number locale changed but unit locale remains
        self::assertSame('de_DE', $options->getLocale());
        self::assertSame('en', $options->getUnitLocale());
    }
}
