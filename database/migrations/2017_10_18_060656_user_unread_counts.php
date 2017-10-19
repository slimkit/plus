<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserUnreadCounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_unread_counts', function (Blueprint $table) {
            $table->integer('user_id')->comment('用户ID');
            $table->integer('unread_comments_count')->unsigned()->default(0)->comment('未读评论数');
            $table->integer('unread_likes_count')->unsigned()->default(0)->comment('未读点赞数');
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
        Schema::dropIfExists('user_unread_counts');
    }
}
