<?php

declare(strict_types=1);

namespace Andante\Measurement\Quantity\Trait;

use Andante\Measurement\Contract\ConverterInterface;
use Andante\Measurement\Contract\Math\RoundingModeInterface;
use Andante\Measurement\Contract\QuantityInterface;
use Andante\Measurement\Contract\UnitInterface;
use Andante\Measurement\Converter\Converter;
use Andante\Measurement\Math\RoundingMode;
use Andante\Measurement\Registry\ConversionFactorRegistry;
use Andante\Measurement\Registry\UnitRegistry;

/**
 * Trait that implements ConvertibleInterface using the global Converter service.
 *
 * This trait expects the class to implement QuantityInterface.
 *
 * @phpstan-require-implements QuantityInterface
 */
trait ConvertibleTrait
{
    private static ?ConverterInterface $converter = null;

    /**
     * Get the Converter instance to use.
     */
    private static function getConverter(): ConverterInterface
    {
        return self::$converter ?? Converter::global();
    }

    /**
     * Set a custom Converter instance.
     *
     * Useful for testing or custom configurations.
     */
    public static function setConverter(?ConverterInterface $converter): void
    {
        self::$converter = $converter;
    }

    /**
     * Reset the Converter to use the default global instance.
     */
    public static function resetConverter(): void
    {
        self::$converter = null;
    }

    /**
     * @see ConvertibleInterface::to()
     */
    public function to(
        UnitInterface $unit,
        int $scale = 10,
        RoundingModeInterface $roundingMode = RoundingMode::HalfUp,
    ): QuantityInterface {
        return self::getConverter()->convertQuantity($this, $unit, $scale, $roundingMode);
    }

    /**
     * @see ConvertibleInterface::toBaseUnit()
     */
    public function toBaseUnit(): QuantityInterface
    {
        $dimension = $this->getUnit()->dimension();
        $baseUnit = ConversionFactorRegistry::global()->getBaseUnit($dimension);
        $baseValue = self::getConverter()->toBaseUnit($this->getValue(), $this->getUnit());

        /** @var class-string<\Andante\Measurement\Contract\QuantityFactoryInterface> $quantityClass */
        $quantityClass = UnitRegistry::global()->getQuantityClass($baseUnit);

        return $quantityClass::from($baseValue, $baseUnit);
    }
}
