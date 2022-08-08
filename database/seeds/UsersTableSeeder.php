<?php

use Illuminate\Database\Seeder;

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

        factory(App\Model\User::class, 50)->create();
    }
}
