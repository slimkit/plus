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

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupMemberLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_member_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->comment('圈子ID');
            $table->integer('user_id')->unsigned()->comment('用户ID');
            $table->integer('member_id')->unsigned()->comment('圈子成员ID');
            $table->tinyInteger('status')->default(0)->comment('审核状态：0 - 待审核、1 - 通过、2 - 拒绝');
            $table->integer('auditer')->nullable()->unsigned()->comment('审核人');
            $table->timestamp('audit_at')->nullable()->comment('处理时间');
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
        Schema::dropIfExists('group_member_logs');
    }
}
