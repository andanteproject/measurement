<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Math\Adapter;

use Andante\Measurement\Contract\Math\MathAdapterInterface;
use Andante\Measurement\Math\Adapter\BCMathAdapter;

/**
 * Test case for BCMathAdapter.
 *
 * This extends the abstract MathAdapterTestCase to ensure BCMathAdapter
 * conforms to the expected behavior of all math adapters.
 *
 * @requires extension bcmath
 */
final class BCMathAdapterTest extends MathAdapterTestCase
{
    protected function createAdapter(): MathAdapterInterface
    {
        return new BCMathAdapter();
    }
}
