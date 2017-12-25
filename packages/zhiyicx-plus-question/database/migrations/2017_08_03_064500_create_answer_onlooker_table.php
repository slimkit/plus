<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerOnlookerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_onlooker', function (Blueprint $table) {
            $table->integer('answer_id')->unsigned()->comment('回答ID');
            $table->integer('user_id')->unsigned()->comment('用户ID');
            $table->integer('amount')->unsigned()->comment('围观金额');
            $table->timestamps();

            $table->primary(['answer_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_onlooker');
    }
}
