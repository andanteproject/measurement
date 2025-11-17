<?php

declare(strict_types=1);

use Andante\Measurement\Unit\Illuminance\IlluminanceUnit;

return [
    IlluminanceUnit::Lux->name => ['люкс', 'люксы'],
    IlluminanceUnit::Kilolux->name => ['килолюкс', 'килолюксы'],
    IlluminanceUnit::Millilux->name => ['миллилюкс', 'миллилюксы'],
    IlluminanceUnit::FootCandle->name => ['фут-свеча', 'фут-свечи'],
];
