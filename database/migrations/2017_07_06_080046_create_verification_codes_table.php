<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerificationCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->default(null)->comment('关联用户');
            $table->string('channel', 50)->comment('发送频道，例如 mail, sms');
            $table->string('account', 100)->comment('发送账户');
            $table->string('code', 20)->comment('发送验证码');
            $table->tinyInteger('state')->nullable()->default(0)->comment('状态');
            $table->timestamps();
            $table->softDeletes();

            $table->index('account');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verification_codes');
    }
}
