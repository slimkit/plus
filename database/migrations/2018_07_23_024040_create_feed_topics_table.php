<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
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
            $table->integer('logo')->unsigned()->nullable()->default(0)->comment('The topic logo, file with id');
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
