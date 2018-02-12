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

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('圈子名称');
            $table->integer('user_id')->unsigned()->comment('创建者');
            $table->integer('category_id')->unsigned()->comment('类别 ID');
            $table->string('location')->nullable()->default(null)->comment('位置');
            $table->string('longitude')->nullable()->default(null)->comment('经度');
            $table->string('latitude')->nullable()->default(null)->comment('维度');
            $table->string('geo_hash', 100)->nullable()->default(null)->comment('地理位置范围');
            $table->tinyInteger('allow_feed')->nullable()->default(0)->comment('是否允许发布到 feed');
            $table->string('mode', 100)->nullable()->default('public')->comment('public: 公开，private：私有，paid：付费的');
            $table->integer('money')->unsigned()->nullable()->default(0)->comment('如果 mode 为 paid 用于标示收费金额');
            $table->text('summary')->nullable()->default(null)->comment('概述 - 简介');
            $table->text('notice')->nullable()->default(null)->comment('公告');
            $table->string('permissions')->nullable()->default('member,administrator,founder')->comment('圈子权限, 1 - 所有-member,administrator,founder、2-圈主和管理员: administrator,founder, 3 - 圈主: founder');
            $table->integer('users_count')->unsigned()->nullable()->default(0)->comment('成员数');
            $table->integer('posts_count')->unsigned()->nullable()->default(0)->comment('帖子数');
            $table->tinyInteger('audit')->nullable()->defalt(0)->comment('审核状态, 0 - 待审核、1 - 通过、2 - 拒绝');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('name');
            $table->index('user_id');
            $table->index('category_id');
            $table->index('geo_hash');
            $table->index('mode');
            $table->index('audit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
