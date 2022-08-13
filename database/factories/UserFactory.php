<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'avatar' => $faker->imageUrl(500, 500, 'dogs', true, 'Faker'),
        'desc' => $faker->paragraph(3, true),
        'phone' => $faker->unique()->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'email_verified_time' => $faker->unixTime('now'),
        'password' => $faker->sha1,
        'status' => $faker->numberBetween(0, 1),
        'is_admin' => $faker->numberBetween(0, 1),
        'is_author' => $faker->numberBetween(0, 1),
        'article_count' => $faker->numberBetween(1, 100),
        'last_login_time' => $faker->unixTime('now'),
        'last_login_ip' => $faker->ipv4,
        'remember_token' => Str::random(10),
        'create_time' => $faker->unixTime('now'),
        'update_time' => $faker->unixTime('now')
    ];
});
