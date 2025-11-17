<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Parser;

use Andante\Measurement\Parser\ParseOptions;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use PHPUnit\Framework\TestCase;

final class ParseOptionsTest extends TestCase
{
    public function testCreateReturnsDefaultOptions(): void
    {
        $options = ParseOptions::create();

        self::assertNull($options->getLocale());
        self::assertNull($options->getDefaultUnit());
        self::assertSame(',', $options->getThousandSeparator());
        self::assertSame('.', $options->getDecimalSeparator());
    }

    public function testFromLocaleCreatesOptionsWithLocale(): void
    {
        $options = ParseOptions::fromLocale('it_IT');

        self::assertSame('it_IT', $options->getLocale());
    }

    public function testWithLocaleReturnsNewInstance(): void
    {
        $original = ParseOptions::create();
        $modified = $original->withLocale('de_DE');

        self::assertNotSame($original, $modified);
        self::assertNull($original->getLocale());
        self::assertSame('de_DE', $modified->getLocale());
    }

    public function testWithThousandSeparatorReturnsNewInstance(): void
    {
        $original = ParseOptions::create();
        $modified = $original->withThousandSeparator('.');

        self::assertNotSame($original, $modified);
        self::assertSame(',', $original->getThousandSeparator());
        self::assertSame('.', $modified->getThousandSeparator());
    }

    public function testWithDecimalSeparatorReturnsNewInstance(): void
    {
        $original = ParseOptions::create();
        $modified = $original->withDecimalSeparator(',');

        self::assertNotSame($original, $modified);
        self::assertSame('.', $original->getDecimalSeparator());
        self::assertSame(',', $modified->getDecimalSeparator());
    }

    public function testWithDefaultUnitReturnsNewInstance(): void
    {
        $original = ParseOptions::create();
        $modified = $original->withDefaultUnit(MetricLengthUnit::Meter);

        self::assertNotSame($original, $modified);
        self::assertNull($original->getDefaultUnit());
        self::assertSame(MetricLengthUnit::Meter, $modified->getDefaultUnit());
    }

    public function testItalianLocaleSeparators(): void
    {
        $options = ParseOptions::fromLocale('it_IT');

        // Italian uses . for thousands and , for decimals
        self::assertSame('.', $options->getThousandSeparator());
        self::assertSame(',', $options->getDecimalSeparator());
    }

    public function testGermanLocaleSeparators(): void
    {
        $options = ParseOptions::fromLocale('de_DE');

        // German uses . for thousands and , for decimals
        self::assertSame('.', $options->getThousandSeparator());
        self::assertSame(',', $options->getDecimalSeparator());
    }

    public function testFrenchLocaleSeparators(): void
    {
        $options = ParseOptions::fromLocale('fr_FR');

        // French uses space for thousands and , for decimals
        // Note: with intl extension, this would be non-breaking space
        $thousandSep = $options->getThousandSeparator();
        self::assertTrue(\in_array($thousandSep, [' ', "\u{00A0}", "\u{202F}"], true));
        self::assertSame(',', $options->getDecimalSeparator());
    }

    public function testExplicitSeparatorsOverrideLocale(): void
    {
        $options = ParseOptions::fromLocale('it_IT')
            ->withThousandSeparator("'")
            ->withDecimalSeparator('.');

        self::assertSame("'", $options->getThousandSeparator());
        self::assertSame('.', $options->getDecimalSeparator());
    }

    public function testFluentBuilderChaining(): void
    {
        $options = ParseOptions::create()
            ->withLocale('en_US')
            ->withThousandSeparator(',')
            ->withDecimalSeparator('.')
            ->withDefaultUnit(MetricLengthUnit::Kilometer);

        self::assertSame('en_US', $options->getLocale());
        self::assertSame(',', $options->getThousandSeparator());
        self::assertSame('.', $options->getDecimalSeparator());
        self::assertSame(MetricLengthUnit::Kilometer, $options->getDefaultUnit());
    }
}
