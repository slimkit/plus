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

use Carbon\Carbon;
use Zhiyi\plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\UserCount;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned;

class FeedController extends Controller
{
    /**
     * Get feeds.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, Feed $model, Carbon $datetime, User $user)
    {
        $limit = (int) $request->query('limit', 20);
        $before = (int) $request->query('before', 0);
        $type = $request->query('type', 'all');
        $id = $request->query('id');
        $user_id = $request->query('user_id');
        $from = $request->query('from');
        $pay = $request->query('pay');
        $stime = $request->query('stime');
        $etime = $request->query('etime');
        $keyword = $request->query('keyword');
        $top = $request->query('top');
        $name = $request->query('userName');

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

        $users = [];

        if ($name) {
            $users = $user->where('name', 'like', "%{$name}%")
                ->get()
                ->pluck('id')
                ->toArray();
        }

        if ($user_id) {
            array_push($users, $user_id);
            $users = array_unique($users);
        }

        $feeds = $model->with([
                'user',
                'paidNode',
                'images',
                'images.paidNode',
                'pinned',
            ])
            ->when($before, function ($query) use ($before) { // 翻页
                return $query->where('id', '<', $before);
            })
            ->when($id, function ($query) use ($id) { // 根据id查询
                return $query->where('id', $id);
            })
            ->when($users, function ($query) use ($users) { // 根据用户id查询
                return $query->whereIn('user_id', $users);
            })
            ->when($from, function ($query) use ($from) { // 根据来源查询
                return $query->where('feed_from', $from);
            })
            ->when($keyword, function ($query) use ($keyword) { // 根据关键字筛选
                return $query->where('feed_content', 'like', '%'.$keyword.'%');
            })
            ->when($top && $top !== 'all', function ($query) use ($top, $datetime) { // 置顶筛选
                switch ($top) {
                    case 'no':
                        return $query->whereNotExists(function ($query) use ($datetime) {
                            return $query->from('feed_pinneds')->whereRaw('feed_pinneds.target = feeds.id')->where('channel', 'feed');
                        });
                        break;
                    case 'yes':
                        return $query->whereExists(function ($query) use ($datetime) {
                            return $query->from('feed_pinneds')->whereRaw('feed_pinneds.target = feeds.id')->where('channel', 'feed')->whereDate('expires_at', '>=', $datetime);
                        });
                        break;
                    case 'wait':
                        return $query->whereExists(function ($query) use ($datetime) {
                            return $query->from('feed_pinneds')->whereRaw('feed_pinneds.target = feeds.id')->where('channel', 'feed')->whereNull('expires_at');
                        });
                        break;
                    case 'reject':
                        return $query->whereExists(function ($query) use ($datetime) {
                            return $query->from('feed_pinneds')->whereRaw('feed_pinneds.target = feeds.id')->where('channel', 'feed')->whereDate('expires_at', '<', $datetime);
                        });
                        break;
                    default:
                        // code...
                        break;
                }
            })
            ->when($pay, function ($query) use ($pay) { // 筛选付费动态
                switch ($pay) {
                    case 'all':
                        return;
                        break;
                    case 'paid':
                        $method = 'whereExists';
                        break;
                    case 'free':
                        $method = 'whereNotExists';
                        break;
                    default:
                        return;
                        break;
                }

                return $query->where(function ($query) use ($method) {
                    return $query->{$method}(function ($query) {
                        return $query->from('paid_nodes')->where('channel', 'feed')->whereRaw('paid_nodes.raw = feeds.id');
                    })
                    ->orWhere(function ($query) use ($method) {
                        return $query->whereHas('images', function ($query) use ($method) {
                            return $query->{$method}(function ($query) {
                                return $query->from('paid_nodes')->where('channel', 'file')->whereRaw('paid_nodes.raw = file_withs.id');
                            });
                        });
                    });
                });
            })
            ->when($stime, function ($query) use ($stime, $datetime) { // 根据时间筛选
                return $query->whereDate('created_at', '>=', $stime);
            })
            ->when($etime, function ($query) use ($etime, $datetime) { // 根据时间筛选
                return $query->whereDate('created_at', '<', $etime);
            })
            ->orderBy('id', 'desc')
            ->paginate($limit);

        return response()->json($feeds)->setStatusCode(200);
    }

    /**
     * Get deleted feeds.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function deleted(Request $request, Feed $model)
    {
        $limit = (int) $request->query('limit', 20);
        $keyword = (string) $request->query('keyword', '');
        $user = (int) $request->query('user', 0);
        $name = (string) $request->query('name', '');

        // $users = $user ? [ $user ] : [];
        $ids = [];

        if ($name) {
            $ids = User::where('name', 'like', '%'.$name.'%')
                ->get()
                ->pluck('id')
                ->toArray();
        }

        if ($user) {
            array_push($ids, $user);
            $ids = array_unique($ids);
        }

        $feeds = $model->onlyTrashed()
            ->with([
                'user',
                'paidNode',
                'images',
                'images.paidNode',
            ])
            ->when($keyword !== '', function ($query) use ($keyword) {
                return $query->where('feed_content', 'like', "%{$keyword}%");
            })
            ->when($ids, function ($query) use ($ids) {
                return $query->whereIn('user_id', $ids);
            })
            ->orderBy('id', 'desc')
            ->paginate($limit);

        return response()->json($feeds)->setStatusCode(200);
    }

    /**
     * 永久删除.
     */
    public function delete(Request $request)
    {
        $feed = $request->query('feed');
        ! $feed && abort(400, '动态传递错误');
        Feed::withTrashed()->find($feed)->forceDelete();

        return response()->json()->setStatusCode(204);
    }

