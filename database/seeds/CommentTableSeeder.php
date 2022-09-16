<?php

use Illuminate\Database\Seeder;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 截断数据
        App\Model\Comment::truncate();
        factory(App\Model\Comment::class, 60)->create();
    }
}
