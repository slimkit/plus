<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayPublishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_publishes', function (Blueprint $table) {
            $table->increments('id')->comment('付费记录ID');
            $table->string('index')->comment('付费索引');
            $table->string('subject')->comment('付费主题');
            $table->string('body')->comment('付费内容详情');
            $table->integer('amount')->unsigned()->comment('付费金额');
            $table->timestamps();

            $table->unique('index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_publishes');
    }
}
