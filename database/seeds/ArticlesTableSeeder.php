<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 截断数据
        App\Model\Article::truncate();
        factory(App\Model\Article::class, 60)->create();
    }
}
