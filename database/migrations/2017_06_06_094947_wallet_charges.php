<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WalletCharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_charges', function (Blueprint $table) {
            $table
                ->increments('id');

            $table
                ->integer('user_id')
                ->unsigned()
                ->nullable()
                ->default(null)
                ->comment('关联用户，可不存在，例如直接支付方式等。存在便于按照用户检索。');

            $table
                ->string('channel')
                ->comment('支付频道，参考 Ping++，增加 user 选项，表示站内用户凭据');

            $table
                ->string('account')
                ->nullable()
                ->default(null)
                ->comment('交易账户，减项为目标账户，增项为来源账户，当 type 为 user 时，此处是用户ID');

            $table
                ->string('charge_id', 255)
                ->nullable()
                ->default(null)
                ->comment('凭据id, 来自 Ping ++ ');

            $table
                ->tinyInteger('action')
                ->unsigned()
                ->comment('类型：1 - 增加，0 - 减少');

            $table
                ->integer('amount')
                ->unsigned()
                ->comment('总额');

            $table
                ->string('currency')
                ->nullable()
                ->default('cny')
                ->comment('货币类型');

            $table
                ->string('subject', 255)
                ->comment('订单标题');

            $table
                ->text('body')
                ->comment('订单详情');

            $table
                ->string('transaction_no', 255)
                ->nullable()
                ->default(null)
                ->comment('平台记录ID');

            $table
                ->tinyInteger('status')
                ->unsigned()
                ->nullable()
                ->default(0)
                ->comment('状态：0 - 等待, 1 - 成功, 2 - 失败');

            $table->timestamps();
            $table->softDeletes(); // 软删除

            // 外健约束
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->index('user_id');
            $table->index('charge_id');
            $table->index('channel');
            $table->index('account');
            $table->index('status');
            $table->index('action');
            $table->index('transaction_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_charges');
    }
}
