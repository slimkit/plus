<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImConversationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('im_conversations', function (Blueprint $table) {
            $table->increments('id')->comment('对话表表ID');
            $table->integer('user_id')->default(0)->comment('创建对话用户UID');
            $table->bigInteger('cid')->default(0)->comment('对话id');
            $table->string('name')->dafault(null)->comment('对话名称');
            $table->string('pwd')->default(null)->comment('加入对话密码');
            $table->tinyInteger('is_disabled')->default(0)->comment('是否被禁用,1:是 0:否');
            $table->tinyInteger('type')->default(0)->comment('对话类型 0:私聊 1:群聊 2:聊天室');
            $table->text('uids')->default(null)->comment('已加入聊天的用户UID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('im_conversations');
    }
}
