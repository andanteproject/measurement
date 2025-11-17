<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Registry;

use Andante\Measurement\Contract\DimensionInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Dimension\DimensionalFormula;
use Andante\Measurement\Exception\InvalidArgumentException;
use Andante\Measurement\Registry\FormulaUnitRegistry;
use Andante\Measurement\Unit\UnitSystem;
use PHPUnit\Framework\TestCase;

final class FormulaUnitRegistryTest extends TestCase
{
    private FormulaUnitRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new FormulaUnitRegistry();
    }

    protected function tearDown(): void
    {
        FormulaUnitRegistry::reset();
    }

    public function testRegisterAndRetrieve(): void
    {
        $formula = DimensionalFormula::length();
        $unit = $this->createMockUnit('Meter', $formula);

        $this->registry->register($formula, $unit);

        $result = $this->registry->getUnit($formula);

        self::assertSame($unit, $result);
    }

    public function testHasReturnsTrueForRegisteredFormula(): void
    {
        $formula = DimensionalFormula::length();
        $unit = $this->createMockUnit('Meter', $formula);

        $this->registry->register($formula, $unit);

        self::assertTrue($this->registry->has($formula));
    }

    public function testHasReturnsFalseForUnregisteredFormula(): void
    {
        $formula = DimensionalFormula::length();

        self::assertFalse($this->registry->has($formula));
    }

    public function testThrowsExceptionForUnregisteredFormula(): void
    {
        $formula = DimensionalFormula::length();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No unit registered');

        $this->registry->getUnit($formula);
    }

    public function testDifferentFormulasAreSeparate(): void
    {
        $lengthFormula = DimensionalFormula::length();
        $areaFormula = DimensionalFormula::length()->power(2);

        $meterUnit = $this->createMockUnit('Meter', $lengthFormula);
        $squareMeterUnit = $this->createMockUnit('SquareMeter', $areaFormula);

        $this->registry->register($lengthFormula, $meterUnit);
        $this->registry->register($areaFormula, $squareMeterUnit);

        self::assertSame($meterUnit, $this->registry->getUnit($lengthFormula));
        self::assertSame($squareMeterUnit, $this->registry->getUnit($areaFormula));
    }

    public function testOverwritesPreviousRegistration(): void
    {
        $formula = DimensionalFormula::length();
        $unit1 = $this->createMockUnit('Meter', $formula);
        $unit2 = $this->createMockUnit('Foot', $formula);

        $this->registry->register($formula, $unit1);
        $this->registry->register($formula, $unit2);

        self::assertSame($unit2, $this->registry->getUnit($formula));
    }

    public function testComplexFormulas(): void
    {
        // Velocity: L¹T⁻¹
        $velocityFormula = new DimensionalFormula(length: 1, time: -1);
        $velocityUnit = $this->createMockUnit('MeterPerSecond', $velocityFormula);

        // Acceleration: L¹T⁻²
        $accelerationFormula = new DimensionalFormula(length: 1, time: -2);
        $accelerationUnit = $this->createMockUnit('MeterPerSecondSquared', $accelerationFormula);

        // Force: L¹M¹T⁻²
        $forceFormula = new DimensionalFormula(length: 1, mass: 1, time: -2);
        $forceUnit = $this->createMockUnit('Newton', $forceFormula);

        $this->registry->register($velocityFormula, $velocityUnit);
        $this->registry->register($accelerationFormula, $accelerationUnit);
        $this->registry->register($forceFormula, $forceUnit);

        self::assertSame($velocityUnit, $this->registry->getUnit($velocityFormula));
        self::assertSame($accelerationUnit, $this->registry->getUnit($accelerationFormula));
        self::assertSame($forceUnit, $this->registry->getUnit($forceFormula));
    }

    public function testGlobalRegistryIsSingleton(): void
    {
        $registry1 = FormulaUnitRegistry::global();
        $registry2 = FormulaUnitRegistry::global();

        self::assertSame($registry1, $registry2);
    }

    public function testResetClearsGlobalRegistry(): void
    {
        $registry1 = FormulaUnitRegistry::global();
        FormulaUnitRegistry::reset();
        $registry2 = FormulaUnitRegistry::global();

        self::assertNotSame($registry1, $registry2);
    }

    public function testSetGlobalReplacesInstance(): void
    {
        $customRegistry = new FormulaUnitRegistry();
        FormulaUnitRegistry::setGlobal($customRegistry);

        self::assertSame($customRegistry, FormulaUnitRegistry::global());
    }

    /**
     * Create a mock unit with a given name and dimensional formula.
     */
    private function createMockUnit(string $name, DimensionalFormula $formula): UnitInterface
    {
        $dimension = $this->createMock(DimensionInterface::class);
        $dimension->method('getFormula')->willReturn($formula);

        $unit = $this->createMock(UnitInterface::class);
        $unit->method('name')->willReturn($name);
        $unit->method('symbol')->willReturn(\strtolower($name));
        $unit->method('dimension')->willReturn($dimension);
        $unit->method('system')->willReturn(UnitSystem::SI);

        return $unit;
    }
}
