# Contributing to Andante Measurement

First off, thank you for considering contributing to Andante Measurement!

## Code of Conduct

This project adheres to a code of conduct. By participating, you are expected to uphold this code.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check existing issues. When creating a bug report, include:

- **Clear title and description**
- **Steps to reproduce** the problem
- **Expected behavior** and what actually happened
- **Code samples** if applicable
- **PHP version** and environment details

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, include:

- **Clear use case** - what problem does it solve?
- **Proposed solution** - if you have one in mind
- **Alternative solutions** you've considered
- **Impact** on existing functionality

### Pull Requests

1. **Fork** the repository
2. **Create a branch** from `main`:
   ```bash
   git checkout -b feature/my-new-feature
   ```
3. **Make your changes** following our coding standards
4. **Add tests** for any new functionality
5. **Run the test suite**:
   ```bash
   make tests
   make phpstan
   make cs-fixer
   ```
6. **Commit your changes** with clear commit messages
7. **Push** to your fork
8. **Submit a pull request** to the `main` branch

## Development Setup

### Prerequisites

- Docker and Docker Compose
- Make (optional but recommended)

### Getting Started

```bash
# Clone the repository
git clone https://github.com/andanteproject/measurement.git
cd measurement

# Setup development environment
make setup

# Get inside the PHP container
make php

# Run tests
make tests

# Run code quality checks
make phpstan
make cs-fixer
```

### Available Make Commands

```bash
make help           # Show all available commands
make setup          # Setup PHP 8.1 environment
make php            # Open shell in PHP container
make tests          # Run tests
make phpstan        # Run PHPStan static analysis
make cs-fixer       # Run PHP-CS-Fixer
make coverage       # Generate coverage report (HTML + text)
make coverage-text  # Show coverage summary in terminal
make clean          # Clean up generated files
```

### Testing

```bash
# Run tests
make tests

# Generate coverage report (HTML + text summary)
make coverage

# Show coverage summary in terminal only (fast)
make coverage-text
```

### Code Quality

We maintain high code quality standards:

- **PHPStan Level 9** - maximum static analysis
- **PHP-CS-Fixer** - PSR-12 + Symfony coding standards
- **Strict types** - `declare(strict_types=1)` in all files
- **100% type coverage** - all methods and properties must be typed

```bash
# Run static analysis
make phpstan

# Fix code style
make cs-fixer
```

## Coding Standards

### PHP Standards

- Use **strict types**: `declare(strict_types=1);`
- Follow **PSR-12** coding style
- Use **type hints** for all parameters and return types
- Write **PHPDoc** for complex logic or non-obvious code
- Prefer **readonly** properties when possible
- Use **enums** for unit types

### Naming Conventions

- **Classes**: PascalCase (`Meter`, `KilowattHour`)
- **Methods**: camelCase (`getValue`, `toBaseUnit`)
- **Properties**: camelCase (`$value`, `$unit`)
- **Constants**: SCREAMING_SNAKE_CASE (`SPEED_OF_LIGHT`)
- **Test methods**: camelCase (`testMeterToKilometerConversion`)

### Object-First Design

- **Always return objects**, not primitives
- **Immutable value objects** - no setters, return new instances
- **Builder pattern** for options - use `with*()` methods (see `FormatOptions`, `ParseOptions`)
- **Factory methods** - use `of()` for unit-specific and `from()` for generic construction

### Testing

- Write tests for **all new functionality**
- Test **edge cases** and **error conditions**
- Use **descriptive test names** in **camelCase**
- Use **static assertions** (`self::assertSame()` not `$this->assertSame()`)
- Follow **AAA pattern**: Arrange, Act, Assert
- **Reset registries** in `tearDown()` to avoid test pollution

Example:
```php
public function testMeterToKilometerConversion(): void
{
    // Arrange
    $meter = Meter::of(NumberFactory::create('5000'));

    // Act
    $kilometer = $meter->to(MetricLengthUnit::Kilometer);

    // Assert
    self::assertEqualsWithDelta(5.0, (float) $kilometer->getValue()->value(), 0.001);
}
```

## Adding New Quantities

Follow the 7-step guide in [README.md](README.md#adding-custom-quantities):

### Step 1: Create the Dimension

Create a dimension class implementing `DimensionInterface` with a `DimensionalFormula`:

```php
// src/Dimension/YourDimension.php
final class YourDimension implements DimensionInterface
{
    public function getFormula(): DimensionalFormula
    {
        return new DimensionalFormula(length: 1, time: -1);
    }
}
```

### Step 2: Create the Unit Enum

Create a unit enum implementing `UnitInterface`:

```php
// src/Unit/YourQuantity/YourQuantityUnit.php
enum YourQuantityUnit: string implements UnitInterface
{
    case BaseUnit = 'base';
    case DerivedUnit = 'derived';

    // Implement symbol(), name(), dimension(), system()
}
```

### Step 3: Create the Quantity Interface

```php
// src/Quantity/YourQuantity/YourQuantityInterface.php
interface YourQuantityInterface extends QuantityInterface {}
```

### Step 4: Create Quantity Classes

Create unit-specific quantity classes using traits:

```php
// src/Quantity/YourQuantity/BaseUnit.php
final class BaseUnit implements YourQuantityInterface, QuantityFactoryInterface, ConvertibleInterface, ComparableInterface, CalculableInterface
{
    use ConvertibleTrait;
    use ComparableTrait;
    use CalculableTrait;

    // Implement of(), from(), getValue(), getUnit()
}
```

### Step 5: Create a Provider

Implement `QuantityDefaultConfigProviderInterface` to register units and conversion factors:

```php
// src/Registry/Provider/YourQuantityProvider.php
final class YourQuantityProvider implements QuantityDefaultConfigProviderInterface
{
    public function registerUnits(UnitRegistry $registry): void { }
    public function registerConversionFactors(ConversionFactorRegistry $registry): void { }
    public function registerResultMappings(ResultQuantityRegistry $registry): void { }
    public function registerFormulaUnits(FormulaUnitRegistry $registry): void { }
}
```

### Step 6: Register with Registries

Add your provider to the default registration in each registry class.

### Step 7: Add Translations (Optional)

Register translations programmatically using `TranslationLoader::registerTranslation()`.

### Step 8: Write Tests

Create comprehensive tests in `tests/Unit/Quantity/YourQuantity/`:

- Conversion tests between all units
- Arithmetic tests (add, subtract, multiply, divide)
- Comparison tests
- Auto-scale tests
- Round-trip conversion tests

## Project Structure

```
src/
├── Calculator/          # Arithmetic operations
├── Comparator/          # Quantity comparison
├── Contract/            # Interfaces
├── Converter/           # Unit conversion logic
├── Dimension/           # Dimensional formulas
├── Exception/           # Custom exceptions
├── Formatter/           # Output formatting
├── Math/                # Arbitrary precision math
├── Parser/              # String parsing
├── Quantity/            # Quantity classes by type
│   ├── Length/
│   │   ├── Metric/      # Unit-specific classes
│   │   ├── Imperial/
│   │   ├── Length.php   # Generic class
│   │   └── ...
│   └── ...
├── Registry/            # Unit, conversion, formula registries
│   └── Provider/        # Registration providers
├── Translation/         # i18n support
└── Unit/                # Unit enums by type
```

## Documentation

- Update **README.md** if adding new features or quantities
- Add **code examples** for new functionality
- Update **PHPDoc** comments
- Document new units in the Available Quantities section

## Questions?

Feel free to open an issue with your question or reach out to the maintainers.
