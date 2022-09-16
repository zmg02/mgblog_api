<?php

use App\Model\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 截断数据
        User::truncate();

        $faker = \Faker\Factory::create();

        $password = Hash::make('admin');

        User::create([
            'name' => 'admin',
            'avatar' => $faker->imageUrl(500, 500, 'dogs', true, 'Faker'),
            'desc' => '时光旅行者',
            'email' => 'admin@zmg2022.cn',
            'password' => $password,
            'is_admin' => 1
        ]);
        
        factory(App\Model\User::class, 50)->create();
        // factory(App\Model\User::class, 50)->create()->each(function ($user) {
        //     $user->article()->save(factory(App\Model\Article::class)->make());
        // });
        // factory(App\Model\User::class, 50)->create()->each(function ($user) {
        //     $user->article()->save(factory(App\Model\Article::class)->create()->each(function ($article) {
        //         $article->comment()->save(factory(App\Model\Comment::class)->make());
        //         $article->tag()->save(factory(App\Model\ArticleTag::class)->make());
        //     }));
        // });
    }
}
