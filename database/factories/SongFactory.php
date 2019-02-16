<?php

use Faker\Generator as Faker;

$factory->define(App\Song::class, function (Faker $faker) {
    return [
        'name' => $faker->firstNameFemale(),
    ];
});

// can be done in the seeder, so not needed here

// $factory->afterCreating(App\Song::class, function ($song, $faker) {
//     $song->song_lyrics()->save(factory(App\SongLyric::class)->make());
// });