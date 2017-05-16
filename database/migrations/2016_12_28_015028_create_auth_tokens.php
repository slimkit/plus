<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('token')->comment('验证token');
            $table->string('refresh_token')->comment('刷新token');
            $table->integer('user_id')->comment('token所属用户id');
            $table->integer('expires')->nullable()->default(0)->comment('token生命周期,秒为单位, 0为永久不过期');
            $table->tinyInteger('state')->nullalble()->default(1)->comment('token状态1:在线,0:下线');
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
        Schema::dropIfExists('auth_tokens');
    }
}
