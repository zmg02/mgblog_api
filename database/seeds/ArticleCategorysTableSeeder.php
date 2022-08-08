<?php

use Illuminate\Database\Seeder;

class ArticleCategorysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Model\ArticleCategory::class, 6)->create();
    }
}
