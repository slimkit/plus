<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('im_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->comment('表ID');
            $table->integer('user_id')->unique()->default(0)->comment('用户ID');
            $table->string('im_password')->default(null)->comment('聊天用户登陆的密码');
            $table->tinyInteger('is_disabled')->default(0)->comment('是否被禁用,1:是 0:否');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('im_users');
    }
}
