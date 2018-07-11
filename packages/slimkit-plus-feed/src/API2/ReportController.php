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

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Report as ReportModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;

class ReportController extends Controller
{
    /**
     * 举报一条动态.
     *
     * @param Request $request
     * @param FeedModel $feed
     * @param ReportModel $reportModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function feed(Request $request, FeedModel $feed, ReportModel $reportModel)
    {
        $auth_user = $request->user();

        $reportModel->user_id = $auth_user->id;
        $reportModel->target_user = $feed->user_id;
        $reportModel->status = 0;
        $reportModel->reason = $request->input('reason');
        $reportModel->subject = empty($feed->feed_content) ? sprintf('动态：id:%s', $feed->id) : sprintf('动态：%s', mb_substr($feed->feed_content, 0, 50));

        $feed->reports()->save($reportModel);

        return response()->json(['message' => ['操作成功']], 201);
    }
}
