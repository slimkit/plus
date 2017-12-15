<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Controllers;

use DB;
use Carbon\Carbon;
use Zhiyi\Plus\Models\Digg;
use Illuminate\Http\Request;
use Zhiyi\Plus\Storages\Storage;
use Zhiyi\Plus\Models\StorageTask;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedAtme;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedDigg;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedComment;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Services\FeedCount;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedCollection;

class FeedController extends Controller
{
    public function formatFeedList($feeds, $uid)
    {
        $datas = [];
        foreach ($feeds as $feed) {
            $data = [];
            $data['user_id'] = $feed->user_id;
            $data['feed_mark'] = $feed->feed_mark;
            // 动态数据
            $data['feed'] = [];
            $data['feed']['feed_id'] = $feed->id;
            $data['feed']['feed_content'] = $feed->feed_content;
            $data['feed']['created_at'] = $feed->created_at->toDateTimeString();
            $data['feed']['feed_from'] = $feed->feed_from;
            $data['feed']['storages'] = $feed->storages->map(function ($storage) {
                return ['storage_id' => $storage->id, 'width' => $storage->image_width, 'height' => $storage->image_height];
            });
            // 工具数据
            $data['tool'] = [];
            $data['tool']['feed_view_count'] = $feed->feed_view_count;
            $data['tool']['feed_digg_count'] = $feed->feed_digg_count;
            $data['tool']['feed_comment_count'] = $feed->feed_comment_count;
            // 暂时剔除当前登录用户判定
            $data['tool']['is_digg_feed'] = $uid ? FeedDigg::byFeedId($feed->id)->byUserId($uid)->count() : 0;
            $data['tool']['is_collection_feed'] = $uid ? FeedCollection::where('feed_id', $feed->id)->where('user_id', $uid)->count() : 0;
            // 最新3条评论
            $data['comments'] = [];

            $getCommendsNumber = 5;
            $data['comments'] = $feed->comments()
                ->orderBy('id', 'desc')
                ->take($getCommendsNumber)
                ->select(['id', 'user_id', 'created_at', 'comment_content', 'reply_to_user_id', 'comment_mark'])
                ->get()
                ->toArray();

            $datas[] = $data;
        }

        return response()->json([
                'status'  => true,
                'code'    => 0,
                'message' => '动态列表获取成功',
                'data' => $datas,
            ])->setStatusCode(200);
    }

