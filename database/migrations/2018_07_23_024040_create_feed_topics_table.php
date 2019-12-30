<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Zhiyi\Plus\Models\FeedTopic as FeedTopicModel;

class CreateFeedTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('The topic name');
            $table->string('logo')->nullable()->default(null)->comment('The topic logo');
            $table->text('desc')->nullable()->comment('The topic desc');
            $table->integer('creator_user_id')->unsigned()->comment('The topic creator user ID');
            $table->integer('feeds_count')->unsigned()->nullable()->default(0)->comment('The topic link feeds count');
            $table->integer('followers_count')->unsigned()->nullable()->default(0)->comment('The topic followers count');
            $table->dateTime('hot_at')->nullable()->default(null)->comment('设置为热门的时间');
            $table->string('status', 100)->nullable()->default(FeedTopicModel::REVIEW_WAITING)->comment('Review status');
            $table->timestamps();

            $table->unique('name');
            $table->index('creator_user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feed_topics');
    }
}
