<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'pid' => $faker->numberBetween(0, 10),
        'article_id' => factory(App\Model\Article::class),
        'user_id' => factory(App\Model\User::class),
        'content' => $faker->text(255),
        'order' => $faker->numberBetween(0, 10),
        'status' => $faker->numberBetween(0, 1),
        'create_time' => $faker->unixTime('now'),
        'update_time' => $faker->unixTime('now')
    ];
});
