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

$component_table_name = 'music_singers';

if (! Schema::hasColumn($component_table_name, 'name')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('name')->comment('歌手名称');
    });
}

if (! Schema::hasColumn($component_table_name, 'cover')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('cover')->comment('歌手封面id');
    });
}
