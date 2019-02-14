<?php

use Faker\Generator as Faker;

$factory->define(App\Point::class, function (Faker $faker) {
    return [
        'lat' => number_format(43.1 + rand(1, 100) / 1000, 4),
        'lon' => number_format(0.7 + rand(1, 100) / 1000, 4),
        'desc' => $faker->address
    ];
});
