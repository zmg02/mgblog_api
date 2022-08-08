<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\Model\User::class),
        'category_id' => factory(App\Model\ArticleCategory::class),
        'default_img' => $faker->imageUrl(500, 500, 'cats', true, 'Faker'),
        'title' => $faker->words(5, true),
        'content' => $faker->text(500),
        'order' => $faker->numberBetween(0, 10),
        'status' => $faker->numberBetween(0, 1),
        'comment_count' => $faker->numberBetween(1, 100),
        'praise_count' => $faker->numberBetween(1, 100),
        'create_time' => $faker->unixTime('now'),
        'update_time' => $faker->unixTime('now')
    ];
});
