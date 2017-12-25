<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicExpertIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topic_expert_income', function (Blueprint $table) {
            $table->integer('charge_id')->unsigned()->comment('钱包凭据id');
            $table->integer('user_id')->unsigned()->comment('收入用户');
            $table->bigInteger('amount')->unsigned()->comment('收入金额');
            $table->string('type')->comment('收入类型');
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
        Schema::dropIfExists('topic_expert_income');
    }
}
