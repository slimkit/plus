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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\API2;

use Carbon\Carbon;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

class AverageController extends Controller
{
    /**
     * @param Request          $request
     * @param Carbon           $date
     * @param FeedPinned       $pinned
     * @param ResponseContract $response
     * @return object
     */
    public function show(Carbon $date, FeedPinned $pinned, ResponseContract $response)
    {
        $averages = [];
        // 动态置顶平均数
        $average = $pinned->averages('feed', $date->subWeek());
        if ($average['total_amount'] && $average['total_day']) {
            $averages['feed'] = ceil($average['total_amount'] / $average['total_day']);
        } else {
            $averages['feed'] = 100;
        }
        // 评论置顶平均数
        $average = $pinned->averages('comment', $date->subWeek());
        if ($average['total_amount'] && $average['total_day']) {
            $averages['feed_comment'] = ceil($average['total_amount'] / $average['total_day']);
        } else {
            $averages['feed_comment'] = 100;
        }

        return $response->json($averages, 200);
    }
}
