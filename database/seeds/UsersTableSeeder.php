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
        // factory(App\Model\User::class, 50)->create()->each(function ($user) {
        //     $user->article()->save(factory(App\Model\Article::class)->make());
        // });
        // 截断数据
        User::truncate();

        $faker = \Faker\Factory::create();

        $password = Hash::make('admin');

        User::create([
            'name' => 'Administrator',
            'avatar' => 'https://via.placeholder.com/500x500.png/003366?text=dogs+Faker+vitae',
            'desc' => '时光旅行者',
            'email' => 'admin@mgblog.com',
            'password' => $password,
            'is_admin' => 1
        ]);
        
        factory(App\Model\User::class, 50)->create();
    }
}
