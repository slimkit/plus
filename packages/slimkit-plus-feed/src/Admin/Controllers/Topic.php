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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Admin\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Admin\Requests\ListAllTopics as ListTopicsRequest;
use Zhiyi\Plus\API2\Controllers\Feed\Topic as Controller;
use Zhiyi\Plus\Models\FeedTopic as TopicModel;
use Zhiyi\Plus\Notifications\System as SystemNotification;
use function Zhiyi\Plus\setting;

class Topic extends Controller
{
    public function __construct()
    {
        // Add DisposeSensitive middleware.
        $this
            ->middleware('sensitive:name,desc')
            ->only(['create', 'update']);
    }

    public function adminListTopics(ListTopicsRequest $request, TopicModel $model): JsonResponse
    {
        $data = $model
            ->query()
            ->with([
                'creator',
            ])
            ->when($id = $request->query('id'), function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->when($request->query('hot'), function ($query) {
                return $query->whereNotNull('hot_at');
            })
            ->when($name = $request->query('name'), function ($query) use ($name) {
                return $query->where('name', 'like', sprintf('%%%s%%', $name));
            })
            ->orderBy($request->query('orderBy', 'id'), $request->query('direction', 'desc'))
            ->paginate($request->query('limit', 15), ['*'], 'page');

        return new JsonResponse($data);
    }

    public function hotToggle(TopicModel $topic)
    {
        $topic->hot_at = $topic->hot_at ? null : new Carbon;
        $topic->save();

        return response('', 204);
    }

    public function destroy(TopicModel $topic)
    {
        return $topic->getConnection()->transaction(function () use ($topic) {
            // 删除关联用户
            $topic->users()->sync([], true);

            // 删除动态关联
            $topic->feeds()->sync([], true);

            // 删除话题
            $topic->delete();

            return response('', 204);
        });
    }

    public function getReviewSwitch(): JsonResponse
    {
        return new JsonResponse([
            'switch' => (bool) setting('feed', 'topic:need-review', false),
        ], JsonResponse::HTTP_OK);
    }

    public function reviewSwitchToggle(): Response
    {
        $setting = setting('feed');
        $setting->set('topic:need-review', ! $setting->get('topic:need-review'));

        return new Response('', 204);
    }

    public function toggleReview(Request $request, TopicModel $topic): Response
    {
        $status = $request->input('status');
        if (! in_array($status, [TopicModel::REVIEW_PASSED, TopicModel::REVIEW_FAILED, TopicModel::REVIEW_WAITING])) {
            throw new UnprocessableEntityHttpException('切换的状态不合法');
        }

        $topic->status = $status;
        // 发送拒绝或通过的审核通知
        if ($topic->creator_user_id && (bool) setting('feed', 'topic:need-review', false)) {
            $msg = sprintf('创建话题「%s」的请求已被%s', $topic->name, $status === 'passed' ? '通过' : '拒绝');
            $topic->creator->notify(new SystemNotification($msg, [
                'type' => sprintf('feed:topic:create:%s', $status),
                'contents' => $msg,
                'topic' => [
                    'id' => $topic->id,
                    'name' => $topic->name,
                ],
            ]));
        }
        $topic->save();

        return new Response('', 204);
    }
}
