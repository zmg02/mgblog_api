<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->string('default_img')->default('');
            $table->string('title')->default('');
            $table->longText('content');
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->smallInteger('comment_count')->default(0);
            $table->smallInteger('praise_count')->default(0);
            $table->integer('create_time')->default(0);
            $table->integer('update_time')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
