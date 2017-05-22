<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserFollow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_follow', function (Blueprint $table) {
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

            $table->timestamps() // 对象时间维护
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('follow_user', function (Blueprint $table) {
            //
        });
    }
}
