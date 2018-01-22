<?php

namespace Zhiyi\PlusGroup\API\Controllers;

use Carbon\Carbon;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\PlusGroup\Models\GroupMember;
use Zhiyi\PlusGroup\Models\Post as PostModel;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\PlusGroup\Models\Pinned as PinnedModel;
use Zhiyi\PlusGroup\Models\GroupMember as MemberModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\PlusGroup\Models\GroupIncome as GroupIncomeModel;

class PinnedController extends Controller
{
    /**
     * 帖子申请置顶列表
     *
     * @param Request $request 
     * @param PinnedModel $pinnedModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function posts(Request $request, PinnedModel $pinnedModel)
    {
        $user = $request->user();
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);
        $group = $request->query('group');
        $pinneds = $pinnedModel->limit($limit)
        ->where('target_user', $user->id)
        ->where('channel', 'post')
        ->whereExists(function ($query) use ($group) {
            return $query->from('group_posts')->whereRaw('group_pinneds.target = group_posts.id')->when($group, function ($query) use ($group) {
                return $query->where('group_id', $group);
            });
        })
        ->when($after, function ($query) use ($after) {
            return $query->where('id', '<', $after);
        })
        ->orderBy('id', 'desc')
        ->get();

        $pinneds->load(['user', 'post']);

        return response()->json($pinneds, 200);
    }

    /**
     * 申请帖子置顶.
     *
     * @param Request $request
     * @param PostModel $post
     * @param PinnedModel $pinnedModel
     * @param Carbon $datetime
     * @param WalletChargeModel $chargeModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function storePost(Request $request, PostModel $post, PinnedModel $pinnedModel, Carbon $datetime, WalletChargeModel $chargeModel)
    {
        $user = $request->user();

        $member = GroupMember::where('user_id', $user->id)
            ->where('group_id', $post->group_id)
            ->first();

        // 是否有权限进行置顶
        $bool = ! $member || in_array($member->audit, [0, 2]) || $member->disabled === 1;

        if (! $member || ($post->user_id !== $user->id && ! in_array($member->role, ['founder', 'administrator']))) {
            return response()->json(['message' => '无权限操作'], 403);
        }
        
        if ($post->pinned()->where('user_id', $user->id)->where(function ($query) use ($datetime) {
            return $query->where('expires_at', '>', $datetime)->orwhere('expires_at', null);
        })->first()) {
            return response()->json(['message' => ['已经申请过']])->setStatusCode(422);
        }
        if (! $post->group->user) {
            return response()->json(['message' => ['不允许该操作']], 422);
        }

        $target_user = $post->group->founder->user;

        $this->validateBase($request, $user);

        $amount = $request->input('amount');
        $day = $request->input('day');

        $pinnedModel->channel = 'post';
        $pinnedModel->target = $post->id;
        $pinnedModel->user_id = $user->id;
        $pinnedModel->target_user = $target_user->id;
        $pinnedModel->amount = $amount;
        $pinnedModel->day = $day;
        $pinnedModel->status = 0;

        $chargeModel->user_id = $user->id;
        $chargeModel->channel = 'user';
        $chargeModel->account = $target_user->id;
        $chargeModel->action = 0;
        $chargeModel->amount = $amount;
        $chargeModel->subject = '帖子申请置顶';
        $chargeModel->body = sprintf('申请置顶帖子《%s》', $post->title);
        $chargeModel->status = 1;

        $post->getConnection()->transaction(function () use ($chargeModel, $user, $pinnedModel, $target_user, $amount, $post) {
            
            // 扣除余额
            $user->wallet()->decrement('balance', $amount);

            // 保存置顶请求
            $pinnedModel->save();

            // 保存扣费记录
            $chargeModel->save();

            // 给圈主发送通知
            $target_user->sendNotifyMessage('group:pinned-post', sprintf('%s申请帖子《%s》置顶', $user->name, $post->title), [
                'post' => $post,
                'user' => $user,
                'pinned' => $pinnedModel,
            ]);
        });

        return response()->json(['message' => ['申请成功']], 201);
    }

    /**
     * 接受置顶帖子
     *
     * @param Request $request
     * @param PostModel $post
     * @param PinnedModel $pinnedModel
     * @param Carbon $datetime
     * @param WalletChargeModel $chargeModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function acceptPost(Request $request, PostModel $post, PinnedModel $pinnedModel, Carbon $datetime, WalletChargeModel $chargeModel, GroupIncomeModel $income)
    {
        $user = $request->user();
        $pinned = $pinnedModel->where('channel', 'post')->where('target', $post->id)->whereNull('expires_at')->first();
        if ($user->id != $post->group->founder->user_id || ! $pinned) {
            return response()->json(['message' => ['没有权限操作']], 403);
        }

        $target_user = $post->user;

        $pinned->expires_at = $datetime->addDay($pinned->day);
        $pinned->status = 1;

        $chargeModel->user_id = $user->id;
        $chargeModel->channel = 'user';
        $chargeModel->account = $target_user->id;
        $chargeModel->action = 1;
        $chargeModel->amount = $pinned->amount;
        $chargeModel->subject = '帖子置顶收入';
        $chargeModel->body = sprintf('接受置顶帖子《%s》的收入', $post->title);
        $chargeModel->status = 1;

        $income->group_id = $post->group->id;
        $income->subject = sprintf("置顶帖子《%s》收入", $post->title);
        $income->type = 2;
        $income->amount = $pinned->amount;
        $income->user_id = $target_user->id;

        $post->getConnection()->transaction(function () use ($pinned, $user, $chargeModel, $target_user, $post, $income) {
            // 增加余额
            $user->wallet()->increment('balance', $pinned->amount);

            // 保存置顶
            $pinned->save();

            // 保存收入记录
            $chargeModel->save();

            // 保存圈子收入流水
            $income->save();

            // 给帖子作者(申请者)发送通知
            $target_user->sendNotifyMessage('group:pinned-post:accept', sprintf('申请帖子《%s》置顶已通过', $post->title), [
                'post' => $post,
                'user' => $user,
                'pinned' => $pinned,
            ]);

        });

        return response()->json(['message' => '审核成功'], 201);
    }

    /**
     * 拒接置顶帖子
     *
     * @param Request $request
     * @param PostModel $post
     * @param PinnedModel $pinnedModel
     * @param Carbon $datetime
     * @param WalletChargeModel $chargeModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function rejectPost(Request $request, PostModel $post, PinnedModel $pinnedModel, Carbon $datetime, WalletChargeModel $chargeModel)
    {
        $user = $request->user();
        $pinned = $pinnedModel->where('channel', 'post')->where('target', $post->id)->whereNull('expires_at')->first();
        if ($user->id != $post->group->founder->user_id || ! $pinned) {
            return response()->json(['message' => ['没有权限操作']], 403);
        }

        $target_user = $post->user;

        $pinned->expires_at = $datetime;
        $pinned->status = 2;

        $chargeModel->user_id = $target_user->id;
        $chargeModel->channel = 'user';
        $chargeModel->account = $user->id;
        $chargeModel->action = 1;
        $chargeModel->amount = $pinned->amount;
        $chargeModel->subject = '退还帖子置顶申请金额';
        $chargeModel->body = sprintf('退还申请置顶帖子《%s》的金额', $post->title);
        $chargeModel->status = 1;

        $post->getConnection()->transaction(function () use ($pinned, $user, $chargeModel, $target_user, $post) {
            // 退还余额
            $target_user->wallet()->increment('balance', $pinned->amount);

            // 记录置顶拒绝操作
            $pinned->save();

            // 保存退还金额记录
            $chargeModel->save();

            // 给帖子作者(申请者)发送通知
            $target_user->sendNotifyMessage('group:pinned-post:reject', sprintf('申请帖子《%s》置顶已被拒绝', $post->title), [
                'post' => $post,
                'user' => $user,
                'pinned' => $pinned,
            ]);
        });

        return response()->json(['message' => ['审核成功']], 201);
    }

    /**
     * 帖子评论置顶列表.
     *
     * @param Request $request
     * @param PinnedModel $pinnedModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function comments(Request $request, PinnedModel $pinnedModel)
    {
        $user = $request->user();
        $limit = $request->query('limit', 15);
        $after = $request->query('after', 0);
        $post = $request->query('post');
        $pinneds = $pinnedModel
        ->where('target_user', $user->id)
        ->where('channel', 'comment')
        ->when($after, function ($query) use ($after) {
            return $query->where('id', '<', $after);
        })
        ->when($post, function ($query) use ($post) {
            return $query->where('raw', $post);
        })
        ->limit($limit)
        ->orderBy('id', 'desc')
        ->get();

        $pinneds->load(['comment', 'user', 'post']);

        return response()->json($pinneds, 200);
    }

    /**
     * 申请评论置顶.
     *
     * @param Request $request
     * @param CommentModel $comment
     * @param PostModel $postModel
     * @param PinnedModel $pinnedModel
     * @param Carbon $datetime
     * @param WalletChargeModel $chargeModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function storeComments(Request $request, CommentModel $comment, PostModel $postModel, PinnedModel $pinnedModel, Carbon $datetime, WalletChargeModel $chargeModel)
    {
        $user = $request->user();

       if ($pinnedModel->where('channel', 'comment')->where('target', $comment->id)->where('user_id', $user->id)->where(function ($query) use ($datetime) {
           return $query->where('expires_at', '>', $datetime)->orwhere('expires_at', null);
       })->first()) {
           return response()->json(['message' => ['已经申请过']])->setStatusCode(422);
       }
       if ($comment->commentable_type !== 'group-posts') {
           return response()->json(['message' => ['不允许该操作']], 422);
       }

        $post = $postModel->where('id', $comment->commentable_id)->first();

        if (! $post || ! $post->user) {
            return response()->json(['message' => ['不允许该操作']], 422);
        }

        $member = GroupMember::where('user_id', $user->id)
            ->where('group_id', $post->group_id)
            ->first();

        // 是否有权限进行置顶
        if (! $member || ($comment->user_id !== $user->id && ! in_array($member->role, ['founder', 'administrator']))) {
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

        $chargeModel->user_id = $user->id;
        $chargeModel->channel = 'user';
        $chargeModel->account = $target_user->id;
        $chargeModel->action = 0;
        $chargeModel->amount = $amount;
        $chargeModel->subject = '评论申请置顶';
        $chargeModel->body = sprintf('在帖子《%s》申请评论置顶', $post->title);
        $chargeModel->status = 1;

        $post->getConnection()->transaction(function () use ($chargeModel, $user, $pinnedModel, $target_user, $amount, $post, $comment) {
            
            // 扣除余额
            $user->wallet()->decrement('balance', $amount);

            // 保存置顶请求
            $pinnedModel->save();

            // 保存扣费记录
            $chargeModel->save();

            // 给帖子作者发送通知
            $target_user->sendNotifyMessage('group:pinned-comment', sprintf('%s申请在帖子《%s》置顶评论', $user->name, $post->title), [
                'comment' => $comment,
                'post' => $post,
                'user' => $user,
                'pinned' => $pinnedModel,
            ]);
        });

        return response()->json(['message' => ['申请成功']], 201);
    }

    /**
     * 通过评论置顶
     *
     * @param Request $request
     * @param CommentModel $comment
     * @param PostModel $postModel
     * @param PinnedModel $pinnedModel
     * @param Carbon $datetime
     * @param WalletChargeModel $chargeModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function acceptComments(Request $request, CommentModel $comment, PostModel $postModel, PinnedModel $pinnedModel, Carbon $datetime, WalletChargeModel $chargeModel)
    {
        $user = $request->user();
        $pinned = $pinnedModel->where('channel', 'comment')->where('target', $comment->id)->whereNull('expires_at')->first();
        $post = $postModel->where('id', $comment->commentable_id)->first();
        if ($user->id != $post->user_id || ! $pinned || ! $post) {
            return response()->json(['message' => ['没有权限操作']], 403);
        }

        $target_user = $comment->user;

        $pinned->expires_at = $datetime->addDay($pinned->day);
        $pinned->status = 1;

        $chargeModel->user_id = $user->id;
        $chargeModel->channel = 'user';
        $chargeModel->account = $target_user->id;
        $chargeModel->action = 1;
        $chargeModel->amount = $pinned->amount;
        $chargeModel->subject = '帖子内置顶评论收入';
        $chargeModel->body = sprintf('帖子《%s》下置顶评论收入的金额', $post->title);
        $chargeModel->status = 1;

        $post->getConnection()->transaction(function () use ($pinned, $user, $chargeModel, $target_user, $comment, $post) {
            // 增加余额
            $user->wallet()->increment('balance', $pinned->amount);

            // 保存置顶
            $pinned->save();

            // 保存收入记录
            $chargeModel->save();

            // 给申请者发送通知
            $target_user->sendNotifyMessage('group:pinned-comment:accept', sprintf('帖子《%s》下的置顶评论已通过', $post->title), [
                'comment' => $comment,
                'post' => $post,
                'user' => $user,
                'pinned' => $pinned,
            ]);
        });

        return response()->json(['message' => ['审核成功']], 201);
    }

    /**
     * 拒绝置顶评论
     *
     * @param Request $request
     * @param CommentModel $comment
     * @param PostModel $postModel
     * @param PinnedModel $pinnedModel
     * @param Carbon $datetime
     * @param WalletChargeModel $chargeModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function rejectComments(Request $request, CommentModel $comment, PostModel $postModel, PinnedModel $pinnedModel, Carbon $datetime, WalletChargeModel $chargeModel)
    {
        $user = $request->user();
        $pinned = $pinnedModel->where('channel', 'comment')->where('target', $comment->id)->whereNull('expires_at')->first();
        $post = $postModel->where('id', $comment->commentable_id)->first();
        if ($user->id != $post->user_id || ! $pinned || ! $post) {
            return response()->json(['message' => ['没有权限操作']], 403);
        }

        $target_user = $comment->user;

        $pinned->expires_at = $datetime;
        $pinned->status = 2;

        $chargeModel->user_id = $target_user->id;
        $chargeModel->channel = 'user';
        $chargeModel->account = $user->id;
        $chargeModel->action = 1;
        $chargeModel->amount = $pinned->amount;
        $chargeModel->subject = '退还帖子内置顶评论申请金额';
        $chargeModel->body = sprintf('退还帖子《%s》下置顶评论申请的金额', $post->title);
        $chargeModel->status = 1;

        $post->getConnection()->transaction(function () use ($pinned, $user, $chargeModel, $target_user, $comment, $post) {
            // 退还金额
            $target_user->wallet()->increment('balance', $pinned->amount);

            // 保存置顶
            $pinned->save();

            // 保存收入记录
            $chargeModel->save();

            // 给申请者发送通知
            $target_user->sendNotifyMessage('group:pinned-comment:reject', sprintf('帖子《%s》下的置顶评论已被拒绝', $post->title), [
                'comment' => $comment,
                'post' => $post,
                'user' => $user,
                'pinned' => $pinned,
            ]);
        });

        return response()->json(['message' => ['审核成功']], 201);
    }

    /**
     * 基础验证.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned $pinned
     * @param callable $call
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    protected function validateBase(Request $request, User $user)
    {
        $rules = [
            'amount' => [
                'required',
                'integer',
                'min:1',
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
    }

    public function postPinnedCreate(Request $request, PostModel $post, PinnedModel $pinnedModel, Carbon $datetime)
    {
        $user = $request->user();
        
        $count = MemberModel::whereIn('role', ['founder', 'administrator'])
        ->where('user_id', $user->id)
        ->where('disabled', 0)
        ->count();

        if (! $count) {
            return response()->json(['message' => '无权限操作'], 403);
        }

        $this->validate($request, [
            'day' => 'required|integer|min:1',
        ], [
            'day.required' => '置顶天数不能为空',
            'day.integer' => '置顶天数格式错误',
            'day.min' => '置顶天数错误',
        ]);

        if ($post->pinned()->where('user_id', $user->id)->where(function ($query) use ($datetime) {
            return $query->where('expires_at', '>', $datetime)->orwhere('expires_at', null);
        })->first()) {
            return response()->json(['message' => '已经申请过'])->setStatusCode(422);
        }

        $target_user = $post->group->founder->user;

        $pinnedModel->channel = 'post';
        $pinnedModel->target = $post->id;
        $pinnedModel->user_id = $post->user_id;
        $pinnedModel->target_user = $target_user->id;
        $pinnedModel->amount = 0;
        $pinnedModel->day = $request->input('day');
        $pinnedModel->status = 1;
        $pinnedModel->expires_at = $datetime->addDay($request->input('day'))->toDateTimeString();

        $post->getConnection()->transaction(function () use ($user, $pinnedModel, $post) {
            // 保存置顶请求
            $pinnedModel->save();

            // 给用户发消息通知
            if ($user->id !== $post->user_id) {
                $post->user->sendNotifyMessage(
                    'group:pinned-post', 
                    sprintf('%s,你的帖子《%s》已被系统管理员置顶', $post->user->name, $post->title), 
                    ['post' => $post, 'user' => $user, 'pinned' => $pinnedModel]
                );
            }
        });

        return response()->json(['message' => '置顶成功', 'pinned' => $pinnedModel], 201);
    }

    public function postPinnedCancel(Request $request, PostModel $post, PinnedModel $pinnedModel, Carbon $datetime)
    {
        $user = $request->user();

        $member = MemberModel::where('user_id', $user->id)
        ->where('group_id', $post->group_id)
        ->where('disabled', 0)
        ->first();

        if (! $member || ($post->user_id !== $user->id && ! in_array($member->role, ['founder', 'administrator']))) {
            return response()->json(['message' => '无权限操作'], 403);
        }

        $pinned = $pinnedModel->where('channel', 'post')
            ->where('target', $post->id)
            ->where('expires_at', '>', $datetime)
            ->first();

        if (! $pinned) {
            return response()->json(['message' => '置顶不存在或置顶已过期'], 403);
        }

        $pinned->expires_at = $datetime->toDateTimeString();
        $pinned->save();
        
        return response()->json(['message' => '取消置顶成功', 'pinned' => $pinned], 201);
    }
}
