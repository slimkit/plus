<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->unsigned()->comment('记录所属者');
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
        Schema::dropIfExists('currency_orders');
    }
}
