<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyCommoditiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_commodities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator')->unsigned()->comment('创建者用户ID');
            $table->integer('currency')->unsigned()->comment('货币类型ID');
            $table->integer('amount')->unsigned()->comment('货币总价');
            $table->string('title')->comment('商品标题');
            $table->text('body')->nullable()->default(null)->comment('商品详情');
            $table->integer('purchase_count')->unsigned()->default(0)->comment('购买统计');
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
        Schema::dropIfExists('currency_commodities');
    }
}
