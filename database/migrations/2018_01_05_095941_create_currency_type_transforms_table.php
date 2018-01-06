<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyTypeTransformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_type_transforms', function (Blueprint $table) {
            $table->integer('form_type_id')->unsigned()->comment('原始货币类型');
            $table->integer('to_type_id')->unsigned()->comment('目标货币类型');
            $table->integer('form_sum')->unsigned()->comment('原始货币数量');
            $table->integer('to_sum')->unsigned()->comment('目标货币数量');
            $table->primary(['form_type_id', 'to_type_id']);
            $table->index('form_type_id');
            $table->index('to_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_type_transforms');
    }
}
