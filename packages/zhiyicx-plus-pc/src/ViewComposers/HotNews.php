<?php

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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\ViewComposers;

use Carbon\Carbon;
use Illuminate\View\View;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;

class HotNews
{
    public function compose(View $view)
    {
        $time = Carbon::now();
        $limit = 10;

        // 每周
        $stime = $time->subDays(7)->toDateTimeString();
        $week = News::byAudit()
                ->where('created_at', '>', $stime)
                ->select('id', 'title', 'hits')
                ->orderBy('hits', 'desc')
                ->take($limit)
                ->get();

        // 每月
        $stime = Carbon::create(null, null, 01); // 本月开始时间
        $etime = Carbon::create(null, null, $time->daysInMonth); // 本月结束时间

        $month = News::byAudit()
                ->whereBetween('created_at', [$stime->toDateTimeString(), $etime->toDateTimeString()])
                ->select('id', 'title', 'hits')
                ->orderBy('hits', 'desc')
                ->take($limit)
                ->get();

        // 每季度
        $season = ceil($time->month / 3); //当月是第几季度
        $stime = Carbon::create($time->year, $season * 3 - 3 + 1, 01, 0, 0, 0); // 本季度开始时间
        $etime = Carbon::create($time->year, $season * 3, $time->daysInMonth, 23, 59, 59); // 本季度结束时间

        $quarter = News::byAudit()
                ->whereBetween('created_at', [$stime->toDateTimeString(), $etime->toDateTimeString()])
                ->select('id', 'title', 'hits')
                ->orderBy('hits', 'desc')
                ->take($limit)
                ->get();

        $view->with('week', $week);
        $view->with('month', $month);
        $view->with('quarter', $quarter);
    }
}
