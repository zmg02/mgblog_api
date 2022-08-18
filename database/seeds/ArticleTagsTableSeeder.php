<?php

use Illuminate\Database\Seeder;

class ArticleTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 截断数据
        App\Model\ArticleTag::truncate();
        factory(App\Model\ArticleTag::class, 100)->create();
    }
}