    /**
     * 恢复.
     */
    public function restore(Request $request)
    {
        $feed = $request->query('feed');
        ! $feed && abort(400, '动态传递错误');

        Feed::withTrashed()->find($feed)->restore();

        return response()->json(['message' => '恢复成功'])->setStatusCode(201);
    }

    /**
     * Delete feed.
     *
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(CacheContract $cache, Feed $feed)
    {
        $pinnedComments = FeedPinned::whereNull('expires_at')
            ->where('raw', $feed->id)
            ->where('channel', 'comment')
            ->get();
        $process = new UserProcess();
        $feed->getConnection()->transaction(function () use ($pinnedComments, $feed, $process, $cache) {
            $pinnedComments->map(function ($comment) use ($process, $feed, $pinnedComments) {
                $process->reject(0, $comment->amount, $comment->user_id, '评论申请置顶退款', sprintf('退还在动态《%s》申请置顶的评论的款项', str_limit($feed->feed_content, 100)));
                $comment->delete();
            });
            // 删除动态申请置顶
            $pinnedFeed = FeedPinned::where('channel', 'feed')
                ->where('target', $feed->id)
                ->first();
            if ($pinnedFeed) {
                $process->reject(0, $pinnedFeed->amount, $pinnedFeed->user_id, '动态申请置顶退款', sprintf('退还动态《%s》申请置顶的款项', str_limit($feed->feed_content, 100)));
                $pinnedFeed->delete();
            }

            // 删除话题关联
            $feed->topics->each(function ($topic) {
                $topic->feeds_count -= 1;
                $topic->save();
            });
            $feed->topics()->sync([]);

            $feed->delete();
            $cache->forget(sprintf('feed:%s', $feed->id));
        });
        $userUnreadCount = FeedPinned::whereNull('expires_at')
            ->where('target_user', $feed->user_id)
            ->where('channel', 'comment')
            ->count();

        $userCount = UserCount::firstOrNew([
            'type' => 'user-feed-comment-pinned',
            'user_id' => $feed->user_id,
        ]);
        $userCount->total = $userUnreadCount;
        $userCount->save();

        return response(null, 204);
    }

    public function show(Feed $feed)
    {
        $feed->load([
            'user',
            'paidNode',
            'images',
            'images.paidNode',
        ]);

        return response()->json($feed)->setStatusCode(200);
    }
}
