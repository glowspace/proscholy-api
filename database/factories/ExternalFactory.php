<?php

use Faker\Generator as Faker;
use Faker\Provider\Internet;


$factory->define(App\External::class, function (Faker $faker) {
    $faker->addProvider(new Internet($faker));

    return [
        'url' => $faker->url(),
        'type' => collect([1,4,8,9])->random(1)[0] // generate only some
    ];
});