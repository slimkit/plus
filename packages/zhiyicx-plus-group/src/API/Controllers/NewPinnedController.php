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

namespace Zhiyi\PlusGroup\API\Controllers;

use DB;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Zhiyi\PlusGroup\Models\GroupMember;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\PlusGroup\Models\Post as PostModel;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\PlusGroup\Models\Pinned as PinnedModel;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\PlusGroup\Models\GroupMember as MemberModel;
use Zhiyi\PlusGroup\Models\GroupIncome as GroupIncomeModel;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class NewPinnedController extends Controller
{
    /**
     * 申请帖子置顶.
     *
     * @param Request     $request
     * @param PostModel   $post
     * @param PinnedModel $pinnedModel
     * @param Carbon      $datetime
     * @return mixed
     * @throws \Throwable
     * @author BS <414606094@qq.com>
     */
    public function storePost(Request $request, PostModel $post, PinnedModel $pinnedModel, Carbon $datetime)
    {
        $user = $request->user();

        $member = GroupMember::where('user_id', $user->id)
            ->where('group_id', $post->group_id)
            ->first();

        // 是否有权限进行置顶

        $bool = ! $member || in_array($member->audit, [0, 2]) || $member->disabled === 1;

        if ($bool || ($post->user_id !== $user->id && ! in_array($member->role, ['founder', 'administrator']))) {
            return response()->json(['message' => '没有权限'], 403);
        }

        if ($post->pinned()->where('user_id', $user->id)->where(function ($query) use ($datetime) {
            return $query->where('expires_at', '>', $datetime)->orwhere('expires_at', null);
        })->first()) {
            return response()->json(['message' => '已经申请过'])->setStatusCode(422);
        }
        if (! $post->group->user) {
            return response()->json(['message' => '不允许该操作'], 422);
        }

        $amount = $request->input('amount');
        $day = $request->input('day');
        if (! $user->currency || $user->currency->sum < $amount) {
            return response()->json(['message' => '积分不足'], 403);
        }

        $target_user = $post->group->founder->user;

        $this->validateBase($request, $user);

        $pinnedModel->channel = 'post';
        $pinnedModel->target = $post->id;
        $pinnedModel->user_id = $user->id;
        $pinnedModel->target_user = $target_user->id;
        $pinnedModel->amount = $amount;
        $pinnedModel->day = $day;
        $pinnedModel->status = 0;
        $pinneds = MemberModel::where('user_id', $target_user->id)
            ->where('role', 'founder')
            ->with(['posts' => function ($query) {
                return $query->whereExists(function ($query) {
                    $query->select(DB::raw('id'))
                      ->from('group_pinneds')
                      ->whereRaw('group_pinneds.target = group_posts.id')
                      ->whereRaw('group_pinneds.raw = 0')
                      ->whereNull('expires_at');
                });
            }])
            ->get()
            ->pluck('posts');
        $arrays = [];
        $pinneds->map(function ($pinned) use (&$arrays) {
            $pinned = $pinned->toArray();
            if ($pinned) {
                $arrays = array_merge($arrays, $pinned);
            }
        });
        // 1.8启用, 新版未读消息提醒
        $userCount = UserCountModel::firstOrNew([
            'type' => 'user-post-pinned',
            'user_id' => $target_user->id
        ]);

        $userCount->total = count($arrays) + 1;

        return $post->getConnection()->transaction(function () use ($user, $pinnedModel, $target_user, $amount, $post, $userCount, $member) {
            $process = new UserProcess();
            $message = '操作成功, 等待审核';
            // 管理员主动置顶帖子
            if (in_array($member->role, ['founder', 'administrator'])) {
                $message = '置顶成功';
                $dateTime = new Carbon();
                $pinnedModel->expires_at = $dateTime->addDay($pinnedModel->day);
                $pinnedModel->status = 1;
                $pinnedModel->amount && $process->prepayment($user->id, $amount, $target_user->id, '评论申请置顶', sprintf('在帖子《%s》申请评论置顶', $post->title));
                // 保存置顶请求
                $pinnedModel->save();

                return response()->json(['message' => $message], 201);
            }
            $process->prepayment($user->id, $amount, $target_user->id, '申请帖子置顶', sprintf('申请置顶帖子《%s》', $post->title));

            // 保存置顶请求
            $pinnedModel->save();

            // 给圈主发送通知
            // $target_user->sendNotifyMessage('group:pinned-post', sprintf('%s申请帖子《%s》置顶', $user->name, $post->title), [
            //     'post' => $post,
            //     'user' => $user,
            //     'pinned' => $pinnedModel,
            // ]);

            $userCount->save();

            return response()->json(['message' => $message], 201);
        });
    }

    /**
     * 接受置顶帖子.
     *
     * @param Request          $request
     * @param PostModel        $post
     * @param PinnedModel      $pinnedModel
     * @param Carbon           $datetime
     * @param GroupIncomeModel $income
     * @return mixed
     * @throws \Throwable
     * @author BS <414606094@qq.com>
     */
    public function acceptPost(Request $request, PostModel $post, PinnedModel $pinnedModel, Carbon $datetime, GroupIncomeModel $income)
    {
        $user = $request->user();
        $pinned = $pinnedModel->where('channel', 'post')->where('target', $post->id)->whereNull('expires_at')->first();
        if ($user->id != $post->group->founder->user_id || ! $pinned) {
            return response()->json(['message' => '没有权限操作'], 403);
        }

        $target_user = $post->user;

        $pinned->expires_at = $datetime->addDay($pinned->day);
        $pinned->status = 1;

        $income->group_id = $post->group->id;
        $income->subject = sprintf('置顶帖子《%s》收入', $post->title);
        $income->type = 2;
        $income->amount = $pinned->amount;
        $income->user_id = $target_user->id;

        // 1.8启用, 新版未读消息提醒
        $userUnreadCount = $target_user->unreadNotifications()
            ->count();
        $userCount = UserCountModel::firstOrNew([
            'type' => 'user-system',
            'user_id' => $target_user->id
        ]);
        $userCount->total = $userUnreadCount + 1;
        // 圈主未操作的帖子置顶审核更新
        $pinneds = MemberModel::where('user_id', $target_user->id)
            ->where('role', 'founder')
            ->with(['posts' => function ($query) {
                return $query->whereExists(function ($query) {
                    $query->select(DB::raw('id'))
                      ->from('group_pinneds')
                      ->whereRaw('group_pinneds.target = group_posts.id')
                      ->whereRaw('group_pinneds.raw = 0')
                      ->whereNull('expires_at');
                });
            }])
            ->get()
            ->pluck('posts');
        $arrays = [];
        $pinneds->map(function ($pinned) use (&$arrays) {
            $pinned = $pinned->toArray();
            if ($pinned) {
                $arrays = array_merge($arrays, $pinned);
            }
        });
        // 1.8启用, 新版未读消息提醒
        $founderCount = UserCountModel::firstOrNew([
            'type' => 'user-post-pinned',
            'user_id' => $target_user->id
        ]);

        $founderCount->total = count($arrays) ? count($arrays) - 1 : 0;

        $post->getConnection()->transaction(function () use ($pinned, $user, $target_user, $post, $income, $userCount, $founderCount) {
            $process = new UserProcess();
            $process->receivables($user->id, $pinned->amount, $target_user->id, '帖子置顶收入', sprintf('接受置顶帖子《%s》的收入', $post->title));
            // 保存置顶
            $pinned->save();
            // 保存圈子收入流水
            $income->save();
            // 给帖子作者(申请者)发送通知
            $target_user->sendNotifyMessage('group:pinned-post:accept', sprintf('申请帖子《%s》置顶已通过', $post->title), [
                'post' => $post,
                'user' => $user,
                'pinned' => $pinned,
            ]);

            $userCount->save();
            $founderCount->save();
        });

        return response()->json(['message' => '审核成功'], 201);
    }

    /**
     * 拒接置顶帖子.
     *
     * @param Request     $request
     * @param PostModel   $post
     * @param PinnedModel $pinnedModel
     * @param Carbon      $datetime
     * @return mixed
     * @throws \Throwable
     * @author BS <414606094@qq.com>
     */
    public function rejectPost(Request $request, PostModel $post, PinnedModel $pinnedModel, Carbon $datetime)
    {
        $user = $request->user();
        $pinned = $pinnedModel->where('channel', 'post')->where('target', $post->id)->whereNull('expires_at')->first();
        if ($user->id != $post->group->founder->user_id || ! $pinned) {
            return response()->json(['message' => '没有权限操作'], 403);
        }

        $target_user = $post->user;

        $pinned->expires_at = $datetime;
        $pinned->status = 2;
        // 1.8启用, 新版未读消息提醒
        $userUnreadCount = $target_user->unreadNotifications()
            ->count();
        $userCount = UserCountModel::firstOrNew([
            'type' => 'user-system',
            'user_id' => $target_user->id
        ]);
        $userCount->total = $userUnreadCount + 1;
        // 圈主未操作的帖子置顶审核更新
        $pinneds = MemberModel::where('user_id', $target_user->id)
            ->where('role', 'founder')
            ->with(['posts' => function ($query) {
                return $query->whereExists(function ($query) {
                    $query->select(DB::raw('id'))
                      ->from('group_pinneds')
                      ->whereRaw('group_pinneds.target = group_posts.id')
                      ->whereRaw('group_pinneds.raw = 0')
                      ->whereNull('expires_at');
                });
            }])
            ->get()
            ->pluck('posts');
        $arrays = [];
        $pinneds->map(function ($pinned) use (&$arrays) {
            $pinned = $pinned->toArray();
            if ($pinned) {
                $arrays = array_merge($arrays, $pinned);
            }
        });
        // 1.8启用, 新版未读消息提醒
        $founderCount = UserCountModel::firstOrNew([
            'type' => 'user-post-pinned',
            'user_id' => $target_user->id
        ]);

        $founderCount->total = count($arrays) ? count($arrays) - 1 : 0;

        $post->getConnection()->transaction(function () use ($pinned, $user, $target_user, $post, $userCount, $founderCount) {
            $process = new UserProcess();
            $process->reject($user->id, $pinned->amount, $target_user->id, '退还帖子置顶申请金额', sprintf('退还申请置顶帖子《%s》的金额', $post->title));
            // 记录置顶拒绝操作
            $pinned->save();
            // 给帖子作者(申请者)发送通知
            $target_user->sendNotifyMessage('group:pinned-post:reject', sprintf('申请帖子《%s》置顶已被拒绝', $post->title), [
                'post' => $post,
                'user' => $user,
                'pinned' => $pinned,
            ]);
            $userCount->save();
            $founderCount->save();
        });

        return response()->json(['message' => '审核成功'], 201);
    }

    /**
     * 申请评论置顶.
     *
     * @param Request $request
     * @param CommentModel $comment
     * @param PostModel $postModel
     * @param PinnedModel $pinnedModel
     * @param Carbon $datetime
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function storeComments(Request $request, CommentModel $comment, PostModel $postModel, PinnedModel $pinnedModel, Carbon $datetime)
    {
        $user = $request->user();

        if ($pinnedModel->where('channel', 'comment')->where('target', $comment->id)->where('user_id', $user->id)->where(function ($query) use ($datetime) {
            return $query->where('expires_at', '>', $datetime)->orwhere('expires_at', null);
        })->first()) {
            return response()->json(['message' => '已经申请过'])->setStatusCode(422);
        }
        if ($comment->commentable_type !== 'group-posts') {
            return response()->json(['message' => '不允许该操作'], 422);
        }

        $post = $postModel->where('id', $comment->commentable_id)->first();

        if (! $post || ! $post->user) {
            return response()->json(['message' => '不允许该操作'], 422);
        }

        $member = GroupMember::where('user_id', $user->id)
            ->where('group_id', $post->group_id)
            ->first();

        // 是否有权限进行置顶
        $bool = ! $member || in_array($member->audit, [0, 2]) || $member->disabled === 1;
        if ($bool || ($comment->user_id !== $user->id && ! in_array($member->role, ['founder', 'administrator']))) {
            return response()->json(['message' => '无权限操作'], 403);
        }

        $target_user = $post->user;

        $this->validateBase($request, $user);

        $amount = $request->input('amount');
        $day = $request->input('day');

        $pinnedModel->channel = 'comment';
        $pinnedModel->raw = $post->id;
        $pinnedModel->target = $comment->id;
        $pinnedModel->user_id = $user->id;
        $pinnedModel->target_user = $target_user->id;
        $pinnedModel->amount = $amount;
        $pinnedModel->day = $day;
        $pinnedModel->status = 0;
        // 1.8启用, 新版未读消息提醒
        // 帖子作者置顶审核更新
        $UserUnreadCount = pinnedModel::where('target_user', $target_user->id)
            ->where('channel', 'comment')
            ->whereNull('expires_at')
            ->count();
        
        // 1.8启用, 新版未读消息提醒
        $founderCount = UserCountModel::firstOrNew([
            'type' => 'user-post-comment-pinned',
            'user_id' => $target_user->id
        ]);

        $founderCount->total = $UserUnreadCount + 1;

        return $post->getConnection()->transaction(function () use ($user, $pinnedModel, $target_user, $amount, $post, $comment, $founderCount) {

            $process = new UserProcess();
            $message = '提交成功，等待审核';
            // 自己帖子下的评论置顶
            if ($target_user->id === $user->id) {
                $message = '置顶成功';
                $dateTime = new Carbon();
                $pinnedModel->expires_at = $dateTime->addDay($pinnedModel->day);
                $pinnedModel->status = 1;
                $pinnedModel->amount && $process->prepayment($user->id, $amount, $target_user->id, '评论申请置顶', sprintf('在帖子《%s》申请评论置顶', $post->title));
                // 保存置顶请求
                $pinnedModel->save();

                return response()->json(['message' => $message], 201);
            }

            $process->prepayment($user->id, $amount, $target_user->id, '评论申请置顶', sprintf('在帖子《%s》申请评论置顶', $post->title));
            // 保存置顶请求
            $pinnedModel->save();
            // 给帖子作者发送通知
            $target_user->sendNotifyMessage('group:pinned-comment', sprintf('%s申请在帖子《%s》置顶评论', $user->name, $post->title), [
                'comment' => $comment,
                'post' => $post,
                'user' => $user,
                'pinned' => $pinnedModel,
            ]);
            $founderCount->save();

            return response()->json(['message' => $message], 201);
        });
    }

    /**
     * 通过评论置顶.
     *
     * @param Request $request
     * @param CommentModel $comment
     * @param PostModel $postModel
     * @param PinnedModel $pinnedModel
     * @param Carbon $datetime
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function acceptComments(Request $request, CommentModel $comment, PostModel $postModel, PinnedModel $pinnedModel, Carbon $datetime)
    {
        $user = $request->user();
        $pinned = $pinnedModel->where('channel', 'comment')->where('target', $comment->id)->whereNull('expires_at')->first();
        $post = $postModel->where('id', $comment->commentable_id)->first();
        if ($user->id != $post->user_id || ! $pinned || ! $post) {
            return response()->json(['message' => '没有权限操作'], 403);
        }

        $target_user = $comment->user;

        $pinned->expires_at = $datetime->addDay($pinned->day);
        $pinned->status = 1;
        // 1.8启用, 新版未读消息提醒
        $userUnreadCount = $target_user->unreadNotifications()
            ->count();
        $userCount = UserCountModel::firstOrNew([
            'type' => 'user-system',
            'user_id' => $target_user->id
        ]);
        $userCount->total = $userUnreadCount + 1;
        // 帖子作者置顶审核更新
        $userUnreadCount = pinnedModel::where('target_user', $user->id)
            ->where('channel', 'comment')
            ->whereNull('expires_at')
            ->count();
        
        // 1.8启用, 新版未读消息提醒
        $founderCount = UserCountModel::firstOrNew([
            'type' => 'user-post-comment-pinned',
            'user_id' => $user->id
        ]);

        $founderCount->total = $userUnreadCount ? $userUnreadCount - 1 : 0;

        $post->getConnection()->transaction(function () use ($pinned, $user, $target_user, $comment, $post, $founderCount, $userCount) {
            $process = new UserProcess();
            $process->receivables($user->id, $pinned->amount, $target_user->id, '帖子内置顶评论收入', sprintf('帖子《%s》下置顶评论收入的金额', $post->title));

            // 保存置顶
            $pinned->save();

            // 给申请者发送通知
            $target_user->sendNotifyMessage('group:pinned-comment:accept', sprintf('帖子《%s》下的置顶评论已通过', $post->title), [
                'comment' => $comment,
                'post' => $post,
                'user' => $user,
                'pinned' => $pinned,
            ]);
            $founderCount->save();
            $userCount->save();
        });

        return response()->json(['message' => '审核成功'], 201);
    }

    /**
     * 拒绝置顶评论.
     *
     * @param Request $request
     * @param CommentModel $comment
     * @param PostModel $postModel
     * @param PinnedModel $pinnedModel
     * @param Carbon $datetime
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function rejectComments(Request $request, CommentModel $comment, PostModel $postModel, PinnedModel $pinnedModel, Carbon $datetime)
    {
        $user = $request->user();
        $pinned = $pinnedModel->where('channel', 'comment')->where('target', $comment->id)->whereNull('expires_at')->first();
        $post = $postModel->where('id', $comment->commentable_id)->first();
        if ($user->id != $post->user_id || ! $pinned || ! $post) {
            return response()->json(['message' => '没有权限操作'], 403);
        }

        $target_user = $comment->user;

        $pinned->expires_at = $datetime;
        $pinned->status = 2;
        // 1.8启用, 新版未读消息提醒
        $userUnreadCount = $target_user->unreadNotifications()
            ->count();
        $userCount = UserCountModel::firstOrNew([
            'type' => 'user-system',
            'user_id' => $target_user->id
        ]);
        $userCount->total = $userUnreadCount + 1;

        // 帖子作者置顶审核更新
        $userUnreadCount = pinnedModel::where('target_user', $user->id)
            ->where('channel', 'comment')
            ->whereNull('expires_at')
            ->count();
        
        // 1.8启用, 新版未读消息提醒
        $founderCount = UserCountModel::firstOrNew([
            'type' => 'user-post-comment-pinned',
            'user_id' => $user->id
        ]);

        $founderCount->total = $userUnreadCount ? $userUnreadCount - 1 : 0;

        $post->getConnection()->transaction(function () use ($pinned, $user, $target_user, $comment, $post, $founderCount, $userCount) {
            $process = new UserProcess();
            $process->reject($user->id, $pinned->amount, $target_user->id, '退还帖子内置顶评论申请金额', sprintf('退还帖子《%s》下置顶评论申请的金额', $post->title));

            // 保存置顶
            $pinned->save();

            // 给申请者发送通知
            $target_user->sendNotifyMessage('group:pinned-comment:reject', sprintf('帖子《%s》下的置顶评论已被拒绝', $post->title), [
                'comment' => $comment,
                'post' => $post,
                'user' => $user,
                'pinned' => $pinned,
            ]);
            $userCount->save();
            $founderCount->save();
        });

        return response()->json(['message' => '审核成功'], 201);
    }

    /**
     * 基础验证.
     *
     * @param \Illuminate\Http\Request $request
     * @param User                     $user
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    protected function validateBase(Request $request, User $user)
    {
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
            'amount.required' => '请输入申请金额',
            'amount.integer' => '参数有误',
            'amount.min' => '输入金额有误',
            'amount.max' => '余额不足',
            'day.required' => '请输入申请天数',
            'day.integer' => '天数只能为整数',
            'day.min' => '输入天数有误',
        ];

        $this->validate($request, $rules, $messages);
    }
}
