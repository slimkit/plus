<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_application', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('用户');
            $table->integer('question_id')->unsigned()->comment('问题id');
            $table->bigInteger('amount')->unsigned()->comment('金额');
            $table->timestamp('expires_at')->nullable()->comment('处理时间');
            $table->integer('examiner')->unsigned()->default(0)->comment('审核用户');
            $table->tinyInteger('status')->nullable()->default(0)->comment('审核状态 0-正在申请，1 - 已加精， 2 - 已拒绝');
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
        Schema::dropIfExists('question_application');
    }
}
