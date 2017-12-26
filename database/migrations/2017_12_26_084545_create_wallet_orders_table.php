<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->tinyInteger('type', 1)->comment('1：入账、-1：支出');
            $table->integer('amount')->unsigned()->comment('订单金额');
            $table->tinyInteger('state', 1)->nullable()->default(0)->comment('订单状态，0: 等待，1：成功，-1: 失败');
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
        Schema::dropIfExists('wallet_orders');
    }
}
