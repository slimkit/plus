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

namespace Zhiyi\Plus\API2\Controllers\Feed;

use Illuminate\Http\Response;
use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\Plus\API2\Requests\Feed\ReportATopic as ReportATopicRequest;
use Zhiyi\Plus\Models\FeedTopic as FeedTopicModel;
use Zhiyi\Plus\Models\Report as ReportModel;

class TopicReport extends Controller
{
    /**
     * Create the action instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Report a topic.
     *
     * @param \Zhiyi\Plus\API2\Requests\Feed\ReportATopic $request
     * @param \Zhiyi\Plus\Models\FeedTopic $topic
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ReportATopicRequest $request, FeedTopicModel $topic): Response
    {
        $report = new ReportModel();
        $report->reason = $request->input('message');
        $report->user_id = $request->user()->id;
        $report->target_user = $topic->creator_user_id;
        $report->subject = sprintf('动态话题（%d）：%s', $topic->id, $topic->name);
        $report->status = 0;
        $topic->reports()->save($report);

        return (new Response)->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
