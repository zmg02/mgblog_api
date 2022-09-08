<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->default('');
            $table->string('avatar')->default('')->nullable();
            $table->text('desc')->nullable();
            $table->string('phone', 20)->default('')->nullable();
            $table->string('email', 50)->unique()->default('');
            $table->integer('email_verified_time')->default(0);
            $table->string('password', 60)->default('');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_admin')->default(0);
            $table->tinyInteger('is_author')->default(0);
            $table->smallInteger('article_count')->default(0);
            $table->integer('last_login_time')->default(0);
            $table->string('last_login_ip', 30)->default('')->nullable();
            $table->rememberToken();
            $table->string('api_token', 60)->unique()->nullable();
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
        Schema::dropIfExists('users');
    }
}
