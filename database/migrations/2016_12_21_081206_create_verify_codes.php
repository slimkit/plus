<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerifyCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account')->comment('账户');
            $table->integer('code')->comment('验证码');
            $table->string('content')->nullable()->default('')->comment('内容');
            $table->string('data')->nullable()->default('')->comment('数据');
            $table->timestamps();

            $table->index('account');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verfiy_codes');
    }
}
