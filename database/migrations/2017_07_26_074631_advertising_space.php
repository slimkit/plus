<?php

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

class AdvertisingSpace extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertising_space', function (Blueprint $table) {
            $table->increments('id');
            $table->string('channel', 50)->comment('广告位频道');
            $table->string('space')->comment('广告位位置标识');
            $table->string('alias')->comment('广告位别名');
            $table->string('allow_type')->comment('允许的广告类型');
            $table->text('format')->comment('广告数据格式');
            $table->text('rule')->comment('广告位表单验证规则');
            $table->text('message')->comment('广告位表单验证提示');
            $table->timestamps();

            $table->index('channel');
            $table->index('space');
            $table->unique('space');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertising_space');
    }
}
