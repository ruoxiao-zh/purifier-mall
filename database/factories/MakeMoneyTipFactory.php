<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MakeMoneyTip;
use Faker\Generator as Faker;

$factory->define(MakeMoneyTip::class, function (Faker $faker) {
    return [
        'cover_image' => $faker->imageUrl(),
        'author'      => $faker->userName,
        'brief_intro' => $faker->text,
        'content'     => $faker->paragraph,
        'sort'        => random_int(0, 100),
    ];
});
