<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token', 255)->comment('验证token');
            $table->string('refresh_token', 255)->comment('刷新token');
            $table->integer('user_id')->comment('token所属用户id');
            $table->integer('expires')->nullable()->default(0)->comment('token生命周期');
            $table->string('device_code')->nullable()->default('')->comment('设备码');
            $table->timestamps();
            $table->softDeletes();
            $table->index('token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_tokens');
    }
}
