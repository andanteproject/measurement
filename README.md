![Andante Project Logo](https://github.com/andanteproject/measurement/blob/main/andanteproject-logo.png?raw=true)
# Measurement
#### PHP Library - [AndanteProject](https://github.com/andanteproject)
[![Latest Version](https://img.shields.io/github/release/andanteproject/measurement.svg)](https://github.com/andanteproject/measurement/releases)
[![CI](https://github.com/andanteproject/measurement/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/andanteproject/measurement/actions/workflows/ci.yml)
![Php8](https://img.shields.io/badge/PHP-8.1%2B-informational?style=flat&logo=php)
![PhpStan](https://img.shields.io/badge/PHPStan-Level%209-success?style=flat&logo=php)

A modern, type-safe PHP library for handling physical measurements with automatic unit conversion, internationalization, and precise calculations.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Core Concepts](#core-concepts)
- [Working with Quantities](#working-with-quantities)
  - [Conversions](#conversions)
  - [Arithmetic](#arithmetic)
  - [Dimensional Analysis](#dimensional-analysis)
  - [Comparisons](#comparisons)
  - [Auto-Scaling](#auto-scaling)
- [Parsing and Formatting](#parsing-and-formatting)
  - [Parsing Strings](#parsing-strings)
  - [Formatting Output](#formatting-output)
- [Available Quantities](#available-quantities)
  - [Length](#length-l¹), [Mass](#mass-m¹), [Time](#time-t¹), [Temperature](#temperature-θ¹), [Electric Current](#electric-current-i¹)
  - [Area](#area-l²), [Volume](#volume-l³), [Velocity](#velocity-l¹t⁻¹), [Acceleration](#acceleration-l¹t⁻²)
  - [Force](#force-l¹m¹t⁻²), [Pressure](#pressure-l⁻¹m¹t⁻²), [Energy](#energy-l²m¹t⁻²), [Power](#power-l²m¹t⁻³), [Density](#density-l⁻³m¹)
  - [Frequency](#frequency-t⁻¹), [Angle](#angle-dimensionless)
  - [Electric Potential](#electric-potential-l²m¹t⁻³i⁻¹), [Electric Resistance](#electric-resistance-l²m¹t⁻³i⁻²), [Electric Capacitance](#electric-capacitance-l⁻²m⁻¹t⁴i²), [Electric Charge](#electric-charge-t¹i¹)
  - [Inductance](#inductance-l²m¹t⁻²i⁻²), [Magnetic Flux](#magnetic-flux-l²m¹t⁻²i⁻¹)
  - [Luminous Intensity](#luminous-intensity-j¹), [Luminous Flux](#luminous-flux-j¹), [Illuminance](#illuminance-l⁻²j¹)
  - [Calorific Value](#calorific-value-l⁻¹m¹t⁻²), [Digital Information](#digital-information-d¹), [Data Transfer Rate](#data-transfer-rate-d¹t⁻¹)
- [Registries](#registries)
  - [UnitRegistry](#unitregistry)
  - [ConversionFactorRegistry](#conversionfactorregistry)
  - [ResultQuantityRegistry](#resultquantityregistry)
  - [FormulaUnitRegistry](#formulaunitregistry)
  - [QuantityDefaultConfigProviderInterface](#quantitydefaultconfigproviderinterface)
- [Adding Custom Quantities](#adding-custom-quantities)
  - [Step 1: Create the Dimension](#step-1-create-the-dimension)
  - [Step 2: Create the Unit Enum](#step-2-create-the-unit-enum)
  - [Step 3: Create the Quantity Interface](#step-3-create-the-quantity-interface)
  - [Step 4: Create Quantity Classes](#step-4-create-quantity-classes)
  - [Step 5: Create a Provider](#step-5-create-a-provider)
  - [Step 6: Register with the Library](#step-6-register-with-the-library)
  - [Step 7: Add Translations (Optional)](#step-7-add-translations-optional)
- [Testing](#testing)

## Features

- **Type-Safe Quantities** - Three levels of type safety (dimension, system, unit) with compile-time type checking and full IDE autocompletion
- **Dimensional Analysis** - Automatic type resolution (`Length × Length = Area`, `Length ÷ Time = Velocity`) that prevents dimensional errors at compile time
- **Arbitrary Precision** - Exact calculations using `brick/math` with no floating-point precision errors, handles very large and very small numbers accurately
- **Auto-Scaling** - Smart unit selection (`1000 g` → `1 kg`) that automatically chooses the most appropriate unit representation across all quantity types
- **String Parsing** - Parse strings like `"100 km"`, `"5.5 kWh"`, `"25 °C"` with locale-aware number parsing, supports unit symbols and full unit names
- **Flexible Formatting** - Multiple output styles (Short, Long, Narrow, Value-only, Unit-only) with locale-aware number formatting and translated unit names
- **Internationalization** - 9 locales (en, de, es, fr, it, pt, ja, ru, zh) with separate locale support for numbers and unit names
- **Locale-Aware Numbers** - Parse `"1.500,5 m"` (IT) or `"1,500.5 m"` (US) with custom thousand and decimal separators
- **Unit Systems** - Complete SI & Metric support, full Imperial unit support with conversions, and Digital units with both SI (decimal) and IEC (binary) prefixes
- **Extensible** - Easy to add custom dimensions and units with provider-based registration system and full integration with existing library features
- **Modern PHP 8.1+** - Enums, readonly properties, strict types, PHPStan level 9, immutable value objects, trait-based functionality composition

## Installation

```bash
composer require andanteproject/measurement
```

### Requirements

- PHP 8.1 or higher
- `brick/math` (for arbitrary precision arithmetic)

## Quick Start

```php
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Length\Metric\Meter;
use Andante\Measurement\Quantity\Length\Metric\Kilometer;
use Andante\Measurement\Quantity\Energy\Electric\KilowattHour;
use Andante\Measurement\Quantity\Volume\Metric\CubicMeter;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use Andante\Measurement\Unit\Energy\SIEnergyUnit;
use Andante\Measurement\Parser\Parser;
use Andante\Measurement\Parser\ParseOptions;
use Andante\Measurement\Formatter\Formatter;
use Andante\Measurement\Formatter\FormatOptions;
use Andante\Measurement\Formatter\FormatStyle;
use Andante\Measurement\Unit\SymbolNotation;

// Create quantities using unit-specific classes
$distance = Meter::of(NumberFactory::create('5000'));
$energy = KilowattHour::of(NumberFactory::create('150'));

// Convert to different units
$inKilometers = $distance->to(MetricLengthUnit::Kilometer);  // 5 km
$inJoules = $energy->to(SIEnergyUnit::Joule);                 // 540,000,000 J

// Arithmetic operations
$doubled = $distance->multiplyBy(NumberFactory::create('2'));  // 10000 m
$sum = $distance->add(Kilometer::of(NumberFactory::create('2')));  // 7000 m
$diff = $distance->subtract(Kilometer::of(NumberFactory::create('1')));  // 4000 m
$half = $distance->divideBy(NumberFactory::create('2'));  // 2500 m

// Type-safe: incompatible types won't compile
// $distance->add($energy);  // ❌ Type error!

// Auto-scale to the most readable unit
$largeDistance = Meter::of(NumberFactory::create('5000'));
$scaled = $largeDistance->autoScale();  // 5 km

// Parse from strings with options
$parser = Parser::global();

// Simple parsing (no options)
$parsed = $parser->parse('100 km');

// Parse with Italian locale (for number formatting)
$parsed = $parser->parse(
    '1.234,56 m',
    ParseOptions::fromLocale('it_IT')
);  // Italian: 1234.56 m

// Parse with Italian locale (different number)
$parsed = $parser->parse(
    '1.500,5 km',
    ParseOptions::fromLocale('it_IT')
);  // Italian: 1500.5 km

// Parse with default unit (for numbers without units)
$parsed = $parser->parse(
    '100',
    ParseOptions::create()
        ->withDefaultUnit(MetricLengthUnit::Meter)
);  // 100 m

// Parse with custom thousand separator
$parsed = $parser->parse(
    '1 234.56 m',
    ParseOptions::create()
        ->withThousandSeparator(' ')
        ->withDecimalSeparator('.')
);  // 1234.56 m

// Parse with custom decimal separator
$parsed = $parser->parse(
    '1234,56 m',
    ParseOptions::create()
        ->withDecimalSeparator(',')
);  // 1234.56 m

// Parse with locale and default unit
$parsed = $parser->parse(
    '1.500',
    ParseOptions::fromLocale('it_IT')
        ->withDefaultUnit(MetricLengthUnit::Kilometer)
);  // 1500 km

// Parse with all custom separators
$parsed = $parser->parse(
    '1_234|56 m',
    ParseOptions::create()
        ->withThousandSeparator('_')
        ->withDecimalSeparator('|')
);  // 1234.56 m

// Format with options
$formatter = Formatter::global();

// Default formatting (short style with symbol)
echo $formatter->format($distance);  // "5,000 m"

// Format with Italian locale
echo $formatter->format(
    $distance,
    FormatOptions::fromLocale('it_IT')
);  // "5.000 m"

// Format with long style (translated unit names)
echo $formatter->format(
    $distance,
    FormatOptions::create()
        ->withStyle(FormatStyle::Long)
);  // "5,000 meters"

// Format with long style and Italian locale
echo $formatter->format(
    $distance,
    FormatOptions::fromLocale('it_IT')
        ->withStyle(FormatStyle::Long)
);  // "5.000 metri"

// Format with narrow style (no space)
echo $formatter->format(
    $distance,
    FormatOptions::create()
        ->withStyle(FormatStyle::Narrow)
);  // "5,000m"

// Format with narrow style and Italian locale
echo $formatter->format(
    $distance,
    FormatOptions::fromLocale('it_IT')
        ->withStyle(FormatStyle::Narrow)
);  // "5.000m"

// Format with value only (no unit)
echo $formatter->format(
    $distance,
    FormatOptions::create()
        ->withStyle(FormatStyle::ValueOnly)
);  // "5,000"

// Format with unit symbol only
echo $formatter->format(
    $distance,
    FormatOptions::create()
        ->withStyle(FormatStyle::UnitSymbolOnly)
);  // "m"

// Format with unit name only
echo $formatter->format(
    $distance,
    FormatOptions::create()
        ->withStyle(FormatStyle::UnitNameOnly)
);  // "meters"

// Format with unit name only and Italian locale
echo $formatter->format(
    $distance,
    FormatOptions::fromLocale('it_IT')
        ->withStyle(FormatStyle::UnitNameOnly)
);  // "metri"

// Format with fixed precision
echo $formatter->format(
    $distance,
    FormatOptions::create()
        ->withPrecision(2)
);  // "5,000.00 m"

// Format with precision and Italian locale
echo $formatter->format(
    $distance,
    FormatOptions::fromLocale('it_IT')
        ->withPrecision(2)
);  // "5.000,00 m"

// Format with precision and long style
echo $formatter->format(
    $distance,
    FormatOptions::create()
        ->withPrecision(3)
        ->withStyle(FormatStyle::Long)
);  // "5,000.000 meters"

// Format with custom thousand separator
echo $formatter->format(
    $distance,
    FormatOptions::create()
        ->withThousandSeparator(' ')
);  // "5 000 m"

// Format with custom decimal separator
$smallDistance = Meter::of(NumberFactory::create('1234.56'));
echo $formatter->format(
    $smallDistance,
    FormatOptions::create()
        ->withDecimalSeparator(',')
);  // "1,234,56 m"

// Format with custom separators
echo $formatter->format(
    $smallDistance,
    FormatOptions::create()
        ->withThousandSeparator('_')
        ->withDecimalSeparator('|')
);  // "1_234|56 m"

// Format with separate unit locale (Italian numbers, English units)
echo $formatter->format(
    $distance,
    FormatOptions::fromLocale('it_IT')
        ->withUnitLocale('en_US')
        ->withStyle(FormatStyle::Long)
);  // "5.000 meters" (Italian numbers, English unit name)

// Format with IEEE symbol notation
$energy = KilowattHour::of(NumberFactory::create('150'));
echo $formatter->format(
    $energy,
    FormatOptions::create()
        ->withSymbolNotation(SymbolNotation::IEEE)
);  // "150 kW·h"

// Format with ASCII symbol notation
echo $formatter->format(
    $energy,
    FormatOptions::create()
        ->withSymbolNotation(SymbolNotation::ASCII)
);  // "150 kW*h"

// Format with Unicode symbol notation
$volume = CubicMeter::of(NumberFactory::create('1000000'));
echo $formatter->format(
    $volume,
    FormatOptions::create()
        ->withSymbolNotation(SymbolNotation::Unicode)
);  // "1,000,000 m³"

// Format with multiple options combined
echo $formatter->format(
    $smallDistance,
    FormatOptions::fromLocale('it_IT')
        ->withStyle(FormatStyle::Long)
        ->withPrecision(2)
);  // "1.234,56 metri"

// Format with all options
echo $formatter->format(
    $distance,
    FormatOptions::fromLocale('it_IT')
        ->withUnitLocale('en_US')
        ->withStyle(FormatStyle::Long)
        ->withPrecision(3)
        ->withSymbolNotation(SymbolNotation::Unicode)
);  // "5.000,000 meters"
```

## Core Concepts

### Dimension vs Unit vs Quantity

- **Dimension**: The physical nature (Length, Mass, Energy)
- **Unit**: A specific scale (meter, kilometer, foot)
- **Quantity**: A value + unit (5 meters)

### Three Levels of Type Safety

The library provides three levels of specificity for type-hinting quantities. This allows you to be as strict or flexible as your use case requires - from accepting any length unit, to requiring a specific measurement system, to enforcing an exact unit.

```php
use Andante\Measurement\Quantity\Length\Length;
use Andante\Measurement\Quantity\Length\MetricLength;
use Andante\Measurement\Quantity\Length\Metric\Meter;
use Andante\Measurement\Unit\Length\MetricLengthUnit;

// Level 1: Generic - accepts any length unit (meters, feet, miles, etc.)
function calculateArea(Length $width, Length $height): Area {
    return $width->multiply($height);
}

// Level 2: System-specific - only metric units allowed (meters, kilometers, etc.)
function europeanDistance(MetricLength $distance): void {
    // Rejects imperial units like feet or miles at compile time
}

// Level 3: Unit-specific - only meters, nothing else
function precisionMeasurement(Meter $length): void {
    // Even kilometers or centimeters are rejected
}
```

### Creating Quantities

There are multiple ways to create quantities, each corresponding to the three levels of type safety. Use `NumberFactory::create()` to create precise numeric values from strings, integers, or floats.

```php
use Andante\Measurement\Math\NumberFactory;
use Andante\Measurement\Quantity\Length\Length;
use Andante\Measurement\Quantity\Length\MetricLength;
use Andante\Measurement\Quantity\Length\Metric\Meter;
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use Andante\Measurement\Unit\Length\ImperialLengthUnit;

// Unit-specific class - most type-safe
$meter = Meter::of(NumberFactory::create('100'));

// Mid-level class with unit - system-constrained
$length = MetricLength::of(
    NumberFactory::create('100'),
    MetricLengthUnit::Kilometer
);

// Generic class with any unit - most flexible
$imperial = Length::of(
    NumberFactory::create('5280'),
    ImperialLengthUnit::Foot
);
```

## Working with Quantities

Once you have quantity objects, you can convert between units, perform arithmetic, compare values, and more. All operations are immutable - they return new quantity objects rather than modifying the original.

### Conversions

Use the `to()` method to convert a quantity to a different unit within the same dimension. The conversion is handled automatically using the library's conversion factor registry.

```php
use Andante\Measurement\Unit\Length\MetricLengthUnit;
use Andante\Measurement\Unit\Length\ImperialLengthUnit;

$meters = Meter::of(NumberFactory::create('1000'));

// Convert to another unit in the same dimension
$kilometers = $meters->to(MetricLengthUnit::Kilometer);  // 1 km
$feet = $meters->to(ImperialLengthUnit::Foot);           // 3280.84 ft

// Get the numeric value
echo $kilometers->getValue()->value();  // "1"
echo $kilometers->getUnit()->symbol();  // "km"
```

### Arithmetic

Quantities support addition, subtraction, multiplication by scalars, and division by scalars. When adding or subtracting quantities with different units, the right operand is automatically converted to the left operand's unit.

```php
$a = Meter::of(NumberFactory::create('100'));
$b = Meter::of(NumberFactory::create('50'));

// Same-unit arithmetic
$sum = $a->add($b);                                    // 150 m
$diff = $a->subtract($b);                              // 50 m
$scaled = $a->multiplyBy(NumberFactory::create('2'));  // 200 m
$half = $a->divideBy(NumberFactory::create('2'));      // 50 m

// Cross-unit arithmetic (auto-converts to left operand's unit)
$km = Kilometer::of(NumberFactory::create('1'));
$result = $km->add($a);  // 1.1 km (100m converted to 0.1km)
```

### Dimensional Analysis

When multiplying or dividing quantities, the library automatically determines the result type:

```php
use Andante\Measurement\Quantity\Length\Metric\Meter;
use Andante\Measurement\Quantity\Time\SI\Second;
use Andante\Measurement\Quantity\Mass\Metric\Kilogram;

// Length × Length = Area
$width = Meter::of(NumberFactory::create('5'));
$height = Meter::of(NumberFactory::create('3'));
$area = $width->multiply($height);  // SquareMeter (15 m²)

// Length ÷ Time = Velocity
$distance = Meter::of(NumberFactory::create('100'));
$time = Second::of(NumberFactory::create('10'));
$velocity = $distance->divide($time);  // MeterPerSecond (10 m/s)

// Mass × Acceleration = Force
$mass = Kilogram::of(NumberFactory::create('10'));
$accel = MeterPerSecondSquared::of(NumberFactory::create('9.8'));
$force = $mass->multiply($accel);  // Newton (98 N)
```

### Comparisons

Compare quantities using intuitive methods. Comparisons work across different units - the library automatically converts to a common unit before comparing.

```php
$a = Meter::of(NumberFactory::create('100'));
$b = Meter::of(NumberFactory::create('50'));

$a->isGreaterThan($b);     // true
$a->isLessThan($b);        // false
$a->equals($b);            // false
$a->isGreaterOrEqual($b);  // true

// Cross-unit comparisons work too
$km = Kilometer::of(NumberFactory::create('1'));
$a->isLessThan($km);  // true (100m < 1000m)
```

### Auto-Scaling

The `autoScale()` method automatically converts a quantity to the most human-readable unit. It prefers values between 1 and 1000, choosing larger or smaller units as appropriate.

```php
// Automatically choose the most readable unit
$grams = Gram::of(NumberFactory::create('5000'));
$scaled = $grams->autoScale();  // Kilogram (5 kg)

$bytes = Byte::of(NumberFactory::create('1048576'));
$scaled = $bytes->autoScale();  // Megabyte (1 MB)

// Works with all quantity types
$watts = Watt::of(NumberFactory::create('1500000'));
$scaled = $watts->autoScale();  // Megawatt (1.5 MW)
```

## Parsing and Formatting

The library includes powerful parsing and formatting capabilities with full internationalization support. Parse measurement strings from user input, and format quantities for display in any locale.

### Parsing Strings

Convert strings like `"100 km"` or `"5.5 kWh"` into quantity objects. The parser handles unit symbols, full unit names, and locale-specific number formats.

```php
use Andante\Measurement\Parser\Parser;
use Andante\Measurement\Parser\ParseOptions;

$parser = Parser::global();

// Simple parsing
$length = $parser->parse('100 km');
$energy = $parser->parse('5.5 kWh');
$temp = $parser->parse('25 °C');

// With locale (for number formatting)
$options = ParseOptions::fromLocale('it_IT');
$length = $parser->parse('1.234,56 m', $options);  // Italian: 1234.56 m

$options = ParseOptions::fromLocale('en_US');
$length = $parser->parse('1,234.56 m', $options);  // US: 1234.56 m

// With default unit (for numbers without units)
$options = ParseOptions::create()->withDefaultUnit(MetricLengthUnit::Meter);
$length = $parser->parse('100', $options);  // 100 m

// Safe parsing (returns null on failure)
$result = $parser->tryParse('invalid');  // null
```

#### ParseOptions Reference

| Option | Method | Default | Description |
|--------|--------|---------|-------------|
| locale | `fromLocale($locale)` / `withLocale($locale)` | `null` | Locale for number formatting (e.g., `'it_IT'`, `'de_DE'`). Determines thousand/decimal separators via ICU. |
| thousandSeparator | `withThousandSeparator($sep)` | `','` | Character used as thousand separator. Overrides locale setting. |
| decimalSeparator | `withDecimalSeparator($sep)` | `'.'` | Character used as decimal separator. Overrides locale setting. |
| defaultUnit | `withDefaultUnit($unit)` | `null` | Unit to assume when parsing numbers without unit symbols (e.g., `'100'` → `100 m`). |

### Formatting Output

Format quantities for display with control over style, precision, and locale. The formatter supports multiple styles (Short, Long, Narrow) and translates unit names into 9 languages.

```php
use Andante\Measurement\Formatter\Formatter;
use Andante\Measurement\Formatter\FormatOptions;
use Andante\Measurement\Formatter\FormatStyle;

$formatter = Formatter::global();
$length = Meter::of(NumberFactory::create('1500'));

// Default (short style with symbol)
echo $formatter->format($length);  // "1,500 m"

// Long style with unit name
$options = FormatOptions::create()->withStyle(FormatStyle::Long);
echo $formatter->format($length, $options);  // "1,500 meters"

// With locale
$options = FormatOptions::fromLocale('de_DE');
echo $formatter->format($length, $options);  // "1.500 m"

// Long style with locale (translated unit names)
$options = FormatOptions::fromLocale('it_IT')->withStyle(FormatStyle::Long);
echo $formatter->format($length, $options);  // "1.500 metri"

// Fixed precision
$options = FormatOptions::create()->withPrecision(2);
echo $formatter->format($length, $options);  // "1,500.00 m"

// Narrow style (no space between number and unit)
$options = FormatOptions::create()->withStyle(FormatStyle::Narrow);
echo $formatter->format($length, $options);  // "1,500m"
```

#### FormatOptions Reference

| Option | Method | Default | Description |
|--------|--------|---------|-------------|
| locale | `fromLocale($locale)` / `withLocale($locale)` | `null` | Locale for number formatting (thousand/decimal separators). |
| unitLocale | `withUnitLocale($locale)` | same as `locale` | Separate locale for unit name translations. Allows Italian numbers with English unit names. |
| precision | `withPrecision($int)` | `null` (auto) | Number of decimal places. `null` preserves input precision and removes trailing zeros. |
| thousandSeparator | `withThousandSeparator($sep)` | `','` | Character used as thousand separator. Overrides locale setting. |
| decimalSeparator | `withDecimalSeparator($sep)` | `'.'` | Character used as decimal separator. Overrides locale setting. |
| style | `withStyle($style)` | `FormatStyle::Short` | Output style (see FormatStyle table below). |
| notation | `withSymbolNotation($notation)` | `SymbolNotation::Default` | Symbol notation style (see SymbolNotation table below). |

#### FormatStyle Options

| Style | Example | Description |
|-------|---------|-------------|
| `FormatStyle::Short` | `"1,500 m"` | Unit symbol with space (default). |
| `FormatStyle::Long` | `"1,500 meters"` | Full unit name, translated if available. |
| `FormatStyle::Narrow` | `"1,500m"` | Unit symbol without space. |
| `FormatStyle::ValueOnly` | `"1,500"` | Numeric value only, no unit. Useful for charts and data export. |
| `FormatStyle::UnitSymbolOnly` | `"m"` | Unit symbol only, no value. Useful for table headers. |
| `FormatStyle::UnitNameOnly` | `"meters"` | Full unit name only, no value. Useful for legends. |

#### SymbolNotation Options

| Notation | Example | Description |
|----------|---------|-------------|
| `SymbolNotation::Default` | `"Gbps"`, `"kWh"`, `"m³"` | Most common/recognizable form. |
| `SymbolNotation::IEEE` | `"Gbit/s"`, `"kW·h"` | Technical/standards-compliant form. |
| `SymbolNotation::ASCII` | `"m3"`, `"um"`, `"kW*h"` | Keyboard-friendly, no special characters. |
| `SymbolNotation::Unicode` | `"m³"`, `"μm"`, `"kW·h"` | Proper Unicode symbols. |

## Registries

The library uses four registries to manage units, conversions, and dimensional analysis. All registries are pre-configured with built-in quantities.

### UnitRegistry

Maps units to their quantity classes. Used for parsing and creating quantities.

```php
use Andante\Measurement\Registry\UnitRegistry;

$registry = UnitRegistry::global();

// Find a unit by symbol
$unit = $registry->findBySymbol('km');  // MetricLengthUnit::Kilometer

// Get the quantity class for a unit
$class = $registry->getQuantityClass(MetricLengthUnit::Meter);  // Meter::class

// Get all units for a dimension
$lengthUnits = $registry->getUnitsForDimension(Length::instance());

// Get only metric units
$metricUnits = $registry->getMetricUnits(Length::instance());
```

### ConversionFactorRegistry

Stores conversion factors from each unit to its dimension's base unit.

```php
use Andante\Measurement\Registry\ConversionFactorRegistry;

$registry = ConversionFactorRegistry::global();

// Get conversion rule for a unit
$rule = $registry->get(MetricLengthUnit::Kilometer);
// factor: 1000 (1 km = 1000 m)

// Temperature uses affine conversions (factor + offset)
$rule = $registry->get(TemperatureUnit::Celsius);
// factor: 1, offset: 273.15 (K = °C + 273.15)
```

### ResultQuantityRegistry

Determines what quantity class to return from dimensional operations.

```php
use Andante\Measurement\Registry\ResultQuantityRegistry;

$registry = ResultQuantityRegistry::global();

// What class should Meter × Meter return?
$resultClass = $registry->getResultClass(
    Meter::class,
    new DimensionalFormula(length: 2)  // L²
);
// Returns: MetricArea::class (preserves metric system)
```

### FormulaUnitRegistry

Maps dimensional formulas to default units for each system. When the library creates a result from dimensional analysis, it uses this registry to determine which unit to express the result in.

```php
use Andante\Measurement\Registry\FormulaUnitRegistry;

$registry = FormulaUnitRegistry::global();

// Default unit for velocity (L¹T⁻¹)
$unit = $registry->getDefaultUnit(new DimensionalFormula(length: 1, time: -1));
// Returns: MetricVelocityUnit::MeterPerSecond

// Imperial default
$unit = $registry->getDefaultUnit(
    new DimensionalFormula(length: 1, time: -1),
    UnitSystem::Imperial
);
// Returns: ImperialVelocityUnit::FootPerSecond
```

### QuantityDefaultConfigProviderInterface

The `QuantityDefaultConfigProviderInterface` is the recommended way to register custom quantities with all four registries at once. Instead of manually registering with each registry, implement this interface to centralize your quantity's configuration.

```php
use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Registry\UnitRegistry;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\ResultQuantityRegistry;
use Andante\Measurement\Registry\FormulaUnitRegistry;

final class MyQuantityProvider implements QuantityDefaultConfigProviderInterface
{
    public function registerUnits(UnitRegistry $registry): void
    {
        // Register unit → quantity class mappings
    }

    public function registerConversionFactors(ConversionFactorRegistry $registry): void
    {
        // Register unit → base unit conversion factors
    }

    public function registerResultMappings(ResultQuantityRegistry $registry): void
    {
        // Register dimensional formula → result class mappings
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        // Register dimensional formula → default unit mappings
    }
}

// Register with all registries at once
$provider = MyQuantityProvider::global();
$provider->registerUnits(UnitRegistry::global());
$provider->registerConversionFactors(ConversionFactorRegistry::global());
$provider->registerResultMappings(ResultQuantityRegistry::global());
$provider->registerFormulaUnits(FormulaUnitRegistry::global());
```

## Adding Custom Quantities

The library is fully extensible - you can add your own quantity types that integrate seamlessly with the existing system. This section walks through creating a custom "Viscosity" quantity as an example. The same pattern applies to any physical quantity you need to add.

### Step 1: Create the Dimension

A dimension represents the physical nature of a quantity, defined by its dimensional formula using the seven SI base dimensions: Length (L), Mass (M), Time (T), Electric Current (I), Temperature (Θ), Amount of Substance (N), and Luminous Intensity (J).

Examples of dimensional formulas:
- **Velocity** = L¹T⁻¹ (length per time)
- **Force** = L¹M¹T⁻² (mass times acceleration)
- **Pressure** = L⁻¹M¹T⁻² (force per area)
- **Viscosity** = L⁻¹M¹T⁻¹ (used in this example)

```php
use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Dimension\DimensionalFormula;

final class Viscosity implements DimensionInterface
{
    private static ?self $instance = null;
    private static ?DimensionalFormula $formula = null;

    private function __construct() {}

    public static function instance(): self
    {
        return self::$instance ??= new self();
    }

    public function getFormula(): DimensionalFormula
    {
        // Dynamic viscosity: M¹L⁻¹T⁻¹ (kg/(m·s))
        return self::$formula ??= new DimensionalFormula(
            mass: 1,
            length: -1,
            time: -1
        );
    }
}
```

### Step 2: Create the Unit Enum

Units are implemented as PHP enums that implement `UnitInterface`. Each unit defines its symbol, name, dimension, and measurement system (SI, Metric, or Imperial). The enum pattern provides type safety and IDE autocompletion.

```php
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Unit\UnitSystem;
use Andante\Measurement\Unit\SymbolNotation;

enum ViscosityUnit: string implements UnitInterface
{
    case PascalSecond = 'Pa·s';
    case Poise = 'P';
    case Centipoise = 'cP';

    public function symbol(SymbolNotation $notation = SymbolNotation::Default): string
    {
        return match ($notation) {
            SymbolNotation::ASCII => match ($this) {
                self::PascalSecond => 'Pa*s',
                self::Poise => 'P',
                self::Centipoise => 'cP',
            },
            default => $this->value,
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::PascalSecond => 'Pascal second',
            self::Poise => 'Poise',
            self::Centipoise => 'Centipoise',
        };
    }

    public function dimension(): DimensionInterface
    {
        return Viscosity::instance();
    }

    public function system(): UnitSystem
    {
        return UnitSystem::SI;
    }
}
```

### Step 3: Create the Quantity Interface

Create an interface that extends `QuantityInterface`. This enables type-hinting for your quantity in function signatures and allows for the three-level type safety pattern (generic → system-specific → unit-specific).

```php
use Andante\Measurement\Contract\QuantityInterface;

interface ViscosityInterface extends QuantityInterface
{
}
```

### Step 4: Create Quantity Classes

Create the actual quantity classes that hold values. Use the provided traits (`ConvertibleTrait`, `ComparableTrait`, `CalculableTrait`, `AutoScalableTrait`) to get conversion, comparison, arithmetic, and auto-scaling functionality without writing boilerplate code.

```php
use Andante\Measurement\Contract\Math\NumberInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Contract\QuantityFactoryInterface;
use Andante\Measurement\Contract\ConvertibleInterface;
use Andante\Measurement\Contract\ComparableInterface;
use Andante\Measurement\Contract\CalculableInterface;
use Andante\Measurement\Quantity\Trait\ConvertibleTrait;
use Andante\Measurement\Quantity\Trait\ComparableTrait;
use Andante\Measurement\Quantity\Trait\CalculableTrait;
use Andante\Measurement\Exception\InvalidUnitException;

// Unit-specific class
final class PascalSecond implements
    ViscosityInterface,
    QuantityFactoryInterface,
    ConvertibleInterface,
    ComparableInterface,
    CalculableInterface
{
    use ConvertibleTrait;
    use ComparableTrait;
    use CalculableTrait;

    private function __construct(
        private readonly NumberInterface $value,
        private readonly UnitInterface $unit,
    ) {}

    public static function of(NumberInterface $value): self
    {
        return new self($value, ViscosityUnit::PascalSecond);
    }

    public static function from(NumberInterface $value, UnitInterface $unit): self
    {
        if (ViscosityUnit::PascalSecond !== $unit) {
            throw InvalidUnitException::forInvalidUnit($unit, ViscosityUnit::PascalSecond, self::class);
        }
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
```

### Step 5: Create a Provider

The provider centralizes all registry configuration for your quantity. It defines which units exist, their conversion factors to the base unit, and how dimensional analysis results should be mapped. This is the recommended pattern used by all built-in quantities.

```php
use Andante\Measurement\Contract\Registry\QuantityDefaultConfigProviderInterface;
use Andante\Measurement\Converter\ConversionRule;
use Andante\Measurement\Math\NumberFactory;

final class ViscosityProvider implements QuantityDefaultConfigProviderInterface
{
    private static ?self $instance = null;

    private function __construct() {}

    public static function global(): self
    {
        return self::$instance ??= new self();
    }

    private function getUnits(): array
    {
        return [
            [ViscosityUnit::PascalSecond, PascalSecond::class, '1'],
            [ViscosityUnit::Poise, Poise::class, '0.1'],
            [ViscosityUnit::Centipoise, Centipoise::class, '0.001'],
        ];
    }

    public function registerUnits(UnitRegistry $registry): void
    {
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, $quantityClass);
        }
    }

    public function registerConversionFactors(ConversionFactorRegistry $registry): void
    {
        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($unit, ConversionRule::factor(NumberFactory::create($factor)));
        }
    }

    public function registerResultMappings(ResultQuantityRegistry $registry): void
    {
        $formula = Viscosity::instance()->getFormula();

        foreach ($this->getUnits() as [$unit, $quantityClass, $factor]) {
            $registry->register($quantityClass, $formula, DynamicViscosity::class);
        }

        $registry->registerGeneric($formula, DynamicViscosity::class);
    }

    public function registerFormulaUnits(FormulaUnitRegistry $registry): void
    {
        $registry->register(
            Viscosity::instance()->getFormula(),
            ViscosityUnit::PascalSecond
        );
    }
}
```

### Step 6: Register with the Library

Call your provider's registration methods during application bootstrap to make your quantity available throughout the library. After registration, parsing, formatting, conversions, and dimensional analysis will all work with your custom quantity.

```php
// In your application bootstrap
ViscosityProvider::global()->registerUnits(UnitRegistry::global());
ViscosityProvider::global()->registerConversionFactors(ConversionFactorRegistry::global());
ViscosityProvider::global()->registerResultMappings(ResultQuantityRegistry::global());
ViscosityProvider::global()->registerFormulaUnits(FormulaUnitRegistry::global());

// Now you can use your custom quantity!
$viscosity = PascalSecond::of(NumberFactory::create('0.001'));
$inCentipoise = $viscosity->to(ViscosityUnit::Centipoise);  // 1 cP
```

### Step 7: Add Translations (Optional)

To enable localized formatting with `FormatStyle::Long`, register translations programmatically using the `TranslationLoader::registerTranslation()` method.

```php
use Andante\Measurement\Translation\TranslationLoader;

// Get or create a translation loader for your locale
$loader = new TranslationLoader('en');

// Register translations for your custom units
$loader->registerTranslation(ViscosityUnit::PascalSecond, [
    'one' => 'pascal second',
    'other' => 'pascal seconds',
]);
$loader->registerTranslation(ViscosityUnit::Poise, [
    'one' => 'poise',
    'other' => 'poise',
]);
$loader->registerTranslation(ViscosityUnit::Centipoise, [
    'one' => 'centipoise',
    'other' => 'centipoise',
]);

// Use the loader with the formatter
$formatter = new Formatter($loader);
```

The `registerTranslation()` method accepts a unit and an array mapping plural rules (`one`, `other`) to translated names. Registered translations take precedence over built-in translations, so you can also use this to override existing unit names if needed.

## Available Quantities

The library provides **29 quantity types** with **200+ units**. Each quantity includes:
- A **generic class** accepting any unit in the dimension
- **Mid-level classes** for system-specific type safety (Metric/Imperial/SI)
- **Unit-specific classes** for maximum type safety

---

### Length [L¹]

The SI base unit of distance. Supports metric and imperial systems.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Length`](src/Quantity/Length/Length.php) | any | - |
| System-specific | [`MetricLength`](src/Quantity/Length/MetricLength.php) | [any metric (`MetricLengthUnit`)](src/Unit/Length/MetricLengthUnit.php) | - |
| System-specific | [`ImperialLength`](src/Quantity/Length/ImperialLength.php) | [any imperial (`ImperialLengthUnit`)](src/Unit/Length/ImperialLengthUnit.php) | - |
| Unit-specific | [`Meter`](src/Quantity/Length/Metric/Meter.php) | [`Meter`](src/Unit/Length/MetricLengthUnit.php) | m |
| Unit-specific | [`Kilometer`](src/Quantity/Length/Metric/Kilometer.php) | [`Kilometer`](src/Unit/Length/MetricLengthUnit.php) | km |
| Unit-specific | [`Hectometer`](src/Quantity/Length/Metric/Hectometer.php) | [`Hectometer`](src/Unit/Length/MetricLengthUnit.php) | hm |
| Unit-specific | [`Decameter`](src/Quantity/Length/Metric/Decameter.php) | [`Decameter`](src/Unit/Length/MetricLengthUnit.php) | dam |
| Unit-specific | [`Decimeter`](src/Quantity/Length/Metric/Decimeter.php) | [`Decimeter`](src/Unit/Length/MetricLengthUnit.php) | dm |
| Unit-specific | [`Centimeter`](src/Quantity/Length/Metric/Centimeter.php) | [`Centimeter`](src/Unit/Length/MetricLengthUnit.php) | cm |
| Unit-specific | [`Millimeter`](src/Quantity/Length/Metric/Millimeter.php) | [`Millimeter`](src/Unit/Length/MetricLengthUnit.php) | mm |
| Unit-specific | [`Micrometer`](src/Quantity/Length/Metric/Micrometer.php) | [`Micrometer`](src/Unit/Length/MetricLengthUnit.php) | μm |
| Unit-specific | [`Nanometer`](src/Quantity/Length/Metric/Nanometer.php) | [`Nanometer`](src/Unit/Length/MetricLengthUnit.php) | nm |
| Unit-specific | [`Foot`](src/Quantity/Length/Imperial/Foot.php) | [`Foot`](src/Unit/Length/ImperialLengthUnit.php) | ft |
| Unit-specific | [`Inch`](src/Quantity/Length/Imperial/Inch.php) | [`Inch`](src/Unit/Length/ImperialLengthUnit.php) | in |
| Unit-specific | [`Yard`](src/Quantity/Length/Imperial/Yard.php) | [`Yard`](src/Unit/Length/ImperialLengthUnit.php) | yd |
| Unit-specific | [`Mile`](src/Quantity/Length/Imperial/Mile.php) | [`Mile`](src/Unit/Length/ImperialLengthUnit.php) | mi |
| Unit-specific | [`NauticalMile`](src/Quantity/Length/Imperial/NauticalMile.php) | [`NauticalMile`](src/Unit/Length/ImperialLengthUnit.php) | nmi |

---

### Mass [M¹]

The SI base unit of mass. Supports metric and imperial systems.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Mass`](src/Quantity/Mass/Mass.php) | any | - |
| System-specific | [`MetricMass`](src/Quantity/Mass/MetricMass.php) | [any metric (`MetricMassUnit`)](src/Unit/Mass/MetricMassUnit.php) | - |
| System-specific | [`ImperialMass`](src/Quantity/Mass/ImperialMass.php) | [any imperial (`ImperialMassUnit`)](src/Unit/Mass/ImperialMassUnit.php) | - |
| Unit-specific | [`Kilogram`](src/Quantity/Mass/Metric/Kilogram.php) | [`Kilogram`](src/Unit/Mass/MetricMassUnit.php) | kg |
| Unit-specific | [`Gram`](src/Quantity/Mass/Metric/Gram.php) | [`Gram`](src/Unit/Mass/MetricMassUnit.php) | g |
| Unit-specific | [`Milligram`](src/Quantity/Mass/Metric/Milligram.php) | [`Milligram`](src/Unit/Mass/MetricMassUnit.php) | mg |
| Unit-specific | [`Microgram`](src/Quantity/Mass/Metric/Microgram.php) | [`Microgram`](src/Unit/Mass/MetricMassUnit.php) | μg |
| Unit-specific | [`Tonne`](src/Quantity/Mass/Metric/Tonne.php) | [`Tonne`](src/Unit/Mass/MetricMassUnit.php) | t |
| Unit-specific | [`Hectogram`](src/Quantity/Mass/Metric/Hectogram.php) | [`Hectogram`](src/Unit/Mass/MetricMassUnit.php) | hg |
| Unit-specific | [`Decagram`](src/Quantity/Mass/Metric/Decagram.php) | [`Decagram`](src/Unit/Mass/MetricMassUnit.php) | dag |
| Unit-specific | [`Decigram`](src/Quantity/Mass/Metric/Decigram.php) | [`Decigram`](src/Unit/Mass/MetricMassUnit.php) | dg |
| Unit-specific | [`Centigram`](src/Quantity/Mass/Metric/Centigram.php) | [`Centigram`](src/Unit/Mass/MetricMassUnit.php) | cg |
| Unit-specific | [`Pound`](src/Quantity/Mass/Imperial/Pound.php) | [`Pound`](src/Unit/Mass/ImperialMassUnit.php) | lb |
| Unit-specific | [`Ounce`](src/Quantity/Mass/Imperial/Ounce.php) | [`Ounce`](src/Unit/Mass/ImperialMassUnit.php) | oz |
| Unit-specific | [`Stone`](src/Quantity/Mass/Imperial/Stone.php) | [`Stone`](src/Unit/Mass/ImperialMassUnit.php) | st |
| Unit-specific | [`ShortTon`](src/Quantity/Mass/Imperial/ShortTon.php) | [`ShortTon`](src/Unit/Mass/ImperialMassUnit.php) | ton |
| Unit-specific | [`LongTon`](src/Quantity/Mass/Imperial/LongTon.php) | [`LongTon`](src/Unit/Mass/ImperialMassUnit.php) | long ton |

---

### Time [T¹]

The SI base unit of time. All Time classes include PHP `DateInterval` integration:

- `toPhpDateInterval()` - convert to PHP `\DateInterval`
- `ofPhpDateInterval(\DateInterval $interval)` - create from PHP `\DateInterval`

```php
// Convert to DateInterval
$hours = Hour::of(NumberFactory::create('2.5'));
$interval = $hours->toPhpDateInterval(); // PT2H30M

// Create from DateInterval
$interval = new \DateInterval('PT1H30M45S');
$seconds = Second::ofPhpDateInterval($interval);
// $seconds->getValue()->value() = '5445'
```

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Time`](src/Quantity/Time/Time.php) | [any (`TimeUnit`)](src/Unit/Time/TimeUnit.php) | - |
| Unit-specific | [`Second`](src/Quantity/Time/Second.php) | [`Second`](src/Unit/Time/TimeUnit.php) | s |
| Unit-specific | [`Millisecond`](src/Quantity/Time/Millisecond.php) | [`Millisecond`](src/Unit/Time/TimeUnit.php) | ms |
| Unit-specific | [`Microsecond`](src/Quantity/Time/Microsecond.php) | [`Microsecond`](src/Unit/Time/TimeUnit.php) | μs |
| Unit-specific | [`Nanosecond`](src/Quantity/Time/Nanosecond.php) | [`Nanosecond`](src/Unit/Time/TimeUnit.php) | ns |
| Unit-specific | [`Minute`](src/Quantity/Time/Minute.php) | [`Minute`](src/Unit/Time/TimeUnit.php) | min |
| Unit-specific | [`Hour`](src/Quantity/Time/Hour.php) | [`Hour`](src/Unit/Time/TimeUnit.php) | h |
| Unit-specific | [`Day`](src/Quantity/Time/Day.php) | [`Day`](src/Unit/Time/TimeUnit.php) | d |
| Unit-specific | [`Week`](src/Quantity/Time/Week.php) | [`Week`](src/Unit/Time/TimeUnit.php) | wk |

---

### Temperature [Θ¹]

Uses affine conversions (factor + offset) for Celsius and Fahrenheit.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Temperature`](src/Quantity/Temperature/Temperature.php) | [any (`TemperatureUnit`)](src/Unit/Temperature/TemperatureUnit.php) | - |
| Unit-specific | [`Kelvin`](src/Quantity/Temperature/Kelvin.php) | [`Kelvin`](src/Unit/Temperature/TemperatureUnit.php) | K |
| Unit-specific | [`Celsius`](src/Quantity/Temperature/Celsius.php) | [`Celsius`](src/Unit/Temperature/TemperatureUnit.php) | °C |
| Unit-specific | [`Fahrenheit`](src/Quantity/Temperature/Fahrenheit.php) | [`Fahrenheit`](src/Unit/Temperature/TemperatureUnit.php) | °F |

---

### Electric Current [I¹]

The SI base unit of electric current.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`ElectricCurrent`](src/Quantity/ElectricCurrent/ElectricCurrent.php) | [any (`ElectricCurrentUnit`)](src/Unit/ElectricCurrent/ElectricCurrentUnit.php) | - |
| Unit-specific | [`Ampere`](src/Quantity/ElectricCurrent/SI/Ampere.php) | [`Ampere`](src/Unit/ElectricCurrent/ElectricCurrentUnit.php) | A |
| Unit-specific | [`Kiloampere`](src/Quantity/ElectricCurrent/SI/Kiloampere.php) | [`Kiloampere`](src/Unit/ElectricCurrent/ElectricCurrentUnit.php) | kA |
| Unit-specific | [`Milliampere`](src/Quantity/ElectricCurrent/SI/Milliampere.php) | [`Milliampere`](src/Unit/ElectricCurrent/ElectricCurrentUnit.php) | mA |
| Unit-specific | [`Microampere`](src/Quantity/ElectricCurrent/SI/Microampere.php) | [`Microampere`](src/Unit/ElectricCurrent/ElectricCurrentUnit.php) | μA |
| Unit-specific | [`Nanoampere`](src/Quantity/ElectricCurrent/SI/Nanoampere.php) | [`Nanoampere`](src/Unit/ElectricCurrent/ElectricCurrentUnit.php) | nA |

---

### Area [L²]

Derived from Length × Length.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Area`](src/Quantity/Area/Area.php) | any | - |
| System-specific | [`MetricArea`](src/Quantity/Area/MetricArea.php) | [any metric (`MetricAreaUnit`)](src/Unit/Area/MetricAreaUnit.php) | - |
| System-specific | [`ImperialArea`](src/Quantity/Area/ImperialArea.php) | [any imperial (`ImperialAreaUnit`)](src/Unit/Area/ImperialAreaUnit.php) | - |
| Unit-specific | [`SquareMeter`](src/Quantity/Area/Metric/SquareMeter.php) | [`SquareMeter`](src/Unit/Area/MetricAreaUnit.php) | m² |
| Unit-specific | [`SquareKilometer`](src/Quantity/Area/Metric/SquareKilometer.php) | [`SquareKilometer`](src/Unit/Area/MetricAreaUnit.php) | km² |
| Unit-specific | [`SquareCentimeter`](src/Quantity/Area/Metric/SquareCentimeter.php) | [`SquareCentimeter`](src/Unit/Area/MetricAreaUnit.php) | cm² |
| Unit-specific | [`SquareMillimeter`](src/Quantity/Area/Metric/SquareMillimeter.php) | [`SquareMillimeter`](src/Unit/Area/MetricAreaUnit.php) | mm² |
| Unit-specific | [`SquareDecimeter`](src/Quantity/Area/Metric/SquareDecimeter.php) | [`SquareDecimeter`](src/Unit/Area/MetricAreaUnit.php) | dm² |
| Unit-specific | [`Hectare`](src/Quantity/Area/Metric/Hectare.php) | [`Hectare`](src/Unit/Area/MetricAreaUnit.php) | ha |
| Unit-specific | [`Are`](src/Quantity/Area/Metric/Are.php) | [`Are`](src/Unit/Area/MetricAreaUnit.php) | a |
| Unit-specific | [`SquareFoot`](src/Quantity/Area/Imperial/SquareFoot.php) | [`SquareFoot`](src/Unit/Area/ImperialAreaUnit.php) | ft² |
| Unit-specific | [`SquareInch`](src/Quantity/Area/Imperial/SquareInch.php) | [`SquareInch`](src/Unit/Area/ImperialAreaUnit.php) | in² |
| Unit-specific | [`SquareYard`](src/Quantity/Area/Imperial/SquareYard.php) | [`SquareYard`](src/Unit/Area/ImperialAreaUnit.php) | yd² |
| Unit-specific | [`SquareMile`](src/Quantity/Area/Imperial/SquareMile.php) | [`SquareMile`](src/Unit/Area/ImperialAreaUnit.php) | mi² |
| Unit-specific | [`Acre`](src/Quantity/Area/Imperial/Acre.php) | [`Acre`](src/Unit/Area/ImperialAreaUnit.php) | ac |

---

### Volume [L³]

Derived from Length × Length × Length. Includes gas measurement units.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Volume`](src/Quantity/Volume/Volume.php) | any | - |
| System-specific | [`MetricVolume`](src/Quantity/Volume/MetricVolume.php) | [any metric (`MetricVolumeUnit`)](src/Unit/Volume/MetricVolumeUnit.php) | - |
| System-specific | [`ImperialVolume`](src/Quantity/Volume/ImperialVolume.php) | [any imperial (`ImperialVolumeUnit`)](src/Unit/Volume/ImperialVolumeUnit.php) | - |
| System-specific | [`GasVolume`](src/Quantity/Volume/GasVolume.php) | [gas measurement (`GasVolumeUnit`)](src/Unit/Volume/GasVolumeUnit.php) | - |
| Unit-specific | [`CubicMeter`](src/Quantity/Volume/Metric/CubicMeter.php) | [`CubicMeter`](src/Unit/Volume/MetricVolumeUnit.php) | m³ |
| Unit-specific | [`CubicDecimeter`](src/Quantity/Volume/Metric/CubicDecimeter.php) | [`CubicDecimeter`](src/Unit/Volume/MetricVolumeUnit.php) | dm³ |
| Unit-specific | [`CubicCentimeter`](src/Quantity/Volume/Metric/CubicCentimeter.php) | [`CubicCentimeter`](src/Unit/Volume/MetricVolumeUnit.php) | cm³ |
| Unit-specific | [`CubicMillimeter`](src/Quantity/Volume/Metric/CubicMillimeter.php) | [`CubicMillimeter`](src/Unit/Volume/MetricVolumeUnit.php) | mm³ |
| Unit-specific | [`Liter`](src/Quantity/Volume/Metric/Liter.php) | [`Liter`](src/Unit/Volume/MetricVolumeUnit.php) | L |
| Unit-specific | [`Deciliter`](src/Quantity/Volume/Metric/Deciliter.php) | [`Deciliter`](src/Unit/Volume/MetricVolumeUnit.php) | dL |
| Unit-specific | [`Centiliter`](src/Quantity/Volume/Metric/Centiliter.php) | [`Centiliter`](src/Unit/Volume/MetricVolumeUnit.php) | cL |
| Unit-specific | [`Milliliter`](src/Quantity/Volume/Metric/Milliliter.php) | [`Milliliter`](src/Unit/Volume/MetricVolumeUnit.php) | mL |
| Unit-specific | [`Hectoliter`](src/Quantity/Volume/Metric/Hectoliter.php) | [`Hectoliter`](src/Unit/Volume/MetricVolumeUnit.php) | hL |
| Unit-specific | [`Kiloliter`](src/Quantity/Volume/Metric/Kiloliter.php) | [`Kiloliter`](src/Unit/Volume/MetricVolumeUnit.php) | kL |
| Unit-specific | [`CubicFoot`](src/Quantity/Volume/Imperial/CubicFoot.php) | [`CubicFoot`](src/Unit/Volume/ImperialVolumeUnit.php) | ft³ |
| Unit-specific | [`CubicInch`](src/Quantity/Volume/Imperial/CubicInch.php) | [`CubicInch`](src/Unit/Volume/ImperialVolumeUnit.php) | in³ |
| Unit-specific | [`CubicYard`](src/Quantity/Volume/Imperial/CubicYard.php) | [`CubicYard`](src/Unit/Volume/ImperialVolumeUnit.php) | yd³ |
| Unit-specific | [`USGallon`](src/Quantity/Volume/Imperial/USGallon.php) | [`USGallon`](src/Unit/Volume/ImperialVolumeUnit.php) | gal |
| Unit-specific | [`USQuart`](src/Quantity/Volume/Imperial/USQuart.php) | [`USQuart`](src/Unit/Volume/ImperialVolumeUnit.php) | qt |
| Unit-specific | [`USPint`](src/Quantity/Volume/Imperial/USPint.php) | [`USPint`](src/Unit/Volume/ImperialVolumeUnit.php) | pt |
| Unit-specific | [`USCup`](src/Quantity/Volume/Imperial/USCup.php) | [`USCup`](src/Unit/Volume/ImperialVolumeUnit.php) | cup |
| Unit-specific | [`USFluidOunce`](src/Quantity/Volume/Imperial/USFluidOunce.php) | [`USFluidOunce`](src/Unit/Volume/ImperialVolumeUnit.php) | fl oz |
| Unit-specific | [`USTablespoon`](src/Quantity/Volume/Imperial/USTablespoon.php) | [`USTablespoon`](src/Unit/Volume/ImperialVolumeUnit.php) | tbsp |
| Unit-specific | [`USTeaspoon`](src/Quantity/Volume/Imperial/USTeaspoon.php) | [`USTeaspoon`](src/Unit/Volume/ImperialVolumeUnit.php) | tsp |
| Unit-specific | [`ImperialGallon`](src/Quantity/Volume/Imperial/ImperialGallon.php) | [`ImperialGallon`](src/Unit/Volume/ImperialVolumeUnit.php) | imp gal |
| Unit-specific | [`ImperialQuart`](src/Quantity/Volume/Imperial/ImperialQuart.php) | [`ImperialQuart`](src/Unit/Volume/ImperialVolumeUnit.php) | imp qt |
| Unit-specific | [`ImperialPint`](src/Quantity/Volume/Imperial/ImperialPint.php) | [`ImperialPint`](src/Unit/Volume/ImperialVolumeUnit.php) | imp pt |
| Unit-specific | [`ImperialFluidOunce`](src/Quantity/Volume/Imperial/ImperialFluidOunce.php) | [`ImperialFluidOunce`](src/Unit/Volume/ImperialVolumeUnit.php) | imp fl oz |
| Unit-specific | [`StandardCubicMeter`](src/Quantity/Volume/Gas/StandardCubicMeter.php) | [`StandardCubicMeter`](src/Unit/Volume/GasVolumeUnit.php) | Smc |
| Unit-specific | [`NormalCubicMeter`](src/Quantity/Volume/Gas/NormalCubicMeter.php) | [`NormalCubicMeter`](src/Unit/Volume/GasVolumeUnit.php) | Nmc |
| Unit-specific | [`StandardCubicFoot`](src/Quantity/Volume/Gas/StandardCubicFoot.php) | [`StandardCubicFoot`](src/Unit/Volume/GasVolumeUnit.php) | scf |
| Unit-specific | [`ThousandCubicFeet`](src/Quantity/Volume/Gas/ThousandCubicFeet.php) | [`ThousandCubicFeet`](src/Unit/Volume/GasVolumeUnit.php) | Mcf |

---

### Velocity [L¹T⁻¹]

Derived from Length ÷ Time.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Velocity`](src/Quantity/Velocity/Velocity.php) | any | - |
| System-specific | [`MetricVelocity`](src/Quantity/Velocity/MetricVelocity.php) | [any metric (`MetricVelocityUnit`)](src/Unit/Velocity/MetricVelocityUnit.php) | - |
| System-specific | [`ImperialVelocity`](src/Quantity/Velocity/ImperialVelocity.php) | [any imperial (`ImperialVelocityUnit`)](src/Unit/Velocity/ImperialVelocityUnit.php) | - |
| Unit-specific | [`MeterPerSecond`](src/Quantity/Velocity/Metric/MeterPerSecond.php) | [`MeterPerSecond`](src/Unit/Velocity/MetricVelocityUnit.php) | m/s |
| Unit-specific | [`KilometerPerHour`](src/Quantity/Velocity/Metric/KilometerPerHour.php) | [`KilometerPerHour`](src/Unit/Velocity/MetricVelocityUnit.php) | km/h |
| Unit-specific | [`CentimeterPerSecond`](src/Quantity/Velocity/Metric/CentimeterPerSecond.php) | [`CentimeterPerSecond`](src/Unit/Velocity/MetricVelocityUnit.php) | cm/s |
| Unit-specific | [`MillimeterPerSecond`](src/Quantity/Velocity/Metric/MillimeterPerSecond.php) | [`MillimeterPerSecond`](src/Unit/Velocity/MetricVelocityUnit.php) | mm/s |
| Unit-specific | [`MilePerHour`](src/Quantity/Velocity/Imperial/MilePerHour.php) | [`MilePerHour`](src/Unit/Velocity/ImperialVelocityUnit.php) | mph |
| Unit-specific | [`FootPerSecond`](src/Quantity/Velocity/Imperial/FootPerSecond.php) | [`FootPerSecond`](src/Unit/Velocity/ImperialVelocityUnit.php) | ft/s |
| Unit-specific | [`Knot`](src/Quantity/Velocity/Imperial/Knot.php) | [`Knot`](src/Unit/Velocity/ImperialVelocityUnit.php) | kn |

---

### Acceleration [L¹T⁻²]

Derived from Velocity ÷ Time.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Acceleration`](src/Quantity/Acceleration/Acceleration.php) | any | - |
| System-specific | [`MetricAcceleration`](src/Quantity/Acceleration/MetricAcceleration.php) | [any metric (`MetricAccelerationUnit`)](src/Unit/Acceleration/MetricAccelerationUnit.php) | - |
| System-specific | [`ImperialAcceleration`](src/Quantity/Acceleration/ImperialAcceleration.php) | [any imperial (`ImperialAccelerationUnit`)](src/Unit/Acceleration/ImperialAccelerationUnit.php) | - |
| Unit-specific | [`MeterPerSecondSquared`](src/Quantity/Acceleration/Metric/MeterPerSecondSquared.php) | [`MeterPerSecondSquared`](src/Unit/Acceleration/MetricAccelerationUnit.php) | m/s² |
| Unit-specific | [`CentimeterPerSecondSquared`](src/Quantity/Acceleration/Metric/CentimeterPerSecondSquared.php) | [`CentimeterPerSecondSquared`](src/Unit/Acceleration/MetricAccelerationUnit.php) | cm/s² |
| Unit-specific | [`MillimeterPerSecondSquared`](src/Quantity/Acceleration/Metric/MillimeterPerSecondSquared.php) | [`MillimeterPerSecondSquared`](src/Unit/Acceleration/MetricAccelerationUnit.php) | mm/s² |
| Unit-specific | [`Gal`](src/Quantity/Acceleration/Metric/Gal.php) | [`Gal`](src/Unit/Acceleration/MetricAccelerationUnit.php) | Gal |
| Unit-specific | [`StandardGravity`](src/Quantity/Acceleration/Metric/StandardGravity.php) | [`StandardGravity`](src/Unit/Acceleration/MetricAccelerationUnit.php) | g |
| Unit-specific | [`FootPerSecondSquared`](src/Quantity/Acceleration/Imperial/FootPerSecondSquared.php) | [`FootPerSecondSquared`](src/Unit/Acceleration/ImperialAccelerationUnit.php) | ft/s² |
| Unit-specific | [`InchPerSecondSquared`](src/Quantity/Acceleration/Imperial/InchPerSecondSquared.php) | [`InchPerSecondSquared`](src/Unit/Acceleration/ImperialAccelerationUnit.php) | in/s² |

---

### Force [L¹M¹T⁻²]

Derived from Mass × Acceleration.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Force`](src/Quantity/Force/Force.php) | any | - |
| System-specific | [`SIForce`](src/Quantity/Force/SIForce.php) | [any SI (`SIForceUnit`)](src/Unit/Force/SIForceUnit.php) | - |
| System-specific | [`ImperialForce`](src/Quantity/Force/ImperialForce.php) | [any imperial (`ImperialForceUnit`)](src/Unit/Force/ImperialForceUnit.php) | - |
| Unit-specific | [`Newton`](src/Quantity/Force/SI/Newton.php) | [`Newton`](src/Unit/Force/SIForceUnit.php) | N |
| Unit-specific | [`Kilonewton`](src/Quantity/Force/SI/Kilonewton.php) | [`Kilonewton`](src/Unit/Force/SIForceUnit.php) | kN |
| Unit-specific | [`Meganewton`](src/Quantity/Force/SI/Meganewton.php) | [`Meganewton`](src/Unit/Force/SIForceUnit.php) | MN |
| Unit-specific | [`Millinewton`](src/Quantity/Force/SI/Millinewton.php) | [`Millinewton`](src/Unit/Force/SIForceUnit.php) | mN |
| Unit-specific | [`Micronewton`](src/Quantity/Force/SI/Micronewton.php) | [`Micronewton`](src/Unit/Force/SIForceUnit.php) | μN |
| Unit-specific | [`Dyne`](src/Quantity/Force/SI/Dyne.php) | [`Dyne`](src/Unit/Force/SIForceUnit.php) | dyn |
| Unit-specific | [`PoundForce`](src/Quantity/Force/Imperial/PoundForce.php) | [`PoundForce`](src/Unit/Force/ImperialForceUnit.php) | lbf |
| Unit-specific | [`OunceForce`](src/Quantity/Force/Imperial/OunceForce.php) | [`OunceForce`](src/Unit/Force/ImperialForceUnit.php) | ozf |
| Unit-specific | [`Kip`](src/Quantity/Force/Imperial/Kip.php) | [`Kip`](src/Unit/Force/ImperialForceUnit.php) | kip |
| Unit-specific | [`Poundal`](src/Quantity/Force/Imperial/Poundal.php) | [`Poundal`](src/Unit/Force/ImperialForceUnit.php) | pdl |

---

### Pressure [L⁻¹M¹T⁻²]

Derived from Force ÷ Area.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Pressure`](src/Quantity/Pressure/Pressure.php) | any | - |
| System-specific | [`SIPressure`](src/Quantity/Pressure/SIPressure.php) | [any SI (`SIPressureUnit`)](src/Unit/Pressure/SIPressureUnit.php) | - |
| System-specific | [`ImperialPressure`](src/Quantity/Pressure/ImperialPressure.php) | [any imperial (`ImperialPressureUnit`)](src/Unit/Pressure/ImperialPressureUnit.php) | - |
| Unit-specific | [`Pascal`](src/Quantity/Pressure/SI/Pascal.php) | [`Pascal`](src/Unit/Pressure/SIPressureUnit.php) | Pa |
| Unit-specific | [`Hectopascal`](src/Quantity/Pressure/SI/Hectopascal.php) | [`Hectopascal`](src/Unit/Pressure/SIPressureUnit.php) | hPa |
| Unit-specific | [`Kilopascal`](src/Quantity/Pressure/SI/Kilopascal.php) | [`Kilopascal`](src/Unit/Pressure/SIPressureUnit.php) | kPa |
| Unit-specific | [`Megapascal`](src/Quantity/Pressure/SI/Megapascal.php) | [`Megapascal`](src/Unit/Pressure/SIPressureUnit.php) | MPa |
| Unit-specific | [`Gigapascal`](src/Quantity/Pressure/SI/Gigapascal.php) | [`Gigapascal`](src/Unit/Pressure/SIPressureUnit.php) | GPa |
| Unit-specific | [`Bar`](src/Quantity/Pressure/SI/Bar.php) | [`Bar`](src/Unit/Pressure/SIPressureUnit.php) | bar |
| Unit-specific | [`Millibar`](src/Quantity/Pressure/SI/Millibar.php) | [`Millibar`](src/Unit/Pressure/SIPressureUnit.php) | mbar |
| Unit-specific | [`Atmosphere`](src/Quantity/Pressure/SI/Atmosphere.php) | [`Atmosphere`](src/Unit/Pressure/SIPressureUnit.php) | atm |
| Unit-specific | [`Torr`](src/Quantity/Pressure/SI/Torr.php) | [`Torr`](src/Unit/Pressure/SIPressureUnit.php) | Torr |
| Unit-specific | [`PoundPerSquareInch`](src/Quantity/Pressure/Imperial/PoundPerSquareInch.php) | [`PoundPerSquareInch`](src/Unit/Pressure/ImperialPressureUnit.php) | psi |
| Unit-specific | [`PoundPerSquareFoot`](src/Quantity/Pressure/Imperial/PoundPerSquareFoot.php) | [`PoundPerSquareFoot`](src/Unit/Pressure/ImperialPressureUnit.php) | psf |
| Unit-specific | [`InchOfMercury`](src/Quantity/Pressure/Imperial/InchOfMercury.php) | [`InchOfMercury`](src/Unit/Pressure/ImperialPressureUnit.php) | inHg |
| Unit-specific | [`InchOfWater`](src/Quantity/Pressure/Imperial/InchOfWater.php) | [`InchOfWater`](src/Unit/Pressure/ImperialPressureUnit.php) | inH₂O |

---

### Energy [L²M¹T⁻²]

Work and heat. Includes electrical and thermal units.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Energy`](src/Quantity/Energy/Energy.php) | any | - |
| System-specific | [`SIEnergy`](src/Quantity/Energy/SIEnergy.php) | [any SI (`SIEnergyUnit`)](src/Unit/Energy/SIEnergyUnit.php) | - |
| System-specific | [`ElectricEnergy`](src/Quantity/Energy/ElectricEnergy.php) | [any electric (`ElectricEnergyUnit`)](src/Unit/Energy/ElectricEnergyUnit.php) | - |
| System-specific | [`ThermalEnergy`](src/Quantity/Energy/ThermalEnergy.php) | [any thermal (`ThermalEnergyUnit`)](src/Unit/Energy/ThermalEnergyUnit.php) | - |
| Unit-specific | [`Joule`](src/Quantity/Energy/SI/Joule.php) | [`Joule`](src/Unit/Energy/SIEnergyUnit.php) | J |
| Unit-specific | [`Kilojoule`](src/Quantity/Energy/SI/Kilojoule.php) | [`Kilojoule`](src/Unit/Energy/SIEnergyUnit.php) | kJ |
| Unit-specific | [`Megajoule`](src/Quantity/Energy/SI/Megajoule.php) | [`Megajoule`](src/Unit/Energy/SIEnergyUnit.php) | MJ |
| Unit-specific | [`WattHour`](src/Quantity/Energy/Electric/WattHour.php) | [`WattHour`](src/Unit/Energy/ElectricEnergyUnit.php) | Wh |
| Unit-specific | [`KilowattHour`](src/Quantity/Energy/Electric/KilowattHour.php) | [`KilowattHour`](src/Unit/Energy/ElectricEnergyUnit.php) | kWh |
| Unit-specific | [`MegawattHour`](src/Quantity/Energy/Electric/MegawattHour.php) | [`MegawattHour`](src/Unit/Energy/ElectricEnergyUnit.php) | MWh |
| Unit-specific | [`GigawattHour`](src/Quantity/Energy/Electric/GigawattHour.php) | [`GigawattHour`](src/Unit/Energy/ElectricEnergyUnit.php) | GWh |
| Unit-specific | [`Calorie`](src/Quantity/Energy/Thermal/Calorie.php) | [`Calorie`](src/Unit/Energy/ThermalEnergyUnit.php) | cal |
| Unit-specific | [`Kilocalorie`](src/Quantity/Energy/Thermal/Kilocalorie.php) | [`Kilocalorie`](src/Unit/Energy/ThermalEnergyUnit.php) | kcal |
| Unit-specific | [`BritishThermalUnit`](src/Quantity/Energy/Thermal/BritishThermalUnit.php) | [`BritishThermalUnit`](src/Unit/Energy/ThermalEnergyUnit.php) | BTU |

---

### Power [L²M¹T⁻³]

Derived from Energy ÷ Time.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Power`](src/Quantity/Power/Power.php) | any | - |
| System-specific | [`SIPower`](src/Quantity/Power/SIPower.php) | [any SI (`SIPowerUnit`)](src/Unit/Power/SIPowerUnit.php) | - |
| System-specific | [`ImperialPower`](src/Quantity/Power/ImperialPower.php) | [any imperial (`ImperialPowerUnit`)](src/Unit/Power/ImperialPowerUnit.php) | - |
| Unit-specific | [`Watt`](src/Quantity/Power/SI/Watt.php) | [`Watt`](src/Unit/Power/SIPowerUnit.php) | W |
| Unit-specific | [`Milliwatt`](src/Quantity/Power/SI/Milliwatt.php) | [`Milliwatt`](src/Unit/Power/SIPowerUnit.php) | mW |
| Unit-specific | [`Kilowatt`](src/Quantity/Power/SI/Kilowatt.php) | [`Kilowatt`](src/Unit/Power/SIPowerUnit.php) | kW |
| Unit-specific | [`Megawatt`](src/Quantity/Power/SI/Megawatt.php) | [`Megawatt`](src/Unit/Power/SIPowerUnit.php) | MW |
| Unit-specific | [`Gigawatt`](src/Quantity/Power/SI/Gigawatt.php) | [`Gigawatt`](src/Unit/Power/SIPowerUnit.php) | GW |
| Unit-specific | [`MechanicalHorsepower`](src/Quantity/Power/Imperial/MechanicalHorsepower.php) | [`MechanicalHorsepower`](src/Unit/Power/ImperialPowerUnit.php) | hp |
| Unit-specific | [`ElectricalHorsepower`](src/Quantity/Power/Imperial/ElectricalHorsepower.php) | [`ElectricalHorsepower`](src/Unit/Power/ImperialPowerUnit.php) | hp(E) |
| Unit-specific | [`MetricHorsepower`](src/Quantity/Power/Imperial/MetricHorsepower.php) | [`MetricHorsepower`](src/Unit/Power/ImperialPowerUnit.php) | PS |
| Unit-specific | [`FootPoundPerSecond`](src/Quantity/Power/Imperial/FootPoundPerSecond.php) | [`FootPoundPerSecond`](src/Unit/Power/ImperialPowerUnit.php) | ft⋅lbf/s |
| Unit-specific | [`BTUPerHour`](src/Quantity/Power/Imperial/BTUPerHour.php) | [`BTUPerHour`](src/Unit/Power/ImperialPowerUnit.php) | BTU/h |

---

### Density [L⁻³M¹]

Derived from Mass ÷ Volume.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Density`](src/Quantity/Density/Density.php) | any | - |
| System-specific | [`SIDensity`](src/Quantity/Density/SIDensity.php) | [any SI (`SIDensityUnit`)](src/Unit/Density/SIDensityUnit.php) | - |
| System-specific | [`ImperialDensity`](src/Quantity/Density/ImperialDensity.php) | [any imperial (`ImperialDensityUnit`)](src/Unit/Density/ImperialDensityUnit.php) | - |
| Unit-specific | [`KilogramPerCubicMeter`](src/Quantity/Density/SI/KilogramPerCubicMeter.php) | [`KilogramPerCubicMeter`](src/Unit/Density/SIDensityUnit.php) | kg/m³ |
| Unit-specific | [`GramPerCubicMeter`](src/Quantity/Density/SI/GramPerCubicMeter.php) | [`GramPerCubicMeter`](src/Unit/Density/SIDensityUnit.php) | g/m³ |
| Unit-specific | [`GramPerCubicCentimeter`](src/Quantity/Density/SI/GramPerCubicCentimeter.php) | [`GramPerCubicCentimeter`](src/Unit/Density/SIDensityUnit.php) | g/cm³ |
| Unit-specific | [`GramPerLiter`](src/Quantity/Density/SI/GramPerLiter.php) | [`GramPerLiter`](src/Unit/Density/SIDensityUnit.php) | g/L |
| Unit-specific | [`KilogramPerLiter`](src/Quantity/Density/SI/KilogramPerLiter.php) | [`KilogramPerLiter`](src/Unit/Density/SIDensityUnit.php) | kg/L |
| Unit-specific | [`MilligramPerCubicMeter`](src/Quantity/Density/SI/MilligramPerCubicMeter.php) | [`MilligramPerCubicMeter`](src/Unit/Density/SIDensityUnit.php) | mg/m³ |
| Unit-specific | [`TonnePerCubicMeter`](src/Quantity/Density/SI/TonnePerCubicMeter.php) | [`TonnePerCubicMeter`](src/Unit/Density/SIDensityUnit.php) | t/m³ |
| Unit-specific | [`PoundPerCubicFoot`](src/Quantity/Density/Imperial/PoundPerCubicFoot.php) | [`PoundPerCubicFoot`](src/Unit/Density/ImperialDensityUnit.php) | lb/ft³ |
| Unit-specific | [`PoundPerCubicInch`](src/Quantity/Density/Imperial/PoundPerCubicInch.php) | [`PoundPerCubicInch`](src/Unit/Density/ImperialDensityUnit.php) | lb/in³ |
| Unit-specific | [`PoundPerGallon`](src/Quantity/Density/Imperial/PoundPerGallon.php) | [`PoundPerGallon`](src/Unit/Density/ImperialDensityUnit.php) | lb/gal |
| Unit-specific | [`OuncePerCubicInch`](src/Quantity/Density/Imperial/OuncePerCubicInch.php) | [`OuncePerCubicInch`](src/Unit/Density/ImperialDensityUnit.php) | oz/in³ |
| Unit-specific | [`SlugPerCubicFoot`](src/Quantity/Density/Imperial/SlugPerCubicFoot.php) | [`SlugPerCubicFoot`](src/Unit/Density/ImperialDensityUnit.php) | slug/ft³ |

---

### Frequency [T⁻¹]

Derived from 1 ÷ Time.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Frequency`](src/Quantity/Frequency/Frequency.php) | [any (`FrequencyUnit`)](src/Unit/Frequency/FrequencyUnit.php) | - |
| Unit-specific | [`Hertz`](src/Quantity/Frequency/SI/Hertz.php) | [`Hertz`](src/Unit/Frequency/FrequencyUnit.php) | Hz |
| Unit-specific | [`Millihertz`](src/Quantity/Frequency/SI/Millihertz.php) | [`Millihertz`](src/Unit/Frequency/FrequencyUnit.php) | mHz |
| Unit-specific | [`Kilohertz`](src/Quantity/Frequency/SI/Kilohertz.php) | [`Kilohertz`](src/Unit/Frequency/FrequencyUnit.php) | kHz |
| Unit-specific | [`Megahertz`](src/Quantity/Frequency/SI/Megahertz.php) | [`Megahertz`](src/Unit/Frequency/FrequencyUnit.php) | MHz |
| Unit-specific | [`Gigahertz`](src/Quantity/Frequency/SI/Gigahertz.php) | [`Gigahertz`](src/Unit/Frequency/FrequencyUnit.php) | GHz |
| Unit-specific | [`Terahertz`](src/Quantity/Frequency/SI/Terahertz.php) | [`Terahertz`](src/Unit/Frequency/FrequencyUnit.php) | THz |
| Unit-specific | [`RevolutionPerMinute`](src/Quantity/Frequency/SI/RevolutionPerMinute.php) | [`RevolutionPerMinute`](src/Unit/Frequency/FrequencyUnit.php) | RPM |
| Unit-specific | [`RevolutionPerSecond`](src/Quantity/Frequency/SI/RevolutionPerSecond.php) | [`RevolutionPerSecond`](src/Unit/Frequency/FrequencyUnit.php) | RPS |
| Unit-specific | [`BeatsPerMinute`](src/Quantity/Frequency/SI/BeatsPerMinute.php) | [`BeatsPerMinute`](src/Unit/Frequency/FrequencyUnit.php) | BPM |

---

### Angle [dimensionless]

Plane angle measurement.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Angle`](src/Quantity/Angle/Angle.php) | [any (`AngleUnit`)](src/Unit/Angle/AngleUnit.php) | - |
| Unit-specific | [`Radian`](src/Quantity/Angle/SI/Radian.php) | [`Radian`](src/Unit/Angle/AngleUnit.php) | rad |
| Unit-specific | [`Milliradian`](src/Quantity/Angle/SI/Milliradian.php) | [`Milliradian`](src/Unit/Angle/AngleUnit.php) | mrad |
| Unit-specific | [`Degree`](src/Quantity/Angle/SI/Degree.php) | [`Degree`](src/Unit/Angle/AngleUnit.php) | ° |
| Unit-specific | [`Arcminute`](src/Quantity/Angle/SI/Arcminute.php) | [`Arcminute`](src/Unit/Angle/AngleUnit.php) | ′ |
| Unit-specific | [`Arcsecond`](src/Quantity/Angle/SI/Arcsecond.php) | [`Arcsecond`](src/Unit/Angle/AngleUnit.php) | ″ |
| Unit-specific | [`Gradian`](src/Quantity/Angle/SI/Gradian.php) | [`Gradian`](src/Unit/Angle/AngleUnit.php) | gon |
| Unit-specific | [`Revolution`](src/Quantity/Angle/SI/Revolution.php) | [`Revolution`](src/Unit/Angle/AngleUnit.php) | rev |
| Unit-specific | [`Turn`](src/Quantity/Angle/SI/Turn.php) | [`Turn`](src/Unit/Angle/AngleUnit.php) | tr |

---

### Electric Potential [L²M¹T⁻³I⁻¹]

Voltage. Derived from Power ÷ Current.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`ElectricPotential`](src/Quantity/ElectricPotential/ElectricPotential.php) | [any (`ElectricPotentialUnit`)](src/Unit/ElectricPotential/ElectricPotentialUnit.php) | - |
| Unit-specific | [`Volt`](src/Quantity/ElectricPotential/SI/Volt.php) | [`Volt`](src/Unit/ElectricPotential/ElectricPotentialUnit.php) | V |
| Unit-specific | [`Megavolt`](src/Quantity/ElectricPotential/SI/Megavolt.php) | [`Megavolt`](src/Unit/ElectricPotential/ElectricPotentialUnit.php) | MV |
| Unit-specific | [`Kilovolt`](src/Quantity/ElectricPotential/SI/Kilovolt.php) | [`Kilovolt`](src/Unit/ElectricPotential/ElectricPotentialUnit.php) | kV |
| Unit-specific | [`Millivolt`](src/Quantity/ElectricPotential/SI/Millivolt.php) | [`Millivolt`](src/Unit/ElectricPotential/ElectricPotentialUnit.php) | mV |
| Unit-specific | [`Microvolt`](src/Quantity/ElectricPotential/SI/Microvolt.php) | [`Microvolt`](src/Unit/ElectricPotential/ElectricPotentialUnit.php) | μV |

---

### Electric Resistance [L²M¹T⁻³I⁻²]

Derived from Voltage ÷ Current.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`ElectricResistance`](src/Quantity/ElectricResistance/ElectricResistance.php) | [any (`ElectricResistanceUnit`)](src/Unit/ElectricResistance/ElectricResistanceUnit.php) | - |
| Unit-specific | [`Ohm`](src/Quantity/ElectricResistance/SI/Ohm.php) | [`Ohm`](src/Unit/ElectricResistance/ElectricResistanceUnit.php) | Ω |
| Unit-specific | [`Megaohm`](src/Quantity/ElectricResistance/SI/Megaohm.php) | [`Megaohm`](src/Unit/ElectricResistance/ElectricResistanceUnit.php) | MΩ |
| Unit-specific | [`Kiloohm`](src/Quantity/ElectricResistance/SI/Kiloohm.php) | [`Kiloohm`](src/Unit/ElectricResistance/ElectricResistanceUnit.php) | kΩ |
| Unit-specific | [`Milliohm`](src/Quantity/ElectricResistance/SI/Milliohm.php) | [`Milliohm`](src/Unit/ElectricResistance/ElectricResistanceUnit.php) | mΩ |
| Unit-specific | [`Microohm`](src/Quantity/ElectricResistance/SI/Microohm.php) | [`Microohm`](src/Unit/ElectricResistance/ElectricResistanceUnit.php) | μΩ |

---

### Electric Capacitance [L⁻²M⁻¹T⁴I²]

Ability to store electric charge.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`ElectricCapacitance`](src/Quantity/ElectricCapacitance/ElectricCapacitance.php) | [any (`ElectricCapacitanceUnit`)](src/Unit/ElectricCapacitance/ElectricCapacitanceUnit.php) | - |
| Unit-specific | [`Farad`](src/Quantity/ElectricCapacitance/SI/Farad.php) | [`Farad`](src/Unit/ElectricCapacitance/ElectricCapacitanceUnit.php) | F |
| Unit-specific | [`Millifarad`](src/Quantity/ElectricCapacitance/SI/Millifarad.php) | [`Millifarad`](src/Unit/ElectricCapacitance/ElectricCapacitanceUnit.php) | mF |
| Unit-specific | [`Microfarad`](src/Quantity/ElectricCapacitance/SI/Microfarad.php) | [`Microfarad`](src/Unit/ElectricCapacitance/ElectricCapacitanceUnit.php) | μF |
| Unit-specific | [`Nanofarad`](src/Quantity/ElectricCapacitance/SI/Nanofarad.php) | [`Nanofarad`](src/Unit/ElectricCapacitance/ElectricCapacitanceUnit.php) | nF |
| Unit-specific | [`Picofarad`](src/Quantity/ElectricCapacitance/SI/Picofarad.php) | [`Picofarad`](src/Unit/ElectricCapacitance/ElectricCapacitanceUnit.php) | pF |

---

### Electric Charge [T¹I¹]

Derived from Current × Time.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`ElectricCharge`](src/Quantity/ElectricCharge/ElectricCharge.php) | [any (`ElectricChargeUnit`)](src/Unit/ElectricCharge/ElectricChargeUnit.php) | - |
| Unit-specific | [`Coulomb`](src/Quantity/ElectricCharge/SI/Coulomb.php) | [`Coulomb`](src/Unit/ElectricCharge/ElectricChargeUnit.php) | C |
| Unit-specific | [`Millicoulomb`](src/Quantity/ElectricCharge/SI/Millicoulomb.php) | [`Millicoulomb`](src/Unit/ElectricCharge/ElectricChargeUnit.php) | mC |
| Unit-specific | [`Microcoulomb`](src/Quantity/ElectricCharge/SI/Microcoulomb.php) | [`Microcoulomb`](src/Unit/ElectricCharge/ElectricChargeUnit.php) | μC |
| Unit-specific | [`AmpereHour`](src/Quantity/ElectricCharge/SI/AmpereHour.php) | [`AmpereHour`](src/Unit/ElectricCharge/ElectricChargeUnit.php) | Ah |
| Unit-specific | [`MilliampereHour`](src/Quantity/ElectricCharge/SI/MilliampereHour.php) | [`MilliampereHour`](src/Unit/ElectricCharge/ElectricChargeUnit.php) | mAh |

---

### Inductance [L²M¹T⁻²I⁻²]

Property of an electrical conductor.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Inductance`](src/Quantity/Inductance/Inductance.php) | [any (`InductanceUnit`)](src/Unit/Inductance/InductanceUnit.php) | - |
| Unit-specific | [`Henry`](src/Quantity/Inductance/SI/Henry.php) | [`Henry`](src/Unit/Inductance/InductanceUnit.php) | H |
| Unit-specific | [`Millihenry`](src/Quantity/Inductance/SI/Millihenry.php) | [`Millihenry`](src/Unit/Inductance/InductanceUnit.php) | mH |
| Unit-specific | [`Microhenry`](src/Quantity/Inductance/SI/Microhenry.php) | [`Microhenry`](src/Unit/Inductance/InductanceUnit.php) | μH |
| Unit-specific | [`Nanohenry`](src/Quantity/Inductance/SI/Nanohenry.php) | [`Nanohenry`](src/Unit/Inductance/InductanceUnit.php) | nH |

---

### Magnetic Flux [L²M¹T⁻²I⁻¹]

Measure of total magnetic field through a surface.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`MagneticFlux`](src/Quantity/MagneticFlux/MagneticFlux.php) | [any (`MagneticFluxUnit`)](src/Unit/MagneticFlux/MagneticFluxUnit.php) | - |
| Unit-specific | [`Weber`](src/Quantity/MagneticFlux/SI/Weber.php) | [`Weber`](src/Unit/MagneticFlux/MagneticFluxUnit.php) | Wb |
| Unit-specific | [`Milliweber`](src/Quantity/MagneticFlux/SI/Milliweber.php) | [`Milliweber`](src/Unit/MagneticFlux/MagneticFluxUnit.php) | mWb |
| Unit-specific | [`Microweber`](src/Quantity/MagneticFlux/SI/Microweber.php) | [`Microweber`](src/Unit/MagneticFlux/MagneticFluxUnit.php) | μWb |
| Unit-specific | [`Maxwell`](src/Quantity/MagneticFlux/CGS/Maxwell.php) | [`Maxwell`](src/Unit/MagneticFlux/MagneticFluxUnit.php) | Mx |

---

### Luminous Intensity [J¹]

The SI base unit of luminous intensity.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`LuminousIntensity`](src/Quantity/LuminousIntensity/LuminousIntensity.php) | [any (`LuminousIntensityUnit`)](src/Unit/LuminousIntensity/LuminousIntensityUnit.php) | - |
| Unit-specific | [`Candela`](src/Quantity/LuminousIntensity/SI/Candela.php) | [`Candela`](src/Unit/LuminousIntensity/LuminousIntensityUnit.php) | cd |
| Unit-specific | [`Kilocandela`](src/Quantity/LuminousIntensity/SI/Kilocandela.php) | [`Kilocandela`](src/Unit/LuminousIntensity/LuminousIntensityUnit.php) | kcd |
| Unit-specific | [`Millicandela`](src/Quantity/LuminousIntensity/SI/Millicandela.php) | [`Millicandela`](src/Unit/LuminousIntensity/LuminousIntensityUnit.php) | mcd |
| Unit-specific | [`Microcandela`](src/Quantity/LuminousIntensity/SI/Microcandela.php) | [`Microcandela`](src/Unit/LuminousIntensity/LuminousIntensityUnit.php) | μcd |

---

### Luminous Flux [J¹]

Total amount of visible light emitted.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`LuminousFlux`](src/Quantity/LuminousFlux/LuminousFlux.php) | [any (`LuminousFluxUnit`)](src/Unit/LuminousFlux/LuminousFluxUnit.php) | - |
| Unit-specific | [`Lumen`](src/Quantity/LuminousFlux/SI/Lumen.php) | [`Lumen`](src/Unit/LuminousFlux/LuminousFluxUnit.php) | lm |
| Unit-specific | [`Kilolumen`](src/Quantity/LuminousFlux/SI/Kilolumen.php) | [`Kilolumen`](src/Unit/LuminousFlux/LuminousFluxUnit.php) | klm |
| Unit-specific | [`Millilumen`](src/Quantity/LuminousFlux/SI/Millilumen.php) | [`Millilumen`](src/Unit/LuminousFlux/LuminousFluxUnit.php) | mlm |

---

### Illuminance [L⁻²J¹]

Derived from Luminous Flux ÷ Area.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`Illuminance`](src/Quantity/Illuminance/Illuminance.php) | [any (`IlluminanceUnit`)](src/Unit/Illuminance/IlluminanceUnit.php) | - |
| Unit-specific | [`Lux`](src/Quantity/Illuminance/SI/Lux.php) | [`Lux`](src/Unit/Illuminance/IlluminanceUnit.php) | lx |
| Unit-specific | [`Kilolux`](src/Quantity/Illuminance/SI/Kilolux.php) | [`Kilolux`](src/Unit/Illuminance/IlluminanceUnit.php) | klx |
| Unit-specific | [`Millilux`](src/Quantity/Illuminance/SI/Millilux.php) | [`Millilux`](src/Unit/Illuminance/IlluminanceUnit.php) | mlx |
| Unit-specific | [`FootCandle`](src/Quantity/Illuminance/Imperial/FootCandle.php) | [`FootCandle`](src/Unit/Illuminance/IlluminanceUnit.php) | fc |

---

### Calorific Value [L⁻¹M¹T⁻²]

Energy per unit volume. Used for gas billing.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`CalorificValue`](src/Quantity/CalorificValue/CalorificValue.php) | any | - |
| System-specific | [`MetricCalorificValue`](src/Quantity/CalorificValue/MetricCalorificValue.php) | [any metric (`MetricCalorificValueUnit`)](src/Unit/CalorificValue/MetricCalorificValueUnit.php) | - |
| System-specific | [`ImperialCalorificValue`](src/Quantity/CalorificValue/ImperialCalorificValue.php) | [any imperial (`ImperialCalorificValueUnit`)](src/Unit/CalorificValue/ImperialCalorificValueUnit.php) | - |
| Unit-specific | [`JoulePerCubicMeter`](src/Quantity/CalorificValue/Metric/JoulePerCubicMeter.php) | [`JoulePerCubicMeter`](src/Unit/CalorificValue/MetricCalorificValueUnit.php) | J/m³ |
| Unit-specific | [`KilojoulePerCubicMeter`](src/Quantity/CalorificValue/Metric/KilojoulePerCubicMeter.php) | [`KilojoulePerCubicMeter`](src/Unit/CalorificValue/MetricCalorificValueUnit.php) | kJ/m³ |
| Unit-specific | [`MegajoulePerCubicMeter`](src/Quantity/CalorificValue/Metric/MegajoulePerCubicMeter.php) | [`MegajoulePerCubicMeter`](src/Unit/CalorificValue/MetricCalorificValueUnit.php) | MJ/m³ |
| Unit-specific | [`GigajoulePerCubicMeter`](src/Quantity/CalorificValue/Metric/GigajoulePerCubicMeter.php) | [`GigajoulePerCubicMeter`](src/Unit/CalorificValue/MetricCalorificValueUnit.php) | GJ/m³ |
| Unit-specific | [`BTUPerCubicFoot`](src/Quantity/CalorificValue/Imperial/BTUPerCubicFoot.php) | [`BTUPerCubicFoot`](src/Unit/CalorificValue/ImperialCalorificValueUnit.php) | BTU/ft³ |
| Unit-specific | [`ThermPerCubicFoot`](src/Quantity/CalorificValue/Imperial/ThermPerCubicFoot.php) | [`ThermPerCubicFoot`](src/Unit/CalorificValue/ImperialCalorificValueUnit.php) | thm/ft³ |

---

### Digital Information [D¹]

Data storage capacity. Supports SI (decimal) and IEC (binary) prefixes.

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`DigitalInformation`](src/Quantity/Digital/DigitalInformation/DigitalInformation.php) | any | - |
| System-specific | [`SI\DigitalInformation`](src/Quantity/Digital/DigitalInformation/SI/DigitalInformation.php) | [any SI (`SIDigitalUnit`)](src/Unit/Digital/SI/SIDigitalUnit.php) | - |
| System-specific | [`SI\BitDigitalInformation`](src/Quantity/Digital/DigitalInformation/SI/BitDigitalInformation.php) | [any SI bit (`SIBitUnit`)](src/Unit/Digital/SI/SIBitUnit.php) | - |
| System-specific | [`SI\ByteDigitalInformation`](src/Quantity/Digital/DigitalInformation/SI/ByteDigitalInformation.php) | [any SI byte (`SIByteUnit`)](src/Unit/Digital/SI/SIByteUnit.php) | - |
| System-specific | [`IEC\DigitalInformation`](src/Quantity/Digital/DigitalInformation/IEC/DigitalInformation.php) | [any IEC (`IECDigitalUnit`)](src/Unit/Digital/IEC/IECDigitalUnit.php) | - |
| System-specific | [`IEC\BitDigitalInformation`](src/Quantity/Digital/DigitalInformation/IEC/BitDigitalInformation.php) | [any IEC bit (`IECBitUnit`)](src/Unit/Digital/IEC/IECBitUnit.php) | - |
| System-specific | [`IEC\ByteDigitalInformation`](src/Quantity/Digital/DigitalInformation/IEC/ByteDigitalInformation.php) | [any IEC byte (`IECByteUnit`)](src/Unit/Digital/IEC/IECByteUnit.php) | - |

**SI Units (decimal, powers of 10):**

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Unit-specific | [`Bit`](src/Quantity/Digital/DigitalInformation/SI/Bit/Bit.php) | [`Bit`](src/Unit/Digital/SI/SIBitUnit.php) | b |
| Unit-specific | [`Kilobit`](src/Quantity/Digital/DigitalInformation/SI/Bit/Kilobit.php) | [`Kilobit`](src/Unit/Digital/SI/SIBitUnit.php) | Kb |
| Unit-specific | [`Megabit`](src/Quantity/Digital/DigitalInformation/SI/Bit/Megabit.php) | [`Megabit`](src/Unit/Digital/SI/SIBitUnit.php) | Mb |
| Unit-specific | [`Gigabit`](src/Quantity/Digital/DigitalInformation/SI/Bit/Gigabit.php) | [`Gigabit`](src/Unit/Digital/SI/SIBitUnit.php) | Gb |
| Unit-specific | [`Terabit`](src/Quantity/Digital/DigitalInformation/SI/Bit/Terabit.php) | [`Terabit`](src/Unit/Digital/SI/SIBitUnit.php) | Tb |
| Unit-specific | [`Petabit`](src/Quantity/Digital/DigitalInformation/SI/Bit/Petabit.php) | [`Petabit`](src/Unit/Digital/SI/SIBitUnit.php) | Pb |
| Unit-specific | [`Byte`](src/Quantity/Digital/DigitalInformation/SI/Byte/Byte.php) | [`Byte`](src/Unit/Digital/SI/SIByteUnit.php) | B |
| Unit-specific | [`Kilobyte`](src/Quantity/Digital/DigitalInformation/SI/Byte/Kilobyte.php) | [`Kilobyte`](src/Unit/Digital/SI/SIByteUnit.php) | KB |
| Unit-specific | [`Megabyte`](src/Quantity/Digital/DigitalInformation/SI/Byte/Megabyte.php) | [`Megabyte`](src/Unit/Digital/SI/SIByteUnit.php) | MB |
| Unit-specific | [`Gigabyte`](src/Quantity/Digital/DigitalInformation/SI/Byte/Gigabyte.php) | [`Gigabyte`](src/Unit/Digital/SI/SIByteUnit.php) | GB |
| Unit-specific | [`Terabyte`](src/Quantity/Digital/DigitalInformation/SI/Byte/Terabyte.php) | [`Terabyte`](src/Unit/Digital/SI/SIByteUnit.php) | TB |
| Unit-specific | [`Petabyte`](src/Quantity/Digital/DigitalInformation/SI/Byte/Petabyte.php) | [`Petabyte`](src/Unit/Digital/SI/SIByteUnit.php) | PB |

**IEC Units (binary, powers of 2):**

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Unit-specific | [`Kibibit`](src/Quantity/Digital/DigitalInformation/IEC/Bit/Kibibit.php) | [`Kibibit`](src/Unit/Digital/IEC/IECBitUnit.php) | Kib |
| Unit-specific | [`Mebibit`](src/Quantity/Digital/DigitalInformation/IEC/Bit/Mebibit.php) | [`Mebibit`](src/Unit/Digital/IEC/IECBitUnit.php) | Mib |
| Unit-specific | [`Gibibit`](src/Quantity/Digital/DigitalInformation/IEC/Bit/Gibibit.php) | [`Gibibit`](src/Unit/Digital/IEC/IECBitUnit.php) | Gib |
| Unit-specific | [`Tebibit`](src/Quantity/Digital/DigitalInformation/IEC/Bit/Tebibit.php) | [`Tebibit`](src/Unit/Digital/IEC/IECBitUnit.php) | Tib |
| Unit-specific | [`Pebibit`](src/Quantity/Digital/DigitalInformation/IEC/Bit/Pebibit.php) | [`Pebibit`](src/Unit/Digital/IEC/IECBitUnit.php) | Pib |
| Unit-specific | [`Kibibyte`](src/Quantity/Digital/DigitalInformation/IEC/Byte/Kibibyte.php) | [`Kibibyte`](src/Unit/Digital/IEC/IECByteUnit.php) | KiB |
| Unit-specific | [`Mebibyte`](src/Quantity/Digital/DigitalInformation/IEC/Byte/Mebibyte.php) | [`Mebibyte`](src/Unit/Digital/IEC/IECByteUnit.php) | MiB |
| Unit-specific | [`Gibibyte`](src/Quantity/Digital/DigitalInformation/IEC/Byte/Gibibyte.php) | [`Gibibyte`](src/Unit/Digital/IEC/IECByteUnit.php) | GiB |
| Unit-specific | [`Tebibyte`](src/Quantity/Digital/DigitalInformation/IEC/Byte/Tebibyte.php) | [`Tebibyte`](src/Unit/Digital/IEC/IECByteUnit.php) | TiB |
| Unit-specific | [`Pebibyte`](src/Quantity/Digital/DigitalInformation/IEC/Byte/Pebibyte.php) | [`Pebibyte`](src/Unit/Digital/IEC/IECByteUnit.php) | PiB |

---

### Data Transfer Rate [D¹T⁻¹]

Measures the speed of data transmission, commonly used for network bandwidth, internet connection speeds, and file transfer rates. Like Digital Information, this quantity supports both SI (decimal) and IEC (binary) prefixes. ISPs and network equipment typically advertise speeds using SI units (e.g., "100 Mbps fiber"), while operating systems often report actual transfer rates in IEC units (e.g., "12 MiB/s"). This distinction matters because 100 Mbps (megabits per second) equals 12.5 MB/s (megabytes per second) but only about 11.92 MiB/s (mebibytes per second).

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Generic | [`DataTransferRate`](src/Quantity/Digital/DataTransferRate/DataTransferRate.php) | any | - |
| System-specific | [`SI\TransferRate`](src/Quantity/Digital/DataTransferRate/SI/TransferRate.php) | [any SI (`SITransferRateUnit`)](src/Unit/Digital/SI/SITransferRateUnit.php) | - |
| System-specific | [`SI\BitTransferRate`](src/Quantity/Digital/DataTransferRate/SI/BitTransferRate.php) | [any SI bit (`BitTransferRateUnit`)](src/Unit/Digital/SI/BitTransferRateUnit.php) | - |
| System-specific | [`SI\ByteTransferRate`](src/Quantity/Digital/DataTransferRate/SI/ByteTransferRate.php) | [any SI byte (`ByteTransferRateUnit`)](src/Unit/Digital/SI/ByteTransferRateUnit.php) | - |
| System-specific | [`IEC\TransferRate`](src/Quantity/Digital/DataTransferRate/IEC/TransferRate.php) | [any IEC (`IECTransferRateUnit`)](src/Unit/Digital/IEC/IECTransferRateUnit.php) | - |
| System-specific | [`IEC\BitTransferRate`](src/Quantity/Digital/DataTransferRate/IEC/BitTransferRate.php) | [any IEC bit (`IECBitTransferRateUnit`)](src/Unit/Digital/IEC/IECBitTransferRateUnit.php) | - |
| System-specific | [`IEC\ByteTransferRate`](src/Quantity/Digital/DataTransferRate/IEC/ByteTransferRate.php) | [any IEC byte (`IECByteTransferRateUnit`)](src/Unit/Digital/IEC/IECByteTransferRateUnit.php) | - |

**SI Units:**

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Unit-specific | [`BitPerSecond`](src/Quantity/Digital/DataTransferRate/SI/Bit/BitPerSecond.php) | [`BitPerSecond`](src/Unit/Digital/SI/BitTransferRateUnit.php) | bps |
| Unit-specific | [`KilobitPerSecond`](src/Quantity/Digital/DataTransferRate/SI/Bit/KilobitPerSecond.php) | [`KilobitPerSecond`](src/Unit/Digital/SI/BitTransferRateUnit.php) | kbps |
| Unit-specific | [`MegabitPerSecond`](src/Quantity/Digital/DataTransferRate/SI/Bit/MegabitPerSecond.php) | [`MegabitPerSecond`](src/Unit/Digital/SI/BitTransferRateUnit.php) | Mbps |
| Unit-specific | [`GigabitPerSecond`](src/Quantity/Digital/DataTransferRate/SI/Bit/GigabitPerSecond.php) | [`GigabitPerSecond`](src/Unit/Digital/SI/BitTransferRateUnit.php) | Gbps |
| Unit-specific | [`BytePerSecond`](src/Quantity/Digital/DataTransferRate/SI/Byte/BytePerSecond.php) | [`BytePerSecond`](src/Unit/Digital/SI/ByteTransferRateUnit.php) | B/s |
| Unit-specific | [`KilobytePerSecond`](src/Quantity/Digital/DataTransferRate/SI/Byte/KilobytePerSecond.php) | [`KilobytePerSecond`](src/Unit/Digital/SI/ByteTransferRateUnit.php) | KB/s |
| Unit-specific | [`MegabytePerSecond`](src/Quantity/Digital/DataTransferRate/SI/Byte/MegabytePerSecond.php) | [`MegabytePerSecond`](src/Unit/Digital/SI/ByteTransferRateUnit.php) | MB/s |
| Unit-specific | [`GigabytePerSecond`](src/Quantity/Digital/DataTransferRate/SI/Byte/GigabytePerSecond.php) | [`GigabytePerSecond`](src/Unit/Digital/SI/ByteTransferRateUnit.php) | GB/s |

**IEC Units:**

| Scope | Class | Unit | Symbol |
|-------|-------|------|--------|
| Unit-specific | [`KibibitPerSecond`](src/Quantity/Digital/DataTransferRate/IEC/Bit/KibibitPerSecond.php) | [`KibibitPerSecond`](src/Unit/Digital/IEC/IECBitTransferRateUnit.php) | Kibps |
| Unit-specific | [`MebibitPerSecond`](src/Quantity/Digital/DataTransferRate/IEC/Bit/MebibitPerSecond.php) | [`MebibitPerSecond`](src/Unit/Digital/IEC/IECBitTransferRateUnit.php) | Mibps |
| Unit-specific | [`GibibitPerSecond`](src/Quantity/Digital/DataTransferRate/IEC/Bit/GibibitPerSecond.php) | [`GibibitPerSecond`](src/Unit/Digital/IEC/IECBitTransferRateUnit.php) | Gibps |
| Unit-specific | [`KibibytePerSecond`](src/Quantity/Digital/DataTransferRate/IEC/Byte/KibibytePerSecond.php) | [`KibibytePerSecond`](src/Unit/Digital/IEC/IECByteTransferRateUnit.php) | KiB/s |
| Unit-specific | [`MebibytePerSecond`](src/Quantity/Digital/DataTransferRate/IEC/Byte/MebibytePerSecond.php) | [`MebibytePerSecond`](src/Unit/Digital/IEC/IECByteTransferRateUnit.php) | MiB/s |
| Unit-specific | [`GibibytePerSecond`](src/Quantity/Digital/DataTransferRate/IEC/Byte/GibibytePerSecond.php) | [`GibibytePerSecond`](src/Unit/Digital/IEC/IECByteTransferRateUnit.php) | GiB/s |

## Testing

```bash
# Run tests
make tests

# Run PHPStan
make phpstan

# Generate coverage report
make coverage

# Show coverage in terminal
make coverage-text
```

### Docker Support

```bash
# Build container
make setup

# Get inside PHP container
make php 
```

---

Built with ❤️ by [AndanteProject](https://github.com/andanteproject) team.
