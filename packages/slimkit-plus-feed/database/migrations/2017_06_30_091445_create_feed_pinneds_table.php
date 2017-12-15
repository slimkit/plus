<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedPinnedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_pinneds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('channel')->comment('频道');
            $table->integer('raw')->unsigned()->nullable()->default(0);
            $table->integer('target')->unsigned()->comment('目标ID');
            $table->integer('user_id')->unsigned()->comment('用户');
            $table->integer('target_user')->unsigned()->nullable()->default(null)->comment('目标用户');
            $table->bigInteger('amount')->unsigned()->comment('金额');
            $table->integer('day')->comment('固定期限，单位 天');
            $table->timestamp('expires_at')->nullable()->comment('到期时间，固定后设置该时间');
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
        Schema::dropIfExists('feed_pinneds');
    }
}
