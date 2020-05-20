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

class CreateWalletOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->unsigned()->comment('记录所属者');
            $table->string('target_type')->comment('目标类型');
            $table->string('target_id')->comment('目标标识');
            $table->string('title')->comment('订单标题');
            $table->text('body')->nullable()->default(null)->comment('详情');
            $table->tinyInteger('type')->comment('1：入账、-1：支出');
            $table->integer('amount')->unsigned()->comment('订单金额');
            $table->tinyInteger('state')->nullable()->default(0)->comment('订单状态，0: 等待，1：成功，-1: 失败');
            $table->timestamps();
            $table->index('owner_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_orders');
    }
}
