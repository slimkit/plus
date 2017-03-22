<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_records', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->comment('用户id');
            $table->string('ip', 100)->nullable()->default('')->comment('ip地址，可为空');
            $table->string('address')->nullable()->default('')->comment('登录设备大致地理位置，可为空');
            $table->string('device_system')->nullable()->default('')->comment('登录设备操作系统，可为空');
            $table->string('device_name')->nullable()->default('')->comment('登录设备名称，可为空');
            $table->string('device_model')->nullable()->default('')->comment('登录设备型号，可为空');
            $table->string('device_code')->nullable()->default('')->comment('设备码，可为空');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_records');
    }
}
