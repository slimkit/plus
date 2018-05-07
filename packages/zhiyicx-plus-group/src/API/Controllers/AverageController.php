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

namespace Zhiyi\PlusGroup\API\Controllers;

use Illuminate\Support\Carbon;
use Zhiyi\PlusGroup\Models\Pinned;
use Zhiyi\Plus\Http\Controllers\Controller;

class AverageController extends Controller
{
    public function show(Carbon $date, Pinned $pinned)
    {
        $averages = [];
        $averagePosts = $pinned->averages('post', $date->subWeek());
        if($averagePosts['total_amount'] && $averagePosts['total_day']) {
            $averages['post'] = ceil($averagePosts['total_amount'] / $averagePosts['total_day']);
        } else {
            $averages['post'] = 100;
        }
        $averageComments = $pinned->averages('comment', $date->subWeek());
        if($averageComments['total_amount'] && $averageComments['total_day']) {
            $averages['post_comment'] = ceil($averageComments['total_amount'] / $averageComments['total_day']);
        } else {
            $averages['post_comment'] = 100;
        }

        return response()->json($averages, 200);
    }
}
