<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('name')->unique()->nullable()->default(null)->comment('user name.');
            $table->string('email')->unique()->nullable()->default(null)->comment('user email.');
            $table->string('phone')->unique()->nullable()->default(null)->comment('user phone member.');
            $table->string('password')->comment('password.');
            $table->rememberToken()->comment('user auth token.');
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
        Schema::dropIfExists('users');
    }
}
