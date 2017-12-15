<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feeds', function (Blueprint $table) {
            $table->increments('id')->comment('动态 ID');
            $table->integer('user_id')->unsigned()->comment('作者UID');
            $table->text('feed_content')->nullable()->comment('动态内容');
            $table->tinyInteger('feed_from')->unsigned()->nullable()->default(1)->comment('动态来源 1:pc 2:h5 3:ios 4:android 5:其他');
            $table->integer('like_count')->nullable()->default(0)->unsigned()->comment('动态点赞数');
            $table->integer('feed_view_count')->nullable()->default(0)->unsigned()->comment('动态阅读数');
            $table->integer('feed_comment_count')->nullable()->default(0)->unsigned()->comment('动态评论数');
            $table->string('feed_latitude')->nullable()->default('')->comment('纬度');
            $table->string('feed_longtitude')->nullable()->default('')->comment('经度');
            $table->string('feed_geohash')->nullable()->default('')->comment('GEO');
            $table->string('feed_client_id')->nullable()->default('::1')->comment('发布IP');
            $table->tinyInteger('audit_status')->nullable()->default(1)->comment('审核状态 0-未审核 1-已审核 2-未通过');
            $table->bigInteger('feed_mark')->comment('唯一标记');

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->unique('feed_mark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feeds');
    }
}
