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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsPinned;
use Zhiyi\Plus\Http\Controllers\Controller;

class AverageController extends Controller
{
    /**
     * @param Request          $request
     * @param Carbon           $date
     * @param FeedPinned       $pinned
     * @param ResponseContract $response
     * @return object
     */
    public function show(Carbon $date, NewsPinned $pinned, ResponseContract $response)
    {
        $averages = [];
        // 资讯置顶平均数
        $average = $pinned->averages('news', $date->subWeek());
        if ($average['total_amount'] && $average['total_day']) {
            $averages['news'] = ceil($average['total_amount'] / $average['total_day']);
        } else {
            $averages['news'] = 100;
        }
        // 评论置顶平均数
        $average = $pinned->averages('news:comment', $date->subWeek());
        if ($average['total_amount'] && $average['total_day']) {
            $averages['news_comment'] = ceil($average['total_amount'] / $average['total_day']);
        } else {
            $averages['news_comment'] = 100;
        }

        return $response->json($averages, 200);
    }
}
