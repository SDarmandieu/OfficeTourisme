<?php

use Faker\Generator as Faker;

$factory->define(App\Image::class, function (Faker $faker) {
    return [
        'filename' => $faker->word,
        'path' => '/images/'.$faker->image('public/storage/images',400,300,null,false),
        'alt' => $faker->word,
        'imagetype_id' => rand(1,3)
    ];
});
