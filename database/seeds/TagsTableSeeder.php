<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 截断数据
        App\Model\Tag::truncate();
        factory(App\Model\Tag::class, 5)->create();
    }
}
