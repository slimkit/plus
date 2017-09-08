<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamousUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('famous', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('被设置用户的id');
            $table->string('type')->default('')->comment('类型[ {each: 相互关注}, {followed: 被关注}]');
            $table->timestamps();
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('famous');
    }
}
