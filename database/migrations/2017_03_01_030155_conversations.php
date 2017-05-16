<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Conversations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            // 创建表结构
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('type')->default('system')->comment('会话类型 system 系统通知 feedback 用户反馈');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->integer('to_user_id')->default(0)->comment('被发送用户ID');
            $table->text('content')->comment('会话内容');
            $table->text('options')->nullable()->comment('给推送平台的额外参数');
            $table->bigInteger('system_mark')->default(null)->comment('移动端存储标记');
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
        Schema::dropIfExists('conversations');
    }
}
