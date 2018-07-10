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
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned as FeedPinnedModel;

class PinnedController extends Controller
{
    /**
     * App.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create the controller instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(ApplicationContract $app)
    {
        $this->app = $app;
    }

    /**
     * 申请评论置顶.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @param \Zhiyi\Plus\Models\Comment $comment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function commentPinned(Request $request, ResponseContract $response, FeedModel $feed, CommentModel $comment, Carbon $datetime)
    {
        $user = $request->user();
        if ($comment->user_id !== $user->id) {
            return $response->json(['message' => '你没有权限申请'])->setStatusCode(403);
        } elseif ($feed->pinnedComments()->newPivotStatementForId($comment->id)->where(function ($query) use ($datetime) {
            return $query->where('expires_at', '>', $datetime)->orwhere('expires_at', null);
        })->first()) {
            return $response->json(['message' => '已申请过置顶, 请等待审核'])->setStatusCode(422);
        }

        $pinned = new FeedPinnedModel();
        $pinned->user_id = $user->id;
        $pinned->channel = 'comment';
        $pinned->target = $comment->id;
        $pinned->raw = $feed->id;
        $pinned->target_user = $feed->user_id;

        return $this->app->call([$this, 'validateBase'], [
            'pinned' => $pinned,
            'call' => function (WalletChargeModel $charge, FeedPinnedModel $pinned) use ($user, $comment, $feed) {
                $charge->user_id = $user->id;
                $charge->channel = 'user';
                $charge->account = $user->id;
                $charge->action = 0;
                $charge->amount = $pinned->amount;
                $charge->subject = '申请动态评论置顶';
                $charge->body = sprintf('申请评论《%s》置顶', $comment->body);
                $charge->status = 1;

                return $this->app->call([$this, 'save'], [
                    'charge' => $charge,
                    'pinned' => $pinned,
                    'feed' => $feed,
                    'call' => $feed->user ? function () use ($user, $comment, $feed, $pinned) {
                        // $message = sprintf('%s 在你发布的动态中申请评论置顶', $user->name);
                        // 增加动态评论置顶申请未读数
                        $userUnReadCount = $pinned->newQuery()
                            ->where('target_user', $feed->user_id)
                            ->where('channel', 'comment')
                            ->whereNull('expires_at')
                            ->count();

                        $userCount = UserCountModel::firstOrNew([
                            'user_id' => $feed->user->id,
                            'type' => 'user-feed-comment-pinned',
                        ]);

                        $userCount->total = $userUnReadCount;
                        $userCount->save();

                        // $feed->user->sendNotifyMessage('feed:pinned-comment', $message, [
                        //     'feed' => $feed,
                        //     'user' => $user,
                        //     'comment' => $comment,
                        //     'pinned' => $pinned,
                        // ]);
                    } : null,
                ]);
            },
        ]);
    }

    /**
     * 申请动态置顶.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function feedPinned(Request $request, ResponseContract $response, FeedModel $feed, Carbon $datetime)
    {
        $user = $request->user();

        if ($feed->user_id !== $user->id) {
            return $response->json(['message' => '你没有权限申请'])->setStatusCode(403);
        } elseif ($feed->pinned()->where('user_id', $user->id)->where(function ($query) use ($datetime) {
            return $query->where('expires_at', '>', $datetime)->orwhere('expires_at', null);
        })->first()) {
            return $response->json(['message' => '已经申请过动态置顶, 请等待审核'])->setStatusCode(422);
        }

        $pinned = new FeedPinnedModel();
        $pinned->user_id = $user->id;
        $pinned->channel = 'feed';
        $pinned->target = $feed->id;

        return $this->app->call([$this, 'validateBase'], [
            'pinned' => $pinned,
            'call' => function (WalletChargeModel $charge, FeedPinnedModel $pinned) use ($user, $feed) {
                $charge->user_id = $user->id;
                $charge->channel = 'user';
                $charge->account = $user->id;
                $charge->action = 0;
                $charge->amount = $pinned->amount;
                $charge->subject = '动态申请置顶';
                $charge->body = sprintf('申请置顶动态《%s》', str_limit($feed->feed_content, 100));
                $charge->status = 1;

                return $this->app->call([$this, 'save'], [
                    'charge' => $charge,
                    'pinned' => $pinned,
                ]);
            },
        ]);
    }

    /**
     * 保存所有数据库记录.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\WalletCharge $charge
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned $pinned
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function save(
        Request $request,
        ResponseContract $response,
        WalletChargeModel $charge,
        FeedPinnedModel $pinned,
        FeedModel $feed,
        callable $call = null
    ) {
        $user = $request->user();
        $user->getConnection()->transaction(function () use ($user, $charge, $pinned, $feed) {
            if ($feed->user_id === $user->id) {
                $dateTime = new Carbon();
                $pinned->expires_at = $dateTime->addDay($pinned->day);
                $pinned->save();

                return $response->json(['message' => '置顶成功'], 201);
            }
            $user->wallet()->decrement('balance', $charge->amount);
            $user->walletCharges()->save($charge);
            $pinned->save();
        });

        if ($call !== null) {
            call_user_func($call);
        }

        return $response->json(['message' => '提交成功, 等待审核'])->setStatusCode(201);
    }

    /**
     * 基础验证.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned $pinned
     * @param callable $call
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function validateBase(Request $request, FeedPinnedModel $pinned, callable $call)
    {
        $user = $request->user();
        $rules = [
            'amount' => [
                'required',
                'integer',
                'min:0',
                'max:'.$user->wallet->balance,
            ],
            'day' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
        $messages = [
            'amount.required' => '请输入申请金额',
            'amount.integer' => '参数有误',
            'amount.min' => '输入金额有误',
            'amount.max' => '余额不足',
            'day.required' => '请输入申请天数',
            'day.integer' => '天数只能为整数',
            'day.min' => '输入天数有误',
        ];
        $this->validate($request, $rules, $messages);

        $pinned->amount = intval($request->input('amount'));
        $pinned->day = intval($request->input('day'));

        return $this->app->call($call, [
            'pinned' => $pinned,
        ]);
    }
}
