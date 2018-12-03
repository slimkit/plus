<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 150)->comment('文章标题');
            $table->text('contents')->comment('文章内容');
            $table->integer('blog_id')->unsigned()->comment('文章所属博客');
            $table->integer('creator_id')->unsigned()->comment('文章创建者');
            $table->integer('comments_count')->unsigned()->nullable()->default(0)->comment('文章评论数量统计');
            $table->timestamp('reviewed_at')->nullable()->default(null)->comment('审核通过时间');
            $table->timestamps();

            // 索引
            $table->index('blog_id');
            $table->index('creator_id');
            $table->index('reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_articles');
    }
}
