<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_extras', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->comment('用户标识');
            $table->integer('like_count')->unsigned()->nullable()->default(0)->comment('点赞统计');
            $table->integer('comment_count')->unsigned()->nullable()->default(0)->comment('评论统计');
            $table->integer('follower_count')->unsigned()->nullable()->default(0)->comment('粉丝统计');
            $table->integer('following_count')->unsigned()->nullable()->default(0)->comment('关注数统计');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_extras');
    }
}
