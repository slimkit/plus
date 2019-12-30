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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedTopicUserLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_topic_user_links', function (Blueprint $table) {
            $table->increments('index')->comment('The topic followers index');
            $table->integer('topic_id')->unsigned()->comment('Be follow topic id');
            $table->integer('user_id')->unsigned()->comment('Follow topic user id');
            $table->integer('feeds_count')->unsigned()->nullable()->default(0)->comment('The user send to the topic feeds count');
            $table->timestamp('following_at')->nullable()->default(null)->command('The user following the topic date.');
            $table->timestamps();

            $table->unique(['topic_id', 'user_id']);
            $table->index('user_id');
            $table->index('topic_id');
            $table->index(Model::CREATED_AT);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feed_topic_user_links');
    }
}
