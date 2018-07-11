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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\AdminControllers;

use DB;
use Carbon\Carbon;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\UserCount;
use Illuminate\Database\QueryException;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Traits\PaginatorPage;

class CommentController extends Controller
{
    use PaginatorPage;

    /**
     * 获取评论列表.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, Comment $commentModel, Carbon $datetime, FeedPinned $feedPinned, User $user)
    {
        $limit = (int) $request->query('limit', 20);
        $feed = $request->query('feed');
        $id = $request->query('id');
        $user_id = $request->query('user_id');
        $target_user = $request->query('target_user');
        $keyword = $request->query('keyword');
        $top = $request->query('top');
        $stime = $request->query('stime');
        $etime = $request->query('etime');
        $pinned_stime = $request->query('pinned_stime', '');
        $pinned_etime = $request->query('pinned_etime', '');
        $type = $request->query('type', 'all');
        $pinned_type = $request->query('pinned_type', 'all');
        $userName = $request->query('userName');

        $users = [];

        if ($userName) {
            $users = $user->where('name', 'like', "%{$userName}%")
                ->get()
                ->pluck('id')
                ->toArray();
        }

        if ($user_id) {
            array_push($users, $user_id);
            $users = array_unique($users);
        }

        // 根据时间快捷筛选
        switch ($type) {
            case 'today':
                $stime = $datetime->yesterday();
                break;
            case 'yesterday':
                $stime = $datetime->yesterday();
                $etime = $datetime->today();
                break;
            case 'week':
                $stime = $datetime->now()->subDays(7);
                $etime = $datetime->now();
                break;
            case 'lastDay':
                $etime = $datetime->today();
                break;
            default:

                break;
        }

        switch ($pinned_type) {
            case 'yes':
                $pinned_etime = '';
                $pinned_stime = $datetime->now();
                break;
            case 'no':
                $pinned_etime = $datetime->now();
                $pinned_stime = '';
                break;
            default:
                // code...
                break;
        }

        $commentModel = $commentModel->with(['user'])
            ->with(['target', 'reply', 'user'])
            ->where('commentable_type', '=', 'feeds')
            ->when($feed, function ($query) use ($feed) { // 根据资源（动态）id查询
                return $query->where('commentable_id', $feed);
            })
            ->when($id, function ($query) use ($id) { // 根据评论id查询
                return $query->where('id', $id);
            })
            ->when($users, function ($query) use ($users) {  // 根据发布者id查询
                return $query->whereIn('user_id', $users);
            })
            ->when($target_user, function ($query) use ($target_user) {  // 根据资源（动态）作者id查询
                return $query->where('target_user', $target_user);
            })
            ->when($keyword, function ($query) use ($keyword) {  // 根据内容关键字查询
                return $query->where('body', 'like', '%'.$keyword.'%');
            })
            ->when($stime, function ($query) use ($stime) {  // 根据起始时间筛选
                return $query->whereDate('created_at', '>=', $stime);
            })
            ->when($etime, function ($query) use ($etime) {  // 根据截至时间筛选
                return $query->whereDate('created_at', '<=', $etime);
            })
            ->when(($top && $top !== 'all' && ! $pinned_etime && ! $pinned_stime), function ($query) use ($pinned_type, $top, $datetime) {
                $method = $top == 'yes' ? 'whereExists' : 'whereNotExists';

                return $query->{$method}(function ($query) use ($datetime, $pinned_type) {
                    if ($pinned_type !== 'all') {
                        return $query
                            ->from('feed_pinneds')
                            ->whereRaw('feed_pinneds.target = comments.id')
                            ->where('channel', 'comment')
                            ->whereDate('expires_at', $pinned_type === 'yes' ? '<=' : '>=', $datetime->now());
                    } else {
                        return $query
                            ->from('feed_pinneds')
                            ->whereRaw('feed_pinneds.target = comments.id')
                            ->where('channel', 'comment');
                    }
                });
            })
            ->when($pinned_stime, function ($query) use ($pinned_stime) {
                return $query->whereExists(function ($query) use ($pinned_stime) {
                    return $query->from('feed_pinneds')->whereRaw('feed_pinneds.target = comments.id')->where('channel', 'comment')->whereDate('expires_at', '>=', $pinned_stime);
                });
            })
            ->when($pinned_etime, function ($query) use ($pinned_etime) {
                return $query->whereExists(function ($query) use ($pinned_etime) {
                    return $query->from('feed_pinneds')->whereRaw('feed_pinneds.target = comments.id')->where('channel', 'comment')->whereDate('expires_at', '<=', $pinned_etime);
                });
            })
            ->orderBy('id', 'desc');

        $paginator = $commentModel->paginate($limit);

        $comment_ids = $paginator->pluck('id');

        $pinneds = $feedPinned->where('channel', 'comment')
            ->with(['user'])
            ->whereIn('target', $comment_ids)
            ->get();

        $data = [
            'comments' => $paginator->getCollection()->toArray(),
            'pinneds' => $pinneds,
            'pervPage' => $this->getPrevPage($paginator),
            'nextPage' => $this->getNextPage($paginator),
            'lastPage' => $paginator->lastPage(),
            'current_page' => $paginator->currentPage(),
            'total' => $paginator->count(),
        ];

        return response()->json($data)->setStatusCode(200);
    }

    /**
     * Delete comment.
     *
     * @param Comment $comment
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function delete(Comment $comment)
    {
        DB::beginTransaction();
        try {
            $pinnedComment = FeedPinned::whereNull('expires_at')
                ->where('target', $comment->id)
                ->where('channel', 'comment')
                ->first();
            if ($pinnedComment) {
                $process = new UserProcess();
                $process->reject(0, $pinnedComment->amount, $pinnedComment->user_id, '评论申请置顶退款', '退还在动态申请置顶的评论的款项');
                $pinnedComment->delete();
            }
            // 统计被评论用户未操作的动态评论置顶
            $unReadCount = FeedPinned::whereNull('expires_at')
                ->where('channel', 'comment')
                ->where('target_user', $comment->target_user)
                ->count();
            $userCount = UserCount::firstOrNew([
                'type' => 'user-feed-comment-pinned',
                'user_id' => $comment->target_user,
            ]);

            $userCount->total = $unReadCount;
            $userCount->save();

            $feed = new Feed();
            $feed->where('id', $comment->commentable_id)->decrement('feed_comment_count'); // 统计相关动态评论数量

            // TODO 用户评论统计，积分减少

            $comment->delete();
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->formatMessage(),
            ])->setStatusCode(500);
        }
        DB::commit();

        return response(null, 204);
    }

    /**
     * 同意评论置顶申请.
     * @param  Request    $request  [description]
     * @param  FeedPinned $pinned   [description]
     * @param  Carbon     $datetime [description]
     * @return [type]               [description]
     */
    public function accept(Comment $comment, FeedPinned $pinned, Carbon $datetime)
    {
        $date = $datetime->addDay($pinned->day)->toDateTimeString();
        $pinned->expires_at = $date;

        $pinned->save();

        $pinned->user->sendNotifyMessage(
            'feed-comment:pass',
            sprintf('你的评论《%s》已被管理员设置为置顶', str_limit($pinned->comment->body, 100)
        ),
        [
            'comment' => $comment,
            'pinned' => $pinned,
        ]);

        return response()->json(['message' => ['操作成功'], 'data' => $pinned], 201);
    }

