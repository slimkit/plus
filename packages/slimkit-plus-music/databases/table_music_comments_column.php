<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

$component_table_name = 'music_comments';

if (! Schema::hasColumn($component_table_name, 'comment_content')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('comment_content')->comment('评论内容');
    });
}

if (! Schema::hasColumn($component_table_name, 'user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('user_id')->comment('用户id');
    });
}

if (! Schema::hasColumn($component_table_name, 'reply_to_user_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('reply_to_user_id')->default(0)->comment('被回复的用户id');
    });
}

if (! Schema::hasColumn($component_table_name, 'music_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('music_id')->default(0)->comment('歌曲id');
    });
}

if (! Schema::hasColumn($component_table_name, 'special_id')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('special_id')->default(0)->comment('专辑id');
    });
}

if (! Schema::hasColumn($component_table_name, 'comment_mark')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->bigInteger('comment_mark')->default(null)->comment('移动端存储标记');
    });
}
