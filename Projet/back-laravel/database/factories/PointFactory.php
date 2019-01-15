<?php

use Faker\Generator as Faker;

$factory->define(App\Point::class, function (Faker $faker) {
    return [
        'lat' => 43+rand(1,1000)/1000,
        'lon' => 0.7+rand(1,100)/1000,
        'desc' => $faker->address
    ];
});
