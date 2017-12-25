<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_incomes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->comment('所属圈子');
            $table->string('subject')->comment('收入内容');
            $table->tinyInteger('type')->unsigned()->default(1)->comment('收入类型 1-加入圈子收入 2-圈内置顶收入');
            $table->integer('amount')->unsigned()->comment('收入金额');
            $table->integer('user_id')->unsigned()->comment('来源用户');
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
        Schema::dropIfExists('group_incomes');
    }
}
