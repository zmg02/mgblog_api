<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Banner;
use Faker\Generator as Faker;

$factory->define(Banner::class, function (Faker $faker) {
    return [
        'url' => $faker->imageUrl(654, 368, 'dogs', true, 'Faker'),
        'category_id' => factory(App\Model\ArticleCategory::class),
        'order' => $faker->numberBetween(0, 10),
        'status' => $faker->numberBetween(0, 1),
        'create_time' => $faker->unixTime('now'),
        'update_time' => $faker->unixTime('now')
    ];
});