    public function set(Request $request, Comment $comment, FeedPinned $pinned, Carbon $datetime)
    {
        $time = intval($request->input('day'));

        $pinnedNode = $pinned->where('target', $comment->id)
            ->where('channel', 'comment')
            ->with('user')
            ->first();

        if (! $pinnedNode) {
            $pinned->expires_at = $datetime->addDay($time);
            // $pinned = new FeedPinned();
            $pinned->user_id = $comment->user_id;
            $pinned->target = $comment->id;
            $pinned->channel = 'comment';
            $pinned->target_user = 0;
            $pinned->amount = 0;
            $pinned->day = $time;
            $pinned->expires_at = $datetime->toDateTimeString();

            $pinned->save();
            $pinned->user->sendNotifyMessage('feed-comment:pass', sprintf('你的评论《%s》已被管理员设置为置顶', str_limit($pinned->comment->body, 100)), [
                'comment' => $comment,
                'pinned' => $pinned,
            ]);

            return response()->json(['message' => ['操作成功'], 'data' => $pinned], 201);
        } else {
            $date = new Carbon($pinnedNode->expires_at);
            $datetime = $date->addDay($time);
            $pinnedNode->day = $datetime->diffInDays(Carbon::now());
            $pinnedNode->expires_at = $datetime->toDateTimeString();
            $pinnedNode->user_id = $comment->user_id;
            $pinnedNode->save();

            $pinnedNode->user->sendNotifyMessage('feed-comment:pass', sprintf('你的评论《%s》已被管理员设置为置顶', str_limit($pinnedNode->comment->body, 100)), [
                'comment' => $comment,
                'pinned' => $pinnedNode,
            ]);

            return response()->json(['message' => ['操作成功'], 'data' => $pinnedNode], 201);
        }
    }

    /**
     * 驳回评论置顶.
     * @param  FeedPinned $pinned
     */
    public function reject(FeedPinned $pinned)
    {
        $pinned->delete();

        return response()->json(['message' => ['删除成功']], 204);
    }
}
