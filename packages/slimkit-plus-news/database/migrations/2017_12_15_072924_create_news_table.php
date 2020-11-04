<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id')->comment('主键');
            $table->string('title')->comment('新闻标题');
            $table->integer('storage')->nullable()->default(0)->comment('缩略图id');
            $table->longtext('content')->comment('新闻内容');
            $table->integer('digg_count')->default(0)->comment('点赞数');
            $table->integer('comment_count')->default(0)->comment('评论数');
            $table->integer('hits')->default(0)->comment('点击数');
            $table->string('from')->nullable()->comment('来源');
            $table->tinyInteger('is_recommend')->default(0)->comment('是否推荐');
            $table->text('subject')->nullable()->comment('副标题');
            $table->string('author', 100)->comment('文章作者');
            $table->tinyInteger('audit_status')->default(0)->comment('文章状态 0-正常 1-待审核 2-草稿 3-驳回 4-删除 5-退款中');
            $table->tinyInteger('audit_count')->nullable()->default(0)->comment('审核次数统计');
            $table->integer('user_id')->unsigned()->comment('用户ID');
            $table->integer('cate_id')->unsigned()->comment('分类');
            $table->bigInteger('contribute_amount')->unsigned()->nullable()->default(0)->comment('投稿金额');
            $table->longtext('text_content')->nullable()->comment('纯文字内容');
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
        Schema::dropIfExists('news');
    }
}
