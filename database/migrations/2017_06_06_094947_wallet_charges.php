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
                ->increments('id')
                ->comment('记录ID');

            $table
                ->integer('user_id')
                ->unsigned()
                ->nullable()
                ->default(null)
                ->comment('关联用户，可不存在，例如直接支付方式等。存在便于按照用户检索。');

            $table
                ->string('type', 50)
                ->comment('支付平台');

            $table
                ->string('charge_id', 255)
                ->nullable()
                ->default(null)
                ->comment('凭据id, 来自 Ping ++ ');

            $table
                ->string('channel')
                ->comment('支付频道，参考 Ping++');

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
                ->nullable()
                ->default(null)
                ->comment('订单详情');

            $table
                ->string('transaction_no', 255)
                ->nullable()
                ->default(null)
                ->comment('平台记录ID');

            $table
                ->json('extra')
                ->nullable()
                ->default(null)
                ->comment('拓展字段');

            $table->timestamps();
            $table->softDeletes(); // 软删除

            $table->index('user_id');
            $table->index('charge_id');
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
