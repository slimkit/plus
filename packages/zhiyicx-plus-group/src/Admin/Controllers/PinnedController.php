<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\PlusGroup\Admin\Controllers;

use Carbon\Carbon;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\PlusGroup\Models\Post as PostModel;
use Zhiyi\PlusGroup\Models\Pinned as PinnedModel;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\PlusGroup\Models\GroupMember as MemberModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;

class PinnedController extends Controller
{
    /**
     * 申请帖子置顶.
     *
     * @param Request $request
     * @param PostModel $post
     * @param PinnedModel $pinnedModel
     * @param Carbon $datetime
     * @param WalletChargeModel $chargeModel
     * @return mixed
     * @author hh <915664508@qq.com>
     */
    public function storePost(Request $request, PostModel $post, PinnedModel $pinnedModel, Carbon $datetime)
    {
        $user = $post->user;

        if ($post->pinned()->where('user_id', $user->id)->where(function ($query) use ($datetime) {
            return $query->where('expires_at', '>', $datetime)->orwhere('expires_at', null);
        })->first()) {
            return response()->json(['message' => '已经申请过'])->setStatusCode(422);
        }

        $day = $request->input('day');

        $target_user = $post->group->user; // ?

        $this->validateBase($request, $user);

        $pinnedModel->channel = 'post';
        $pinnedModel->target = $post->id;
        $pinnedModel->user_id = $user->id;
        $pinnedModel->target_user = $target_user->id;
        $pinnedModel->amount = 0;
        $pinnedModel->day = $day;
        $pinnedModel->status = 1;
        $pinnedModel->expires_at = $datetime->addDay($day)->toDateTimeString();

        // 1.8启用, 用户未读消息
        $userCount = UserCountModel::firstOrNew([
            'type' => 'user-system',
            'user_id' => $user->id
        ]);
        $userCount->total += 1;

        $post->getConnection()->transaction(function () use ($user, $pinnedModel, $post) {
            // 保存置顶请求
            $pinnedModel->save();
            // 给用户发消息通知
            $user->sendNotifyMessage('group:pinned-post', sprintf('%s,你的帖子《%s》已被系统管理员置顶', $user->name, $post->title), ['post' => $post,
                'user' => $user,
                'pinned' => $pinnedModel,
            ]);
            $userCount->save();
        });

        $pinnedModel->expires_state = $pinnedModel->expires_at > $datetime->now()->toDateTimeString();

        return response()->json(['message' => '置顶成功', 'pinned' => $pinnedModel], 201);
    }

    /**
     * 接受置顶帖子.
     *
     * @param Request $request
     * @param PostModel $post
     * @param PinnedModel $pinnedModel
     * @param Carbon $datetime
     * @param WalletChargeModel $chargeModel
     * @return mixed
     * @author hh <915664508@qq.com>
     */
    public function acceptPost(Request $request, PostModel $post, PinnedModel $pinnedModel, Carbon $datetime, WalletChargeModel $chargeModel)
    {
        $user = $post->user;

        $founder = MemberModel::where('group_id', $post->group_id)
        ->where('role', 'founder')
        ->first();

        $pinned = $pinnedModel
        ->where('channel', 'post')
        ->where('target', $post->id)
        ->whereNull('expires_at')
        ->first();

        if (! $pinned) {
            return response()->json(['message' => '置顶不存在或被审核'], 403);
        }

        $pinned->expires_at = $datetime->addDay($pinned->day)->toDateTimeString();
        $pinned->status = 1;

        $chargeModel->user_id = $founder->user->id;
        $chargeModel->channel = 'system';
        $chargeModel->action = 1;
        $chargeModel->amount = $pinned->amount;
        $chargeModel->subject = '帖子置顶收入';
        $chargeModel->body = sprintf('接受置顶帖子《%s》的收入', $post->title);
        $chargeModel->status = 1;
        // 1.8启用, 新版未读消息数
        $userCount = UserCountModel::firstOrNew([
            'type' => 'user-system',
            'user_id' => $user->id
        ]);
        $userCount->total += 1;

        $post->getConnection()->transaction(function () use ($pinned, $user, $chargeModel, $post, $founder) {
            // 保存置顶
            $pinned->save();
            $userCount->save();
            // 保存收入记录
            $chargeModel->save();

            // 给帖子作者(申请者)发送通知
            $user->sendNotifyMessage('group:pinned-post:accept', sprintf('申请帖子《%s》置顶已通过', $post->title), [
                'post' => $post,
                'user' => $user,
                'pinned' => $pinned,
            ]);
        });

        $pinned->expires_state = $pinned->expires_at > $datetime->now()->toDateTimeString();

        return response()->json(['message' => '审核成功', 'pinned' => $pinned], 201);
    }

