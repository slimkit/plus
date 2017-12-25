<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusicSpecialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music_specials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('专辑标题');
            $table->string('intro')->nullable()->comment('简介');
            $table->integer('storage')->comment('封面id');
            $table->integer('taste_count')->default(0)->comment('播放数量');
            $table->integer('share_count')->default(0)->comment('分享数量');
            $table->integer('comment_count')->default(0)->comment('评论数量');
            $table->integer('collect_count')->default(0)->comment('收藏数量');
            $table->integer('sort')->default(0)->comment('权重');
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
        Schema::dropIfExists('music_specials');
    }
}
