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

class CreateNativePayOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('native_pay_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->comment('支付类型: alipay,wechatPay');
            $table->string('out_trade_no')->unique()->comment('站内订单号');
            $table->string('trade_no')->nullable()->comment('第三方交易订单号');
            $table->unsignedTinyInteger('status')->default(0)->comment('交易状态, 默认等待确认0, 1成功, 2失败');
            $table->unsignedInteger('amount')->comment('交易金额: 分为单位');
            $table->unsignedInteger('user_id')->comment('订单发起人id');
            $table->string('subject')->comment('订单抬头');
            $table->string('content')->comment('订单内容');
            $table->string('product_code')->comment('订单交易方式');
            $table->unsignedInteger('from')->comment('充值订单来自哪个客户端');
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('native_pay_orders');
    }
}
