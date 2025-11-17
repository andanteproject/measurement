<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Translation;

use Andante\Measurement\Translation\PluralRule;
use Andante\Measurement\Translation\TranslationLoader;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use PHPUnit\Framework\TestCase;

final class TranslationLoaderTest extends TestCase
{
    public function testLoadEnglishTranslations(): void
    {
        $loader = new TranslationLoader('en');

        $name = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::One);

        self::assertSame('meter', $name);
    }

    public function testLoadEnglishPluralTranslations(): void
    {
        $loader = new TranslationLoader('en');

        $one = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::One);
        $other = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::Other);

        self::assertSame('meter', $one);
        self::assertSame('meters', $other);
    }

    public function testLoadItalianTranslations(): void
    {
        $loader = new TranslationLoader('it_IT');

        $name = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::One);

        self::assertSame('metro', $name);
    }

    public function testLoadItalianPluralTranslations(): void
    {
        $loader = new TranslationLoader('it_IT');

        $one = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::One);
        $other = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::Other);

        self::assertSame('metro', $one);
        self::assertSame('metri', $other);
    }

    public function testLoadKilometerTranslations(): void
    {
        $loader = new TranslationLoader('it_IT');

        $one = $loader->getUnitName(MetricLengthUnit::Kilometer, PluralRule::One);
        $other = $loader->getUnitName(MetricLengthUnit::Kilometer, PluralRule::Other);

        self::assertSame('chilometro', $one);
        self::assertSame('chilometri', $other);
    }

    public function testFallbackToOther(): void
    {
        $loader = new TranslationLoader('en');

        // When asking for a plural rule that's not defined (e.g., Few),
        // it should fall back to Other
        $name = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::Few);

        self::assertSame('meters', $name);
    }

    public function testFallbackChainFromItalianToEnglish(): void
    {
        $loader = new TranslationLoader('it_IT');
        $chain = $loader->getFallbackChain();

        self::assertContains('it_IT', $chain);
        self::assertContains('it', $chain);
        self::assertContains('en', $chain);
    }

    public function testEnglishDoesNotFallbackToEnglish(): void
    {
        $loader = new TranslationLoader('en');
        $chain = $loader->getFallbackChain();

        self::assertContains('en', $chain);
        self::assertCount(1, \array_filter($chain, fn ($l) => 'en' === $l));
    }

    public function testGetLocale(): void
    {
        $loader = new TranslationLoader('it_IT');

        self::assertSame('it_IT', $loader->getLocale());
    }

    public function testHasTranslation(): void
    {
        $loader = new TranslationLoader('en');

        self::assertTrue($loader->hasTranslation(MetricLengthUnit::Meter));
        self::assertTrue($loader->hasTranslation(MetricLengthUnit::Kilometer));
    }

    public function testGetUnitTranslation(): void
    {
        $loader = new TranslationLoader('en');
        $translation = $loader->getUnitTranslation(MetricLengthUnit::Meter);

        self::assertArrayHasKey('one', $translation);
        self::assertArrayHasKey('other', $translation);
        self::assertSame('meter', $translation['one']);
        self::assertSame('meters', $translation['other']);
    }

    public function testRegisterTranslationOverrides(): void
    {
        $loader = new TranslationLoader('en');

        $loader->registerTranslation(MetricLengthUnit::Meter, [
            'one' => 'custom meter',
            'other' => 'custom meters',
        ]);

        self::assertSame('custom meter', $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::One));
        self::assertSame('custom meters', $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::Other));
    }

    public function testReturnsNullForUnknownUnit(): void
    {
        $loader = new TranslationLoader('en');

        // Create a mock unit that's not in the translations
        $unknownUnit = $this->createMock(\Andante\Measurement\Contract\UnitInterface::class);
        $unknownUnit->method('symbol')->willReturn('xyz');
        $unknownUnit->method('name')->willReturn('unknown');

        $name = $loader->getUnitName($unknownUnit, PluralRule::One);

        self::assertNull($name);
    }

    // Multi-language translation tests

    public function testGermanTranslations(): void
    {
        $loader = new TranslationLoader('de');

        $one = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::One);
        $other = $loader->getUnitName(MetricLengthUnit::Kilometer, PluralRule::Other);

        self::assertSame('Meter', $one);
        self::assertSame('Kilometer', $other);
    }

    public function testSpanishTranslations(): void
    {
        $loader = new TranslationLoader('es');

        $one = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::One);
        $other = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::Other);

        self::assertSame('metro', $one);
        self::assertSame('metros', $other);
    }

    public function testFrenchTranslations(): void
    {
        $loader = new TranslationLoader('fr');

        $one = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::One);
        $other = $loader->getUnitName(MetricLengthUnit::Kilometer, PluralRule::Other);

        self::assertSame('mètre', $one);
        self::assertSame('kilomètres', $other);
    }

    public function testPortugueseTranslations(): void
    {
        $loader = new TranslationLoader('pt');

        $one = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::One);
        $other = $loader->getUnitName(MetricLengthUnit::Kilometer, PluralRule::Other);

        self::assertSame('metro', $one);
        self::assertSame('quilômetros', $other);
    }

    public function testJapaneseTranslations(): void
    {
        $loader = new TranslationLoader('ja');

        $one = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::One);
        $other = $loader->getUnitName(MetricLengthUnit::Kilometer, PluralRule::Other);

        self::assertSame('メートル', $one);
        self::assertSame('キロメートル', $other);
    }

    public function testRussianTranslations(): void
    {
        $loader = new TranslationLoader('ru');

        $one = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::One);
        $other = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::Other);

        self::assertSame('метр', $one);
        self::assertSame('метров', $other);
    }

    public function testChineseSimplifiedTranslations(): void
    {
        $loader = new TranslationLoader('zh');

        $one = $loader->getUnitName(MetricLengthUnit::Meter, PluralRule::One);
        $other = $loader->getUnitName(MetricLengthUnit::Kilometer, PluralRule::Other);

        self::assertSame('米', $one);
        self::assertSame('千米', $other);
    }
}
