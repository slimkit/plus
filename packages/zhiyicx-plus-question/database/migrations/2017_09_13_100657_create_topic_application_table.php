<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topic_application', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('申请用户');
            $table->string('name')->comment('申请话题名称');
            $table->string('description')->nullable()->default(null)->comment('申请话题描述');
            $table->tinyInteger('status')->unsigned()->default(0)->comment('审核状态 0-未处理 1-已通过 2-被驳回');
            $table->timestamp('expires_at')->nullable()->comment('处理时间');
            $table->integer('examiner')->unsigned()->default(0)->comment('审核用户');
            $table->string('remarks')->nullable()->comment('备注');
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
        Schema::dropIfExists('topic_application');
    }
}
