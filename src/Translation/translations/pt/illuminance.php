<?php

declare(strict_types=1);

use Andante\Measurement\Unit\Illuminance\IlluminanceUnit;

return [
    IlluminanceUnit::Lux->name => ['lux', 'lux'],
    IlluminanceUnit::Kilolux->name => ['quilolux', 'quilolux'],
    IlluminanceUnit::Millilux->name => ['mililux', 'mililux'],
    IlluminanceUnit::FootCandle->name => ['pé-vela', 'pés-vela'],
];
