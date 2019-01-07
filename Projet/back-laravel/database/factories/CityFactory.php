<?php

use Faker\Generator as Faker;

$factory->define(App\City::class, function (Faker $faker) {
    return [
        'name' => 'foo',
        'lat' => rand(1, 50),
        'lon' => rand(1, 50)
    ];
});
