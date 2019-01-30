<?php

use Faker\Generator as Faker;

$factory->define(App\Answer::class, function (Faker $faker) {
    return [
        'content' => $faker->sentence,
        'valid' => rand(1,4)===1,
        'file_id' => null
    ];
});