    /**
     * 创建动态
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (! $request->storage_task_ids && ! $request->feed_content) {
            return response()->json([
                'status'  => false,
                'code'    => 6001,
                'message' => '动态内容不能为空',
            ])->setStatusCode(400);
        }
        $storages = [];
        if ($request->storage_task_ids) {
            $storage_task_ids = $request->storage_task_ids;
            $storageTasks = StorageTask::whereIn('id', $storage_task_ids)
                ->with('storage')
                ->get();
            $storages = $storageTasks->map(function ($storageTask) {
                return $storageTask->storage->id;
            });
        }
        $feed = new Feed();
        $feed->feed_content = $request->feed_content ?? '';
        $feed->feed_client_id = $request->getClientIp();
        $feed->user_id = $request->user()->id;
        $feed->feed_from = $request->feed_from;
        $feed->feed_latitude = $request->input('latitude', '');
        $feed->feed_longtitude = $request->input('longtitude', '');
        $feed->feed_geohash = $request->input('geohash', '');
        $feed->feed_mark = $request->input('feed_mark', ($user->id.Carbon::now()->timestamp) * 1000); //默认uid+毫秒时间戳

        DB::transaction(function () use ($feed, $storages, $request, $user) { // 创建动态时的数据连贯操作
            $feed->save();
            $feed->storages()->sync($storages);

            $user->storages()->sync($storages, false); // 更新作者的个人相册

            $request->isatuser == 1 && $this->analysisAtme($feed->feed_content, $feed->user_id, $feed->id);

            $count = new FeedCount();
            $count->count($user->id, 'feeds_count', 'increment'); //更新动态作者的动态数量
        });

        return response()->json([
                'status' => true,
                'code' => 0,
                'message' => '动态创建成功',
                'data' => $feed->id,
            ])->setStatusCode(201);
    }

    public function read($feed_id)
    {
        $uid = Auth::guard('api')->user()->id ?? 0;
        if (! $feed_id) {
            return response()->json([
                'status' => false,
                'code' => 6003,
                'message' => '动态ID不能为空',
            ])->setStatusCode(400);
        }
        $feed = Feed::where('id', intval($feed_id))
            ->with([
                'diggs' => function ($query) {
                    $query->take(8);
                },
                'storages',
            ])
            ->first();
        if (! $feed) {
            return response()->json([
                'status' => false,
                'code' => 6004,
                'message' => '动态不存在或已被删除',
            ])->setStatusCode(404);
        }
        // 组装数据
        $data = [];
        // 用户标识
        $data['user_id'] = $feed->user_id;
        // 动态内容
        $data['feed'] = [];
        $data['feed']['feed_id'] = $feed->id;
        $data['feed']['feed_content'] = $feed->feed_content;
        $data['feed']['created_at'] = $feed->created_at->toDateTimeString();
        $data['feed']['feed_from'] = $feed->feed_from;
        $data['feed']['storages'] = $feed->storages->map(function ($storage) {
            return ['storage_id' => $storage->id, 'width' => $storage->image_width, 'height' => $storage->image_height];
        });
        // 工具栏数据
        $data['tool'] = [];
        $data['tool']['feed_view_count'] = $feed->feed_view_count;
        $data['tool']['feed_digg_count'] = $feed->feed_digg_count;
        $data['tool']['feed_comment_count'] = $feed->feed_comment_count;
        // 暂时剔除当前登录用户判定
        $data['tool']['is_digg_feed'] = $uid ? FeedDigg::byFeedId($feed->id)->byUserId($uid)->count() : 0;
        $data['tool']['is_collection_feed'] = $uid ? FeedCollection::where('feed_id', $feed->id)->where('user_id', $uid)->count() : 0;
        // 动态评论,详情默认为空，自动获取评论列表接口
        $data['comments'] = [];
        // 动态最新8条点赞的用户id
        $data['diggs'] = $feed->diggs->map(function ($digg) {
            return $digg->user_id;
        });

        Feed::byFeedId($feed_id)->increment('feed_view_count');

        return response()->json([
                'status' => true,
                'code' => 0,
                'message' => '获取动态成功',
                'data' => $data,
            ])->setStatusCode(200);
    }

    /**
     * 最新动态列表构建.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getNewFeeds(Request $request)
    {
        $user_id = Auth::guard('api')->user()->id ?? 0;
        $feed_ids = $request->input('feed_ids');
        is_string($feed_ids) && $feed_ids = explode(',', $feed_ids);
        // 设置单页数量
        $limit = $request->limit ?? 15;
        $feeds = Feed::orderBy('id', 'DESC')
            ->where(function ($query) use ($request) {
                if ($request->max_id > 0) {
                    $query->where('id', '<', $request->max_id);
                }
            })
            ->where(function ($query) use ($feed_ids) {
                if (count($feed_ids) > 0) {
                    $query->whereIn('id', $feed_ids);
                }
            })
            ->withCount(['diggs' => function ($query) use ($user_id) {
                if ($user_id) {
                    $query->where('user_id', $user_id);
                }
            }])
            ->byAudit()
            ->with('storages')
            ->take($limit)
            ->get();

        return $this->formatFeedList($feeds, $user_id);
    }

    /**
     * Get the feed list of my follows.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getFollowFeeds(Request $request)
    {
        $user_id = Auth::guard('api')->user()->id;
        // 设置单页数量
        $limit = $request->limit ?? 15;
        $feeds = Feed::orderBy('id', 'DESC')
            ->whereIn('user_id', array_merge([$user_id], $request->user()->follows->pluck('following_user_id')->toArray()))
            ->where(function ($query) use ($request) {
                if ($request->max_id > 0) {
                    $query->where('id', '<', $request->max_id);
                }
            })
            ->withCount(['diggs' => function ($query) use ($user_id) {
                if ($user_id) {
                    $query->where('user_id', $user_id);
                }
            }])
            ->byAudit()
            ->with('storages')
            ->take($limit)
            ->get();

        return $this->formatFeedList($feeds, $user_id);
    }

    /**
     * 热门动态列表构建.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getHotFeeds(Request $request)
    {
        $user_id = Auth::guard('api')->user()->id ?? 0;
        // 设置单页数量
        $limit = $request->limit ?? 15;
        $page = $request->page ?? 1;
        $skip = ($page - 1) * $limit;

        $feeds = Feed::orderBy('id', 'DESC')
            ->whereIn('id', FeedDigg::groupBy('feed_id')
                ->take($limit)
                ->select('feed_id', DB::raw('COUNT(user_id) as diggcount'))
                ->where('created_at', '>', Carbon::now()->subMonth()->toDateTimeString())
                ->orderBy('diggcount', 'desc')
                ->orderBy('feed_id', 'desc')
                ->skip($skip)
                ->pluck('feed_id')
                )
            ->withCount(['diggs' => function ($query) use ($user_id) {
                if ($user_id) {
                    $query->where('user_id', $user_id);
                }
            }])
            ->byAudit()
            ->with('storages')
            ->get();

        return $this->formatFeedList($feeds, $user_id);
    }

    /**
     * 获取单个用户的动态列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getUserFeeds(Request $request, int $user_id)
    {
        $auth_id = Auth::guard('api')->user()->id ?? 0;
        $limit = $request->input('limit', 15);
        $max_id = intval($request->input('max_id'));

        $feeds = Feed::orderBy('id', 'DESC')
        ->where('user_id', $user_id)
        ->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('id', '<', $max_id);
            }
        })
        ->withCount(['diggs' => function ($query) use ($user_id) {
            if ($user_id) {
                $query->where('user_id', $user_id);
            }
        }])
        ->byAudit()
        ->with('storages')
        ->take($limit)
        ->get();

        return $this->formatFeedList($feeds, $auth_id);
    }

    /**
     * 获取用户收藏列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @param  int     $user_id [description]
     * @return [type]           [description]
     */
    public function getUserCollection(Request $request)
    {
        $uid = $request->user()->id;
        $limit = $request->input('limit', 15);
        $max_id = intval($request->input('max_id'));

        $feeds = Feed::orderBy('id', 'DESC')
        ->where(function ($query) use ($max_id, $uid) {
            $query->whereIn('id', FeedCollection::where('user_id', $uid)->pluck('feed_id'));
            if ($max_id > 0) {
                $query->where('id', '<', $max_id);
            }
        })
        ->withCount(['diggs' => function ($query) use ($uid) {
            $query->where('user_id', $uid);
        }])
        ->byAudit()
        ->with('storages')
        ->take($limit)
        ->get();

        return $this->formatFeedList($feeds, $uid);
    }

