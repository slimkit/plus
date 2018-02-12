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

class CreateGroupPinnedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_pinneds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('channel')->comment('频道');
            $table->integer('raw')->unsigned()->nullable()->default(0)->comment('所属资源id，当置顶对象为评论时，该值为帖子id');
            $table->integer('target')->unsigned()->comment('目标ID');
            $table->integer('user_id')->unsigned()->comment('用户');
            $table->integer('target_user')->unsigned()->nullable()->default(null)->comment('目标用户');
            $table->bigInteger('amount')->unsigned()->comment('金额');
            $table->integer('day')->comment('固定期限，单位 天');
            $table->timestamp('expires_at')->nullable()->comment('置顶到期时间');
            $table->integer('status')->unsigned()->default(0)->comment('处理状态 0-待审核 1-已通过 2-已拒绝');
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
        Schema::dropIfExists('group_pinneds');
    }
}
