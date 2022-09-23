<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstagramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagrams', function (Blueprint $table) {
            $table->id();
            $table->string('url')->default('');
            $table->integer('user_id')->default(0);
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('status')->default(1)->comment('0:已删除 1:正常 2:待定:审核中 3:待定.已批准 4:拒绝:暴力 5:拒绝:侵权 6:拒绝:色情');
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
        Schema::dropIfExists('instagrams');
    }
}
