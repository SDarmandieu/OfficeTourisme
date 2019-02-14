<?php

use Faker\Generator as Faker;

$factory->define(App\Game::class, function (Faker $faker) {
    $ages = ['7/9 ans','9/11 ans','11/13 ans'];
    $rand = rand(0,1);
    return [
        'name' => $faker->word,
        'age' => $ages[array_rand($ages)],
        'desc' => $faker->word,
        'published' => [true,false][$rand]
    ];
});
