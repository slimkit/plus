<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserFollow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_follow', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('关注ID');

            // 操作对象用户
            $table
                ->integer('user_id')
                ->unsigned()
                ->comment('对象用户');

            // 操作目标用户
            $table
                ->integer('target')
                ->unsigned()
                ->comment('目标用户');

            $table->timestamps(); // 对象时间维护

            // 外健约束
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table
                ->foreign('target')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // 复合唯一健
            $table->unique(['user_id', 'target']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_follow');
    }
}
