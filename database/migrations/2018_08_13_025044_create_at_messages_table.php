<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('at_messages', function (Blueprint $table) {
            $table->increments('id')->comment('自增 ID');
            $table->string('resourceable_type')->comment('资源类型');
            $table->integer('resourceable_id')->unsigned()->comment('资源 ID');
            $table->integer('user_id')->unsigned()->comment('接受 At Message 消息用户');
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('at_messages');
    }
}
