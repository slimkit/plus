<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicExpertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topic_expert', function (Blueprint $table) {
            $table->integer('topic_id')->unsigned()->comment('话题ID');
            $table->integer('user_id')->unsigned()->comment('用户ID');
            $table->integer('sort')->unsigned()->nullable()->default(0)->comment('排序,权重越大越靠前');
            $table->timestamps();

            $table->primary(['topic_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topic_expert');
    }
}
