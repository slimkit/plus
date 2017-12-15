<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlusIdClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plus_id_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('客户端名称');
            $table->string('url')->comment('通讯地址');
            $table->string('key')->comment('通讯密钥');
            $table->tinyInteger('sync_login')->nullable()->default(0)->comment('是否同步登陆');
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
        Schema::dropIfExists('plus_id_clients');
    }
}
