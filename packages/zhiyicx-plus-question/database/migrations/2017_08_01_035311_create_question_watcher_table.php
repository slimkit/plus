<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionWatcherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_watcher', function (Blueprint $table) {
            $table->integer('question_id')->unsigned()->comment('问题ID');
            $table->integer('user_id')->unsigned()->comment('用户ID');
            $table->timestamps();

            $table->primary(['question_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_watcher');
    }
}
