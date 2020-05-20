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
use Illuminate\Http\Request;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News as NewsModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsPinned as NewsPinnedModel;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Http\Middleware\VerifyUserPassword;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class NewPinnedController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this
            ->middleware(VerifyUserPassword::class)
            ->only(['newsPinned', 'commentPinned']);
    }

    /**
     * 申请资讯置顶.
     *
     * @param Request $request
     * @param NewsModel $news
     * @param Carbon $dateTime
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function newsPinned(Request $request, NewsModel $news, Carbon $dateTime)
    {
        $user = $request->user();

        if ($user->id !== $news->user_id) {
            return response()->json(['message' => '没有权限申请'], 403);
        }

        if ($news
            ->pinned()
            ->where('state', 1)
            ->where('expires_at', '>', $dateTime)
            ->count()
        ) {
            return response()->json(['message' => '已经申请过'], 422);
        }

        if ($news
            ->pinned()
            ->where('state', 0)
            ->count()
        ) {
            return response()->json(['message' => '已申请过置顶,请等待审核'], 422);
        }

        $pinned = new NewsPinnedModel();
        $pinned->user_id = $user->id;
        $pinned->target = $news->id;
        $pinned->channel = 'news';
        $pinned->target_user = 0;
        $pinned->state = 0;
        $pinned->amount = $request->input('amount');

        return app()->call([$this, 'PinnedValidate'], [
            'pinned' => $pinned,
            'call' => function (NewsPinnedModel $pinned) use ($user, $news) {
                $process = new UserProcess();
                $message = '提交成功,等待审核';
                // if ($pinned->amount) {
                $order = $process->prepayment($user->id, $pinned->amount, 0, '申请资讯置顶', sprintf('申请资讯《%s》置顶', $news->title));
                // if ($news->user_id === $user->id) {
                //     $dateTime = new Carbon();
                //     $pinned->expires_at = $dateTime->addDay($pinned->day);
                //     $message = '置顶成功';
                // }
                // }

                if ($order) {
                    $pinned->save();

                    return response()->json(['message' => $message], 201);
                }

                return response()->json(['message' => '操作失败'], 500);
            },
        ]);
    }

    /**
     * 申请资讯评论置顶.
     *
     * @param Request $request
     * @param NewsModel $news
     * @param CommentModel $comment
     * @param Carbon $dateTime
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function commentPinned(Request $request, NewsModel $news, CommentModel $comment, Carbon $dateTime)
    {
        $user = $request->user();

        if ($user->id !== $comment->user_id) {
            return response()->json(['message' => '没有权限申请'], 403);
        }

        if ($news
            ->pinnedComments()
            ->newPivotStatementForId($comment->id)
            ->where('user_id', $user->id)
            ->where('channel', 'news:comment')
            ->where('state', 1)
            ->where('expires_at', '>', $dateTime)
            ->count()
        ) {
            return response()->json(['message' => '已申请过置顶,请等待审核'], 422);
        }

        if ($news
            ->pinnedComments()
            ->newPivotStatementForId($comment->id)
            ->where('user_id', $user->id)
            ->where('channel', 'news:comment')
            ->where('state', 0)
            ->count()
        ) {
            return response()->json(['message' => '已经申请过,请等待审核'], 422);
        }

        $pinned = new NewsPinnedModel();
        $pinned->user_id = $user->id;
        $pinned->raw = $comment->id;
        $pinned->target = $news->id;
        $pinned->channel = 'news:comment';
        $pinned->target_user = $news->user_id ?? 0;
        $pinned->state = 0;
        $pinned->amount = $request->input('amount');

        return app()->call([$this, 'PinnedValidate'], [
            'pinned' => $pinned,
            'call' => function (NewsPinnedModel $pinned) use ($user, $comment, $news) {
                $process = new UserProcess();
                $message = '提交成功,等待审核';
                $order = false;

                if ($news->user_id === $user->id) {
                    $dateTime = new Carbon();
                    $pinned->expires_at = $dateTime->addDay($pinned->day);
                    $pinned->state = 1;
                    $message = '置顶成功';
                    $order = ! $pinned->amount
                                ? true
                                : $process->prepayment($user->id, $pinned->amount, $news->user_id, '申请资讯评论置顶', sprintf('申请评论《%s》置顶', $comment->body));
                } else {
                    $order = $process->prepayment($user->id, $pinned->amount, $news->user_id, '申请资讯评论置顶', sprintf('申请评论《%s》置顶', $comment->body));
                }
                if ($order) {
                    $pinned->save();
                    if ($news->user && $news->user->id !== $user->id) {
                        // 查询资讯用户未审核的资讯评论数量
                        // 增加资讯评论置顶申请未读数
                        $unreadPinned = $pinned->newQuery()
                            ->where('target_user', $news->user_id)
                            ->where('channel', 'news:comment')
                            ->whereNull('expires_at')
                            ->count();

                        $userCount = UserCountModel::firstOrNew([
                            'type' => 'user-news-comment-pinned',
                            'user_id' => $news->user->id,
                        ]);

                        $userCount->total = $unreadPinned;
                        $userCount->save();
                    }

                    return response()->json(['message' => $message], 201);
                }

                return response()->json(['message' => '操作失败'], 500);
            },
        ]);
    }

    /**
     * check Request.
     *
     * @param Request $request
     * @param NewsPinnedModel $pinned
     * @param callable $call
     * @author BS <414606094@qq.com>
     */
    public function PinnedValidate(Request $request, NewsPinnedModel $pinned, callable $call)
    {
        $user = $request->user();
        $currency = $user->currency()->firstOrCreate(['type' => 1], ['sum' => 0]);

        $rules = [
            'amount' => [
                'required',
                'integer',
                'min:0',
                'max:'.$currency->sum,
            ],
            'day' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
        $messages = [
            'amount.required' => '请输入金额',
            'amount.integer' => '参数有误',
            'amount.min' => '输入金额有误',
            'amount.max' => '余额不足',
            'day.required' => '请输入申请天数',
            'day.integer' => '天数只能为整数',
            'day.min' => '天数最少为1天',
        ];
        $this->validate($request, $rules, $messages);

        $pinned->amount = intval($request->input('amount'));
        $pinned->day = intval($request->input('day'));

        return app()->call($call, [
            'pinned' => $pinned,
        ]);
    }
}
