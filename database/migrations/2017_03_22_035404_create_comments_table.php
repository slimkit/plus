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
            $table->increments('id');
            $table->string('component')->comment('扩展包名');
            $table->integer('comment_id')->comment('关联评论id');
            $table->integer('user_id')->default(0)->comment('评论者id');
            $table->integer('to_user_id')->default(0)->comment('资源作者id');
            $table->integer('reply_to_user_id')->default(0)->comment('被回复的评论者id');
            $table->timestamps();
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
