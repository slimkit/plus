<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

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
