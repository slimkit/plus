<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id')->comment('评论ID');
            $table->integer('user_id')->unsigned()->comment('评论用户');
            $table->integer('target_user')->unsigned()->comment('目标用户');
            $table->integer('reply_user')->unsigned()->nullable()->default(0)->comment('回复用户');
            $table->string('channel', 100)->comment('来源频道');
            $table->string('target', 100)->comment('来源目标');
            $table->timestamps();

            $table->index('user_id');
            $table->index('target_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
