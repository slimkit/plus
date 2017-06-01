<?php

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
                ->integer('value')
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
