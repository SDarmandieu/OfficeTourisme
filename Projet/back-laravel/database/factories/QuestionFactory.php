<?php

use Faker\Generator as Faker;

$factory->define(App\Question::class, function (Faker $faker) {
    $expe = [16,32,64];
    return [
        'content'=> $faker->sentence,
        'expe' => $expe[array_rand($expe)]
    ];
});
