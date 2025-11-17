<?php

declare(strict_types=1);

namespace Andante\Measurement\Tests\Unit\Math\Adapter;

use Andante\Measurement\Contract\Math\MathAdapterInterface;
use Andante\Measurement\Math\Adapter\BrickMathAdapter;

/**
 * Test case for BrickMathAdapter.
 *
 * This extends the abstract MathAdapterTestCase to ensure BrickMathAdapter
 * conforms to the expected behavior of all math adapters.
 */
final class BrickMathAdapterTest extends MathAdapterTestCase
{
    protected function createAdapter(): MathAdapterInterface
    {
        return new BrickMathAdapter();
    }
}
