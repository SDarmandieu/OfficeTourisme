<?php

use Faker\Generator as Faker;

$factory->define(App\Poi::class, function (Faker $faker) {
    return [
        'lon' => "43.10".rand(1,100),
        'lat' => "0.72".rand(1,100),
        'desc' => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reprehenderit ex, impedit quia sequi eligendi placeat, mollitia, animi facilis libero consequatur distinctio."
    ];
});