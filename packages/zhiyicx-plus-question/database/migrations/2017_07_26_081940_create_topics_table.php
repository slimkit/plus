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

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id')->comment('话题ID');
            $table->string('name', 100)->comment('话题名称');
            $table->string('description')->nullable()->default(null)->comment('话题描述');
            $table->integer('questions_count')->unsigned()->nullable()->default(0)->comment('话题问题统计');
            $table->integer('follows_count')->unsigned()->nullable()->default(0)->comment('话题关注者统计');
            $table->integer('experts_count')->unsigned()->nullable()->default(0)->comment('话题下的专家统计');
            $table->integer('sort')->unsigned()->nullable()->default(0)->comment('排序,权重越大越靠前');
            $table->tinyInteger('status')->unsigned()->nullable()->default(0)->comment('0 开启 1关闭');
            $table->timestamps();

            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
    }
}
