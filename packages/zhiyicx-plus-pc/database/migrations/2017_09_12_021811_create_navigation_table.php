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

class CreateNavigationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->default('')->comment('导航名称');
            $table->string('app_name')->nullable()->default('')->comment('英文名');
            $table->string('url')->nullable()->default('')->comment('跳转链接');
            $table->string('target')->nullable()->default('')->comment('打开方式');
            $table->tinyInteger('status')->nullable()->default(1)->unsigned()->comment('状态 0-关闭 1-开启');
            $table->tinyInteger('position')->nullable()->default(0)->unsigned()->comment('导航位置 0-顶部 1-底部');
            $table->integer('parent_id')->nullable()->default(0)->unsigned()->comment('pid');
            $table->integer('order_sort')->nullable()->default(0)->unsigned()->comment('排序');
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
        Schema::drop('navigation');
    }
}
