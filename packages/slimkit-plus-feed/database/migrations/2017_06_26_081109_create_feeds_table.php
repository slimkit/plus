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
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;

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

            // 可转发的
            $table->string('repostable_type')->nullable()->default(null)->comment('可转发的资源类型');
            $table->integer('repostable_id')->unsigned()->nullable()->default(0)->comment('可转发的资源 ID');

            // 用于热门动态需求
            $table->integer('hot')->unsigned()->nullable()->default(0)->comment('热门排序值');

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->unique('feed_mark');
            $table->index('hot');
            $table->index(FeedModel::CREATED_AT);
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
