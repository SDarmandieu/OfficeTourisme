<?php

use Faker\Generator as Faker;

$factory->define(App\File::class, function (Faker $faker) {
    $rand = rand(1, 10);
    switch ($rand) {
        case(1) :
            return [
                'filename' => "audio fake",
                'path' => '/fake/fake_audio.mp4',
                'extension' => 'mp4',
                'type' => 'audio',
                'alt' => "applause",
                'imagetype_id' => null
            ];
            break;
        case(2) :
            return [
                'filename' => "video fake",
                'path' => '/fake/fake_video.mp4',
                'extension' => 'mp4',
                'type' => 'video',
                'alt' => "talking lion",
                'imagetype_id' => null
            ];
            break;
        default :
            return [
                'filename' => $faker->word,
                'path' => '/files/image/' . $faker->image('public/storage/files/image', 400, 300, null, false),
                'extension' => 'png',
                'type' => 'image',
                'alt' => $faker->word,
                'imagetype_id' => rand(1, 3)
            ];
    }
});
