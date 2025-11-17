<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Parser;

use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Exception\ParsingException;
use Andante\Measurement\Parser\ParseOptions;
use Andante\Measurement\Parser\Parser;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use PHPUnit\Framework\TestCase;

/**
 * Simple test quantity for parser tests.
 */
class ParserTestQuantity implements QuantityInterface, QuantityFactoryInterface
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

final class ParserTest extends TestCase
{
    private Parser $parser;
    private UnitRegistry $registry;

    protected function setUp(): void
    {
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
        Parser::reset();

        $this->registry = new UnitRegistry();

        // Register test units
        $this->registry->register(MetricLengthUnit::Meter, ParserTestQuantity::class);
        $this->registry->register(MetricLengthUnit::Kilometer, ParserTestQuantity::class);
        $this->registry->register(MetricLengthUnit::Centimeter, ParserTestQuantity::class);

        $this->parser = new Parser($this->registry);
    }

    protected function tearDown(): void
    {
        Parser::reset();
        ConversionFactorRegistry::reset();
        UnitRegistry::reset();
    }

    // Global service pattern tests

    public function testGlobalReturnsSameInstance(): void
    {
        // Use custom registry for global instance
        UnitRegistry::setGlobal($this->registry);

        $instance1 = Parser::global();
        $instance2 = Parser::global();

        self::assertSame($instance1, $instance2);
    }

    public function testResetClearsGlobalInstance(): void
    {
        UnitRegistry::setGlobal($this->registry);

        $instance1 = Parser::global();
        Parser::reset();
        $instance2 = Parser::global();

        self::assertNotSame($instance1, $instance2);
    }

    public function testSetGlobalReplacesInstance(): void
    {
        UnitRegistry::setGlobal($this->registry);

        $custom = new Parser($this->registry);
        Parser::setGlobal($custom);

        self::assertSame($custom, Parser::global());
    }

    // Basic parsing tests

    public function testParseSimpleQuantity(): void
    {
        $quantity = $this->parser->parse('10 m');

        self::assertSame('10', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $quantity->getUnit());
    }

    public function testParseWithKilometer(): void
    {
        $quantity = $this->parser->parse('5 km');

        self::assertSame('5', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Kilometer, $quantity->getUnit());
    }

    public function testParseDecimalNumber(): void
    {
        $quantity = $this->parser->parse('10.5 m');

        self::assertSame('10.5', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $quantity->getUnit());
    }

    public function testParseWithThousandsSeparator(): void
    {
        $quantity = $this->parser->parse('1,234.56 m');

        self::assertSame('1234.56', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $quantity->getUnit());
    }

    public function testParseNegativeNumber(): void
    {
        $quantity = $this->parser->parse('-100 m');

        self::assertSame('-100', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $quantity->getUnit());
    }

    public function testParsePositiveNumber(): void
    {
        $quantity = $this->parser->parse('+100 m');

        // BCMath normalizes +100 to 100
        self::assertSame('100', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $quantity->getUnit());
    }

    // Input normalization tests

    public function testParseWithNoSpaceBetweenNumberAndUnit(): void
    {
        $quantity = $this->parser->parse('10km');

        self::assertSame('10', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Kilometer, $quantity->getUnit());
    }

    public function testParseWithExtraWhitespace(): void
    {
        $quantity = $this->parser->parse('  10   m  ');

        self::assertSame('10', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $quantity->getUnit());
    }

    public function testParseByUnitName(): void
    {
        $quantity = $this->parser->parse('10 meter');

        self::assertSame('10', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $quantity->getUnit());
    }

    public function testParseCaseInsensitiveUnit(): void
    {
        $quantity = $this->parser->parse('10 KM');

        self::assertSame('10', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Kilometer, $quantity->getUnit());
    }

    // Locale-aware parsing tests

    public function testParseItalianFormat(): void
    {
        $options = ParseOptions::fromLocale('it_IT');
        $quantity = $this->parser->parse('1.234,56 m', $options);

        self::assertSame('1234.56', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $quantity->getUnit());
    }

    public function testParseGermanFormat(): void
    {
        $options = ParseOptions::fromLocale('de_DE');
        $quantity = $this->parser->parse('1.234,56 km', $options);

        self::assertSame('1234.56', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Kilometer, $quantity->getUnit());
    }

    public function testParseWithCustomSeparators(): void
    {
        $options = ParseOptions::create()
            ->withThousandSeparator("'")
            ->withDecimalSeparator('.');

        $quantity = $this->parser->parse("1'234.56 m", $options);

        self::assertSame('1234.56', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $quantity->getUnit());
    }

    // Default unit tests

    public function testParseNumberWithDefaultUnit(): void
    {
        $options = ParseOptions::create()
            ->withDefaultUnit(MetricLengthUnit::Meter);

        $quantity = $this->parser->parse('100', $options);

        self::assertSame('100', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $quantity->getUnit());
    }

    public function testParseDecimalWithDefaultUnit(): void
    {
        $options = ParseOptions::create()
            ->withDefaultUnit(MetricLengthUnit::Kilometer);

        $quantity = $this->parser->parse('1.5', $options);

        self::assertSame('1.5', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Kilometer, $quantity->getUnit());
    }

    public function testParseWithThousandsAndDefaultUnit(): void
    {
        $options = ParseOptions::create()
            ->withDefaultUnit(MetricLengthUnit::Meter);

        $quantity = $this->parser->parse('1,234.56', $options);

        self::assertSame('1234.56', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $quantity->getUnit());
    }

    public function testExplicitUnitOverridesDefault(): void
    {
        $options = ParseOptions::create()
            ->withDefaultUnit(MetricLengthUnit::Meter);

        $quantity = $this->parser->parse('100 km', $options);

        self::assertSame('100', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Kilometer, $quantity->getUnit());
    }

    // Error handling tests

    public function testParseEmptyStringThrows(): void
    {
        $this->expectException(ParsingException::class);

        $this->parser->parse('');
    }

    public function testParseWithoutUnitAndNoDefaultThrows(): void
    {
        $this->expectException(ParsingException::class);

        $this->parser->parse('100');
    }

    public function testParseUnknownUnitThrows(): void
    {
        $this->expectException(ParsingException::class);

        $this->parser->parse('10 unknown');
    }

    public function testParseInvalidNumberThrows(): void
    {
        $this->expectException(ParsingException::class);

        $this->parser->parse('abc m');
    }

    // tryParse tests

    public function testTryParseReturnsQuantityOnSuccess(): void
    {
        $quantity = $this->parser->tryParse('10 m');

        self::assertNotNull($quantity);
        self::assertSame('10', $quantity->getValue()->value());
        self::assertSame(MetricLengthUnit::Meter, $quantity->getUnit());
    }

    public function testTryParseReturnsNullOnEmptyInput(): void
    {
        $quantity = $this->parser->tryParse('');

        self::assertNull($quantity);
    }

    public function testTryParseReturnsNullOnUnknownUnit(): void
    {
        $quantity = $this->parser->tryParse('10 unknown');

        self::assertNull($quantity);
    }

    public function testTryParseReturnsNullOnInvalidNumber(): void
    {
        $quantity = $this->parser->tryParse('abc m');

        self::assertNull($quantity);
    }

    public function testTryParseWithOptions(): void
    {
        $options = ParseOptions::fromLocale('it_IT');
        $quantity = $this->parser->tryParse('1.234,56 m', $options);

        self::assertNotNull($quantity);
        self::assertSame('1234.56', $quantity->getValue()->value());
    }
}
