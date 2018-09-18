<?php

declare(strict_types=1);

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

class CreateNewsPinnedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_pinneds', function (Blueprint $table) {
            $table->increments('id')->comment('主键');
            $table->string('channel')->comment('频道: 资讯或资讯评论');
            $table->unsignedInteger('raw')->nullable()->default(0)->comment('如果存在则为评论id');
            $table->unsignedInteger('target')->comment('资讯id');
            $table->unsignedInteger('user_id')->comment('申请者id');
            $table->unsignedTinyInteger('state')->default(0)->comment('审核状态0: 待审核, 1审核通过, 2被拒');
            $table->unsignedInteger('target_user')->unllable()->default(0)->comment('资讯作者');
            $table->bigInteger('amount')->unsigned()->comment('金额');
            $table->unsignedInteger('day')->comment('天数');
            $table->unsignedInteger('cate_id')->nullable()->default(null)->comment('资讯置顶所属分类');
            $table->timestamp('expires_at')->nullable()->comment('到期时间，固定后设置该时间');
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
        Schema::dropIfExists('news_pinneds');
    }
}
