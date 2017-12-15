<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlusIdNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plus_id_notices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender')->comment('发送者');
            $table->string('recipient')->comment('接受者');
            $table->string('desc')->nullable()->default(null)->comment('描述');
            $table->text('data')->comment('数据');
            $table->string('status', 100)->comment('状态');
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
        Schema::dropIfExists('plus_id_notices');
    }
}
