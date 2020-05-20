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
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News as NewsModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsPinned as NewsPinnedModel;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Notifications\System as SystemNotification;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class NewCommentPinnedController extends Controller
{
    /**
     * 通过评论置顶.
     *
     * @param  Request  $request
     * @param  ResponseContract  $response
     * @param  Carbon  $dateTime
     * @param  NewsModel  $news
     * @param  CommentModel  $comment
     * @param  NewsPinnedModel  $pinned
     *
     * @return mixed
     * @throws \Exception
     * @author BS <414606094@qq.com>
     */
    public function accept(
        Request $request,
        ResponseContract $response,
        Carbon $dateTime,
        NewsModel $news,
        CommentModel $comment,
        NewsPinnedModel $pinned
    ) {
        $user = $request->user();
        if ($user->id !== $news->user_id) {
            return $response->json(['message' => '你没有权限操作'], 403);
        } elseif ($pinned->expires_at) {
            return $response->json(['message' => '已操作，请勿重复发起'], 422);
        }

        // 设置置顶时间
        $pinned->expires_at = $dateTime->addDay($pinned->day);
        $pinned->state = 1; // 审核通过

        $process = new UserProcess();
        $order = $process->receivables($user->id, $pinned->amount, $pinned->user_id, '置顶资讯评论', sprintf('置顶评论《%s》', Str::limit($comment->body, 100, '...')));

        if ($order) {
            $pinned->save();
            $comment->user->notify(new SystemNotification('你申请的资讯评论置顶已通过', [
                'type' => 'pinned:news/comment',
                'state' => 'passed',
                'news' => [
                    'id' => $news->id,
                    'title' => $news->title,
                ],
                'comment' => [
                    'id' => $comment->id,
                    'contents' => $comment->body,
                ],
            ]));

            return $response->json(['message' => '置顶成功'], 201);
        }

        return $response->json(['message' => '操作失败'], 500);
    }

    /**
     * 拒绝评论置顶.
     *
     * @param  Request  $request
     * @param  NewsModel  $news
     * @param  CommentModel  $comment
     * @param  NewsPinnedModel  $pinned
     * @param  ResponseContract  $response
     * @param  Carbon  $dateTime
     *
     * @return mixed
     * @throws \Exception
     * @author BS <414606094@qq.com>
     */
    public function reject(
        Request $request,
        NewsModel $news,
        CommentModel $comment,
        NewsPinnedModel $pinned,
        ResponseContract $response,
        Carbon $dateTime
    ) {
        $user = $request->user();
        if ($user->id !== $pinned->target_user || $pinned->channel !== 'news:comment') {
            return $response->json(['message' => '无效操作'], 422);
        } elseif ($pinned->expires_at) {
            return $response->json(['message' => '已被处理'], 422);
        }

        $pinned->load(['comment']);

        $process = new UserProcess();
        $order = $process->reject($user->id, $pinned->amount, $pinned->user_id, '被拒资讯评论置顶', sprintf('被拒资讯评论《%s》申请，退还申请金额', Str::limit($pinned->comment->body ?? 'null', 100, '...')));

        if ($order) {
            $pinned->expires_at = $dateTime;
            $pinned->state = 2;
            $pinned->save();

            $comment->user->notify(new SystemNotification('你申请的资讯评论置顶未通过', [
                'type' => 'pinned:news/comment',
                'state' => 'rejected',
                'news' => [
                    'id' => $news->id,
                    'title' => $news->title,
                ],
                'comment' => [
                    'id' => $comment->id,
                    'contents' => $comment->body,
                ],
            ]));

            return $response->json(null, 204);
        }

        return $response->json(['message' => '操作失败'], 500);
    }
}
