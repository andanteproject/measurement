<?php

declare(strict_types=1);

namespace Andante\Measurement\Math;

use Andante\Measurement\Contract\Math\MathAdapterInterface;
use Andante\Measurement\Exception\InvalidOperationException;
use Andante\Measurement\Math\Adapter\BCMathAdapter;
use Andante\Measurement\Math\Adapter\BrickMathAdapter;

/**
 * Factory for creating and managing the global MathAdapter instance.
 *
 * This factory allows users to configure which math library to use.
 * By default, it attempts to auto-detect available math libraries in this order:
 * 1. brick/math (if installed via Composer)
 * 2. BCMath (if ext-bcmath is available)
 * 3. Throws exception if neither is available
 *
 * Users can provide their own adapter implementation by calling setAdapter()
 * before any quantities are created.
 *
 * Example:
 * ```php
 * // Option 1: Auto-detection (tries brick/math, then BCMath)
 * // No configuration needed if either library is available
 *
 * // Option 2: Explicitly set brick/math
 * MathAdapterFactory::setAdapter(new BrickMathAdapter());
 *
 * // Option 3: Explicitly set BCMath
 * MathAdapterFactory::setAdapter(new BCMathAdapter());
 *
 * // Option 4: Use custom adapter
 * MathAdapterFactory::setAdapter(new MyCustomMathAdapter());
 * ```
 */
final class MathAdapterFactory
{
    private static ?MathAdapterInterface $adapter = null;
    private static bool $autoDetected = false;

    /**
     * Set the math adapter to use globally.
     *
     * This should be called early in your application bootstrap,
     * before any quantities are created.
     *
     * @param MathAdapterInterface $adapter The adapter to use for all math operations
     */
    public static function setAdapter(MathAdapterInterface $adapter): void
    {
        self::$adapter = $adapter;
        self::$autoDetected = false;
    }

    /**
     * Get the configured math adapter.
     *
     * If no adapter has been explicitly set, this will attempt to
     * auto-detect available math libraries (brick/math, then BCMath).
     * If no suitable library is found, throws an exception.
     *
     * @throws InvalidOperationException If no adapter is configured and auto-detection fails
     */
    public static function getAdapter(): MathAdapterInterface
    {
        if (null !== self::$adapter) {
            return self::$adapter;
        }

        // Attempt auto-detection: brick/math first (more features), then BCMath
        if (self::canUseBrickMath()) {
            self::$adapter = new BrickMathAdapter();
            self::$autoDetected = true;

            return self::$adapter;
        }

        if (self::canUseBCMath()) {
            self::$adapter = new BCMathAdapter();
            self::$autoDetected = true;

            return self::$adapter;
        }

        throw new InvalidOperationException('No math adapter configured. Please either: (1) Install brick/math: composer require brick/math, or (2) Enable BCMath extension: ext-bcmath, or (3) Configure a custom adapter: MathAdapterFactory::setAdapter($adapter)');
    }

    /**
     * Check if an adapter has been explicitly configured.
     */
    public static function hasAdapter(): bool
    {
        return null !== self::$adapter && !self::$autoDetected;
    }

    /**
     * Check if the current adapter was auto-detected.
     */
    public static function isAutoDetected(): bool
    {
        return self::$autoDetected;
    }

    /**
     * Reset the factory (useful for testing).
     *
     * @internal
     */
    public static function reset(): void
    {
        self::$adapter = null;
        self::$autoDetected = false;
    }

    /**
     * Check if brick/math is available.
     */
    private static function canUseBrickMath(): bool
    {
        return \class_exists('Brick\\Math\\BigDecimal');
    }

    /**
     * Check if BCMath extension is available.
     */
    private static function canUseBCMath(): bool
    {
        return \extension_loaded('bcmath');
    }
}
