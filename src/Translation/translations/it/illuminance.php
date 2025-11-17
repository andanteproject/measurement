<?php

declare(strict_types=1);

use Andante\Measurement\Unit\Illuminance\IlluminanceUnit;

return [
    IlluminanceUnit::Lux->name => ['lux', 'lux'],
    IlluminanceUnit::Kilolux->name => ['chilolux', 'chilolux'],
    IlluminanceUnit::Millilux->name => ['millilux', 'millilux'],
    IlluminanceUnit::FootCandle->name => ['piede-candela', 'piedi-candela'],
];
