<?php
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\SongLyric::class, function (Faker $faker) {
    return [
        'name' => $faker->firstNameFemale(),
        'lyrics' => $faker->text()
    ];
});