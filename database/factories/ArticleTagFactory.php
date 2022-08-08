<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\ArticleTag;
use Faker\Generator as Faker;

$factory->define(ArticleTag::class, function (Faker $faker) {
    return [
        'article_id' => factory(App\Model\Article::class),
        'tag_id' => factory(App\Model\Tag::class),
    ];
});
