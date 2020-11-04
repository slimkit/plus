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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News as NewsModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsPinned as NewsPinnedModel;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\Plus\Notifications\System as SystemNotification;

class CommentPinnedController extends Controller
{
    /**
     * 获取资讯评论当前用户审核列表.
     *
     * @param Request $request
     * @param NewsPinnedModel $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, NewsPinnedModel $model)
    {
        $user = $request->user();
        $limit = $request->query('limit', 15);
        $after = $request->query('after');

        $grammar = $model->getConnection()->getQueryGrammar();
        $pinneds = $model->with('comment')
            ->where('channel', 'news:comment')
            ->where('target_user', $user->id)
            ->when(boolval($after), function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->orderByRaw(str_replace('{state}', $grammar->wrap('state'), '
                CASE
                    WHEN ({state} = 0) THEN 1
                    WHEN ({state} != 0 ) THEN 2
                END ASC
            '))
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();

        $pinneds->load(['news', 'user']);

        return response()->json($pinneds, 200);
    }

    public function newsList(Request $request)
    {
        $user = $request->user();
        $after = $request->input('after');
        $limit = $request->input('limit', 15);

        $news = NewsPinnedModel::where('user_id', $user->id)
            ->when($after, function ($query) use ($after) {
                $query->where('id', '<', $after);
            })
            ->where('channel', 'news')
            ->limit($limit)
            ->get();

        return response()->json($news)->setStatusCode(200);
    }

    /**
     *  审核评论置顶.
     * @param Request $request [description]
     * @param ResponseContract $response [description]
     * @param Carbon $dateTime [description]
     * @param WalletChargeModel $charge [description]
     * @param NewsModel $news [description]
     * @param CommentModel $comment [description]
     * @param NewsPinnedModel $pinned [description]
     * @return JsonResponse [type]                      [description]
     * @throws Throwable
     * @author Wayne < qiaobinloverabbi@gmail.com >
     */
    public function accept(
        Request $request,
        ResponseContract $response,
        Carbon $dateTime,
        WalletChargeModel $charge,
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

        // 动态发起人增加收款凭据
        $charge->user_id = $user->id;
        $charge->channel = 'user';
        $charge->account = $pinned->user_id;
        $charge->action = 1;
        $charge->amount = $pinned->amount;
        $charge->subject = '置顶资讯动态评论';
        $charge->body = sprintf('置顶评论《%s》', Str::limit($comment->body, 100, '...'));

        $news->getConnection()->transaction(function () use ($pinned, $user, $charge) {
            $pinned->save();
            $user->currency()->increment('sum', $charge->amount);
            $user->walletCharges()->save($charge);
        });

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

    /**
     *  拒绝评论置顶.
     *  @author Wayne < qiaobinloverabbi@gmail.com >
     *  @param  Request           $request  [description]
     *  @param  ResponseContract  $response [description]
     *  @param  Carbon            $dateTime [description]
     *  @param  WalletChargeModel $charge   [description]
     *  @param  NewsPinnedModel   $pinned   [description]
     *  @return [type]                      [description]
     */
    public function reject(
        Request $request,
        NewsModel $news,
        CommentModel $comment,
        NewsPinnedModel $pinned,
        ResponseContract $response,
        Carbon $dateTime,
        WalletChargeModel $charge
    ) {
        $user = $request->user();
        if ($user->id !== $pinned->target_user || $pinned->channel !== 'news:comment') {
            return $response->json(['message' => '无效操作'], 422);
        } elseif ($pinned->expires_at) {
            return $response->json(['message' => '已被处理'], 422);
        }

        $pinned->load(['comment']);

        // 拒绝凭据
        $charge->user_id = $pinned->user_id;
        $charge->channel = 'user';
        $charge->account = $user->id;
        $charge->action = 1;
        $charge->amount = $pinned->amount;
        $charge->subject = '被拒动态评论置顶';
        $charge->body = sprintf('被拒动态评论《%s》申请，退还申请金额', Str::limit($pinned->comment->body ?? 'null', 100, '...'));
        $charge->status = 1;

        $pinned->getConnection()->transaction(function () use ($charge, $pinned, $dateTime) {
            $charge->save();
            $pinned->user->wallet()->increment('balance', $pinned->amount);
            $pinned->expires_at = $dateTime;
            $pinned->state = 2; // 被拒绝
            $pinned->save();
        });

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

    /**
     * 取消置顶.
     *
     * @param  Request  $request
     * @param  ResponseContract  $response
     * @param  Carbon  $dateTime
     * @param  NewsModel  $news
     * @param $comment
     * @param  NewsPinnedModel  $pinned
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(
        Request $request,
        ResponseContract $response,
        Carbon $dateTime,
        NewsModel $news,
        $comment,
        NewsPinnedModel $pinned
    ) {
        unset($comment);
        $user = $request->user();
        if ($user->id !== $news->user_id) {
            return $response->json(['message' => '你没有权限操作'], 403);
        } elseif (! $pinned) {
            return $response->json(['message' => '无效操作'], 422);
        }

        $pinned->expires_at = $dateTime;

        return $pinned->save()
            ? $response->make('', 204)
            : $response->json(['message' => '操作失败'])->setStatusCode(500);
    }
}