    /**
     * 拒接置顶帖子.
     *
     * @param Request $request
     * @param PostModel $post
     * @param PinnedModel $pinnedModel
     * @param Carbon $datetime
     * @param WalletChargeModel $chargeModel
     * @return mixed
     * @author hh <915664508@qq.com>
     */
    public function rejectPost(Request $request, PostModel $post, PinnedModel $pinnedModel, Carbon $datetime, WalletChargeModel $chargeModel)
    {
        $user = $post->user;

        $pinned = $pinnedModel->where('channel', 'post')
        ->where('target', $post->id)
        ->whereNull('expires_at')
        ->first();

        if (! $pinned) {
            return response()->json(['message' => '置顶不存在或已被审核'], 403);
        }

        $pinned->expires_at = $datetime->toDateTimeString();
        $pinned->status = 2;

        $chargeModel->user_id = $user->id;
        $chargeModel->channel = 'system';
        $chargeModel->action = 1;
        $chargeModel->amount = $pinned->amount;
        $chargeModel->subject = '退还帖子置顶申请金额';
        $chargeModel->body = sprintf('退还申请置顶帖子《%s》的金额', $post->title);
        $chargeModel->status = 1;

        // 1.8启用, 新版未读消息
        $userCount = UserCountModel::firstOrNew([
            'type' => 'user-system',
            'user_id' => $user->id
        ]);
        $userCount->total += 1;

        $post->getConnection()->transaction(function () use ($pinned, $chargeModel, $user, $post) {
            // 退还余额
            $user->wallet()->increment('balance', $pinned->amount);

            // 记录置顶拒绝操作
            $pinned->save();

            // 保存退还金额记录
            $chargeModel->save();

            // 给帖子作者(申请者)发送通知
            $user->sendNotifyMessage('group:pinned-post:reject', sprintf('申请帖子《%s》置顶已被拒绝', $post->title), [
                'post' => $post,
                'user' => $user,
                'pinned' => $pinned,
            ]);
            $userCount->save();
        });

        return response()->json(['message' => '拒绝成功', 'pinned' => $pinned], 201);
    }

    /**
     * 帖子置顶撤销.
     *
     * @param Request $request
     * @param PostModel $post
     * @param PinnedModel $pinnedModel
     * @param Carbon $datetime
     * @author hh <915664508@qq.com>
     */
    public function revocationPost(Request $request, PostModel $post, PinnedModel $pinnedModel, Carbon $datetime)
    {
        $user = $post->user;

        $pinned = $pinnedModel->where('channel', 'post')
            ->where('target', $post->id)
            ->where('expires_at', '>', $datetime)
            ->first();

        if (! $pinned) {
            return response()->json(['message' => '置顶不存在或置顶已过期'], 403);
        }

        $pinned->expires_at = $datetime->toDateTimeString();
        $pinned->save();

        $pinned->expires_state = $pinned->expires_at > $datetime->now()->toDateTimeString();

        return response()->json(['message' => '撤销成功', 'pinned' => $pinned], 201);
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
    protected function validateBase(Request $request, User $user)
    {
        $rules = [
            // 'amount' => [
            //     'required',
            //     'integer',
            //     'min:1',
            //     'max:'.$user->wallet->balance,
            // ],
            'day' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
        $messages = [
            // 'amount.required' => '请输入申请金额',
            // 'amount.integer' => '参数有误',
            // 'amount.min' => '输入金额有误',
            // 'amount.max' => '余额不足',
            'day.required' => '请输入申请天数',
            'day.integer' => '天数只能为整数',
            'day.min' => '输入天数有误',
        ];

        $this->validate($request, $rules, $messages);
    }
}
