<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Parser;

use Andante\Measurement\Exception\ParsingException;
use Andante\Measurement\Parser\NumberParser;
use PHPUnit\Framework\TestCase;

final class NumberParserTest extends TestCase
{
    private NumberParser $parser;

    protected function setUp(): void
    {
        $this->parser = new NumberParser();
    }

    public function testParseSimpleInteger(): void
    {
        $result = $this->parser->parse('123', ',', '.');

        self::assertSame('123', $result->value());
    }

    public function testParseSimpleDecimal(): void
    {
        $result = $this->parser->parse('123.45', ',', '.');

        self::assertSame('123.45', $result->value());
    }

    public function testParseWithThousandSeparator(): void
    {
        $result = $this->parser->parse('1,234,567', ',', '.');

        self::assertSame('1234567', $result->value());
    }

    public function testParseWithThousandAndDecimal(): void
    {
        $result = $this->parser->parse('1,234.56', ',', '.');

        self::assertSame('1234.56', $result->value());
    }

    public function testParseItalianFormat(): void
    {
        // Italian: 1.234,56 (. for thousands, , for decimal)
        $result = $this->parser->parse('1.234,56', '.', ',');

        self::assertSame('1234.56', $result->value());
    }

    public function testParseGermanFormat(): void
    {
        // German: 1.234,56 (. for thousands, , for decimal)
        $result = $this->parser->parse('1.234.567,89', '.', ',');

        self::assertSame('1234567.89', $result->value());
    }

    public function testParseSwissFormat(): void
    {
        // Swiss: 1'234.56 (' for thousands, . for decimal)
        $result = $this->parser->parse("1'234.56", "'", '.');

        self::assertSame('1234.56', $result->value());
    }

    public function testParseFrenchFormat(): void
    {
        // French: 1 234,56 (space for thousands, , for decimal)
        $result = $this->parser->parse('1 234,56', ' ', ',');

        self::assertSame('1234.56', $result->value());
    }

    public function testParsePositiveNumber(): void
    {
        $result = $this->parser->parse('+123.45', ',', '.');

        // BCMath normalizes +123.45 to 123.45
        self::assertSame('123.45', $result->value());
    }

    public function testParseNegativeNumber(): void
    {
        $result = $this->parser->parse('-123.45', ',', '.');

        self::assertSame('-123.45', $result->value());
    }

    public function testParseWithLeadingWhitespace(): void
    {
        $result = $this->parser->parse('  123.45', ',', '.');

        self::assertSame('123.45', $result->value());
    }

    public function testParseWithTrailingWhitespace(): void
    {
        $result = $this->parser->parse('123.45  ', ',', '.');

        self::assertSame('123.45', $result->value());
    }

    public function testParseScientificNotation(): void
    {
        $result = $this->parser->parse('1.5e10', ',', '.');

        // BCMath converts scientific notation to decimal
        self::assertSame('15000000000', $result->value());
    }

    public function testParseScientificNotationWithNegativeExponent(): void
    {
        $result = $this->parser->parse('1.5e-10', ',', '.');

        // BCMath converts scientific notation to decimal
        self::assertSame('0.00000000015', $result->value());
    }

    public function testParseEmptyStringThrowsException(): void
    {
        $this->expectException(ParsingException::class);

        $this->parser->parse('', ',', '.');
    }

    public function testParseInvalidFormatThrowsException(): void
    {
        $this->expectException(ParsingException::class);

        $this->parser->parse('abc', ',', '.');
    }

    public function testParseMultipleDecimalPointsThrowsException(): void
    {
        $this->expectException(ParsingException::class);

        $this->parser->parse('1.2.3', ',', '.');
    }

    public function testTryParseReturnsNullOnFailure(): void
    {
        $result = $this->parser->tryParse('invalid', ',', '.');

        self::assertNull($result);
    }

    public function testTryParseReturnsValueOnSuccess(): void
    {
        $result = $this->parser->tryParse('123.45', ',', '.');

        self::assertNotNull($result);
        self::assertSame('123.45', $result->value());
    }

    // Locale-based parsing tests

    public function testParseWithLocaleEnglish(): void
    {
        $result = $this->parser->parseWithLocale('1,234.56', 'en_US');

        self::assertSame('1234.56', $result->value());
    }

    public function testParseWithLocaleItalian(): void
    {
        $result = $this->parser->parseWithLocale('1.234,56', 'it_IT');

        self::assertSame('1234.56', $result->value());
    }

    public function testParseWithLocaleGerman(): void
    {
        $result = $this->parser->parseWithLocale('1.234,56', 'de_DE');

        self::assertSame('1234.56', $result->value());
    }

    public function testParseWithLocaleFrench(): void
    {
        // French uses non-breaking space for thousands
        $result = $this->parser->parseWithLocale('1 234,56', 'fr_FR');

        self::assertSame('1234.56', $result->value());
    }

    public function testParseWithLocaleSimpleInteger(): void
    {
        $result = $this->parser->parseWithLocale('123', 'en_US');

        self::assertSame('123', $result->value());
    }

    public function testParseWithLocaleNegativeNumber(): void
    {
        $result = $this->parser->parseWithLocale('-123.45', 'en_US');

        self::assertSame('-123.45', $result->value());
    }

    public function testParseWithLocaleEmptyStringThrowsException(): void
    {
        $this->expectException(ParsingException::class);

        $this->parser->parseWithLocale('', 'en_US');
    }

    public function testParseWithLocaleInvalidFormatThrowsException(): void
    {
        $this->expectException(ParsingException::class);

        $this->parser->parseWithLocale('abc', 'en_US');
    }

    public function testTryParseWithLocaleReturnsNullOnFailure(): void
    {
        $result = $this->parser->tryParseWithLocale('invalid', 'en_US');

        self::assertNull($result);
    }

    public function testTryParseWithLocaleReturnsValueOnSuccess(): void
    {
        $result = $this->parser->tryParseWithLocale('123.45', 'en_US');

        self::assertNotNull($result);
        self::assertSame('123.45', $result->value());
    }

    public function testParseWithLocaleTrimsWhitespace(): void
    {
        $result = $this->parser->parseWithLocale('  123.45  ', 'en_US');

        self::assertSame('123.45', $result->value());
    }
}
