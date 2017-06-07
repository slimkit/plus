<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WalletRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_records', function (Blueprint $table) {
            $table
                ->increments('id')
                ->comment('记录ID');

            $table
                ->integer('user_id')
                ->unsigned()
                ->comment('记录关联用户');

            $table
                ->integer('charge_id')
                ->nullable()
                ->default(null)
                ->comment('凭据ID');

            $table
                ->integer('value')
                ->unsigned()
                ->comment('操作金额'); // 真实货币乘 100 的整数单位

            $table
                ->tinyInteger('action')
                ->unsigned()
                ->comment('类型：1 - 增加，0 - 减少');

            $table
                ->string('type', '100')
                ->comment('记录类型，user - 表示用户，alipay - 表示支付宝, wechat - 表示微信, apple - 表示苹果支付');

            $table
                ->string('title')
                ->comment('交易标题');

            $table
                ->text('content')
                ->nullable()
                ->default(null)
                ->comment('交易详情');

            $table
                ->string('account')
                ->nullable()
                ->default(null)
                ->comment('交易账户，减项为目标账户，增项为来源账户，当 type 为 user 时，此处是用户ID');

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
            $table->index('account');
            $table->index('status');
            $table->index('type');
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_records');
    }
}
