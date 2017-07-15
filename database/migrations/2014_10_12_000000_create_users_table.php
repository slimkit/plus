<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('user id.');
            $table->string('name', 100)->nullable()->default(null)->comment('user name.');
            $table->string('email', 150)->nullable()->default(null)->comment('user email.');
            $table->string('phone', 50)->nullable()->default(null)->comment('user phone member.');
            $table->string('password')->nullable()->default(null)->comment('password.');
            $table->string('bio')->nullable()->default(null)->comment('用户简介');
            $table->tinyInteger('sex')->nullable()->default(0)->comment('用户性别');
            $table->string('location')->nullable()->default(null)->comment('用户位置');
            $table->rememberToken()->comment('user auth token.');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('name');
            $table->unique('email');
            $table->unique('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
