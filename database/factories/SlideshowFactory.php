<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Slideshow;
use Faker\Generator as Faker;

$factory->define(Slideshow::class, function (Faker $faker) {
    return [
        'url_path' => $faker->imageUrl(),
        'sort' => random_int(0, 100),
    ];
});
