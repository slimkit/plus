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

class WalletCashes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_cashes', function (Blueprint $table) {
            $table
                ->bigIncrements('id')
                ->comment('提现记录ID');

            $table
                ->integer('user_id')
                ->unsigned()
                ->comment('提现用户');

            $table
                ->bigInteger('value')
                ->unsigned()
                ->comment('需要提现的金额');

            $table
                ->string('type', '100')
                ->comment('提现方式');

            $table
                ->string('account')
                ->comment('提现账户');

            $table
                ->tinyInteger('status')
                ->unsigned()
                ->nullable()
                ->default(0)
                ->comment('状态：0 - 待审批，1 - 已审批，2 - 被拒绝');

            $table
                ->text('remark')
                ->nullable()
                ->default(null)
                ->comment('备注');

            $table->timestamps(); // 自动维护时间
            $table->softDeletes(); // 软删除

            // 外健约束
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('wallet_cashes');
    }
}
