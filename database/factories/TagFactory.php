<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->words(3, true),
        'status' => $faker->numberBetween(0, 1),
        'create_time' => $faker->unixTime('now'),
        'update_time' => $faker->unixTime('now')
    ];
});
