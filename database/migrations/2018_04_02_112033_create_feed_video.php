<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedVideo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('记录所属者');
            $table->integer('video_id')->unsigned()->comment('视频filewith_id');
            $table->integer('cover_id')->unsigned()->comment('视频封面filewith_id');
            $table->integer('feed_id')->unsigned()->comment('所属动态id');
            $table->integer('height')->unsigned()->comment('视频高度');
            $table->integer('width')->unsigned()->comment('视频宽度');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('video_id')->references('id')->on('file_withs');
            $table->foreign('feed_id')->references('id')->on('feeds');
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
        Schema::dropIfExists('feed_videos');
    }
}
