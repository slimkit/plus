<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->comment('所属圈子');
            $table->integer('user_id')->unsigned()->comment('发布者');
            $table->string('title')->comment('标题');
            $table->text('body')->comment('markdown 内容');
            $table->string('summary')->comment('列表专用字段，概述，简短内容');
            $table->integer('likes_count')->unsigned()->nullable()->default(0)->comment('喜欢数量统计');
            $table->integer('comments_count')->unsigned()->nullable()->default(0)->comment('评论数统计');
            $table->integer('views_count')->unsigned()->nullable()->default(0)->comment('查看数统计');
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
        Schema::dropIfExists('group_posts');
    }
}
