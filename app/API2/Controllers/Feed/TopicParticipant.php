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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Zhiyi\Plus\API2\Controllers\Controller;
use Zhiyi\Plus\API2\Requests\Feed\ListParticipantsForATopic as ListParticipantsForATopicRequest;
use Zhiyi\Plus\API2\Resources\Feed\TopicParticipantCollection as TopicParticipantCollectionResponse;
use Zhiyi\Plus\Models\FeedTopicUserLink as FeedTopicUserLinkModel;

class TopicParticipant extends Controller
{
    /**
     * List participants for a topic.
     *
     * @param \Zhiyi\Plus\API2\Requests\Feed\ListParticipantsForATopic $request
     * @param \Zhiyi\Plus\Models\FeedTopicUserLink $model
     * @param int $topic
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ListParticipantsForATopicRequest $request, FeedTopicUserLinkModel $model, int $topic): JsonResponse
    {
        $result = $model
            ->query()
            ->where('topic_id', $topic)
            ->limit($request->query('limit', 15))
            ->offset($request->query('offset', 0))
            ->orderBy(Model::UPDATED_AT, 'desc')
            ->get();

        return (new TopicParticipantCollectionResponse($result))
            ->response()
            ->setStatusCode(JsonResponse::HTTP_OK /* 200 */);
    }
}
