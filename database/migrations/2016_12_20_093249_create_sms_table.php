<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('to')->default('')->comment('被发送的手机号码');
            $table->string('template_id')->nullable()->default('')->comment('短信模板id');
            $table->string('data')->nullable()->default('')->comment('数据');
            $table->string('content')->nullable()->default('')->comment('内容');
            $table->timestamps();
            $table->softDeletes();

            $table->index('to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms');
    }
}