    /**
     * 增加浏览量.
     *
     * @author bs<414606094@qq.com>
     * @param  int $feed_id [description]
     */
    public function addFeedViewCount(int $feed_id)
    {
        Feed::byFeedId($feed_id)->increment('feed_view_count');

        return response()->json([
            'status' => true,
            'code' => 0,
            'message' => 'ok',
            'data' => null,
        ])->setStatusCode(201);
    }

    /**
     * 解析动态中的@用户.
     *
     * @author bs<414606094@qq.com>
     * @param  [type] $content [description]
     */
    protected function analysisAtme(string $content, int $user_id, int $feed_id)
    {
        $pattern = '/\[tsplus:(\d+):(\w+)\]/is';
        preg_match_all($pattern, $content, $matchs);
        $uids = $matchs[1];
        $time = Carbon::now();
        if (is_array($uids)) {
            $datas = array_map(function ($data) use ($user_id, $feed_id, $time) {
                return ['at_user_id' => $data, 'user_id' => $user_id, 'feed_id' => $feed_id, 'created_at' => $time, 'updated_at' => $time];
            }, $uids);

            FeedAtme::insert($datas); // 批量插入数据需要手动维护时间
        }
    }

    /**
     * 删除动态
     *
     * @author bs<414606094@qq.com>
     * @param  int    $feed_id [description]
     * @return [type]          [description]
     */
    public function delFeed(Request $request, int $feed_id)
    {
        $user_id = $request->user()->id;

        $feed = new Feed();
        $feed = $feed->where('id', $feed_id)->where('user_id', $user_id)->first();
        if ($feed) {
            DB::transaction(function () use ($feed, $user_id) {
                $comments = new FeedComment();
                $comments->where('feed_id', $feed->id)->delete(); // 删除相关评论

                $digg = new FeedDigg();
                $digg->where('feed_id', $feed->id)->delete(); // 删除相关点赞

                Digg::where(['component' => 'feed', 'digg_table' => 'feed_diggs', 'source_id' => $feed->id])->delete(); // 删除点赞总表记录

                $atme = new FeedAtme();
                $atme->where('feed_id', $feed->id)->delete(); // 删除@记录

                $collection = new FeedCollection();
                $collection->where('feed_id', $feed->id)->delete(); // 删除相关收藏

                $count = new FeedCount();
                $count->count($user_id, 'feeds_count', 'decrement'); // 更新动态作者的动态数量
                $count->count($user_id, 'diggs_count', 'decrement', $feed->feed_digg_count); // 更新动态作者收到的点赞数量

                $feed->delete();
            });

            return response()->json([
                'status'  => true,
                'code'    => 0,
                'message' => '删除成功',
                'data' => null,
            ])->setStatusCode(204);
        } else {
            return response()->json([
                'status'  => false,
                'code'    => 6010,
                'message' => '操作失败',
                'data' => null,
            ])->setStatusCode(403);
        }
    }
}
