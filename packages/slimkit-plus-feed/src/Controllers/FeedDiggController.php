<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Controllers;

use DB;
use Zhiyi\Plus\Models\Digg;
use Illuminate\Http\Request;
use Zhiyi\Plus\Jobs\PushMessage;
use Illuminate\Database\QueryException;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedDigg;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedStorage;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Services\FeedCount;

class FeedDiggController extends Controller
{
    /**
     * 获取赞微博的用户.
     *
     * @author bs<414606094@qq.com>
     * @return json
     */
    public function getDiggList(Request $request, int $feed_id)
    {
        $limit = $request->get('limit', 10);
        // intval($request->limit) ? : 10;
        $max_id = $request->get('max_id');
        $feed = Feed::byFeedId($feed_id)
            ->with([
                'diggs' => function ($query) use ($limit, $max_id) {
                    if (intval($max_id) > 0) {
                        $query->where('id', '<', intval($max_id));
                    }
                    $query->take($limit)->orderBy('id', 'desc');
                },
                'diggs.user',
            ])
            ->orderBy('id', 'desc')->first();
        if (! $feed) {
            return response()->json(static::createJsonData([
                'code' => 6004,
                'status' => false,
                'message' => '指定动态不存在',
            ]))->setStatusCode(404);
        }

        if ($feed->diggs->isEmpty()) {
            return response()->json(static::createJsonData([
                'status' => true,
                'data' => [],
            ]))->setStatusCode(200);
        }
        foreach ($feed->diggs as $digg) {
            $user['feed_digg_id'] = $digg->id;
            $user['user_id'] = $digg->user_id;

            $users[] = $user;
        }

        return response()->json(static::createJsonData([
            'status' => true,
            'data' => $users,
        ]))->setStatusCode(200);
    }

    /**
     * 点赞一个动态
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @param  int     $feed_id [description]
     * @return [type]           [description]
     */
    public function diggFeed(Request $request, int $feed_id)
    {
        $feed = Feed::find($feed_id);
        if (! $feed) {
            return response()->json(static::createJsonData([
                'code' => 6004,
            ]))->setStatusCode(403);
        }
        $feeddigg['user_id'] = $request->user()->id;
        $feeddigg['feed_id'] = $feed_id;
        if (FeedDigg::byFeedId($feed_id)->byUserId($feeddigg['user_id'])->first()) {
            return response()->json(static::createJsonData([
                'code' => 6005,
                'status' => false,
                'message' => '已赞过该动态',
            ]))->setStatusCode(400);
        }

        DB::beginTransaction();

        try {
            $digg = new FeedDigg();
            $digg->user_id = $feeddigg['user_id'];
            $digg->feed_id = $feeddigg['feed_id'];
            $digg->save();

            Feed::byFeedId($feed_id)->increment('feed_digg_count'); //增加点赞数量

            $count = new FeedCount();
            $count->count($feed->user_id, 'diggs_count', $method = 'increment'); //更新动态作者收到的赞数量

            Digg::create(['component' => 'feed',
                        'digg_table' => 'feed_diggs',
                        'digg_id' => $digg->id,
                        'source_table' => 'feeds',
                        'source_id' => $feed_id,
                        'source_content' => $feed->feed_content,
                        'source_cover' => $feed->storages->isEmpty() ? 0 : $feed->storages->toArray()[0]['id'],
                        'user_id' => $feeddigg['user_id'],
                        'to_user_id' => $feed->user_id,
                        ]); // 统计到点赞总表

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json(static::createJsonData([
                'status' => false,
                'message' => $e->formatMessage(),
                'code' => 6010,
            ]))->setStatusCode(400);
        }
        $extras = ['action' => 'digg'];
        $alert = '有人赞了你的动态，去看看吧';
        $alias = $feed->user_id;

        dispatch(new PushMessage($alert, (string) $alias, $extras));

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '点赞成功',
        ]))->setStatusCode(201);
    }

    /**
     * 取消点赞一个动态
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @param  int     $feed_id [description]
     * @return [type]           [description]
     */
    public function cancelDiggFeed(Request $request, int $feed_id)
    {
        $feed = Feed::find($feed_id);
        if (! $feed) {
            return response()->json(static::createJsonData([
                'code' => 6004,
            ]))->setStatusCode(403);
        }
        $feeddigg['user_id'] = $request->user()->id;
        $feeddigg['feed_id'] = $feed_id;
        $digg = FeedDigg::where($feeddigg)->first();
        if (! $digg) {
            return response()->json(static::createJsonData([
                'code' => 6006,
                'status' => false,
                'message' => '未对该动态点赞',
            ]))->setStatusCode(400);
        }
        DB::transaction(function () use ($digg, $feed, $feed_id) {
            $digg->delete();
            Feed::byFeedId($feed_id)->decrement('feed_digg_count'); //减少点赞数量

            $count = new FeedCount();
            $count->count($feed->user_id, 'diggs_count', 'decrement'); //更新动态作者收到的赞数量

            Digg::where(['component' => 'feed', 'digg_id' => $digg->id])->delete(); // 统计到点赞总表
        });

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '取消点赞成功',
        ]))->setStatusCode(204);
    }

    /**
     * 我收到的赞.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function mydiggs(Request $request)
    {
        $user_id = $request->user()->id;
        $limit = $request->input('limit', 15);
        $max_id = intval($request->input('max_id'));
        $diggs = FeedDigg::join('feeds', function ($query) use ($user_id) {
            $query->on('feeds.id', '=', 'feed_diggs.feed_id')->where('feeds.user_id', $user_id);
        })
        ->select(['feed_diggs.id', 'feed_diggs.user_id', 'feed_diggs.created_at', 'feed_diggs.feed_id', 'feeds.feed_content', 'feeds.feed_title'])
        ->where(function ($query) use ($max_id) {
            if ($max_id > 0) {
                $query->where('feed_diggs.id', '<', $max_id);
            }
        })
        ->take($limit)
        ->orderBy('id', 'desc')
        ->get()->toArray();
        foreach ($diggs as &$digg) {
            $digg['storages'] = FeedStorage::where('feed_id', $digg['feed_id'])->select('feed_storage_id')->get()->toArray();
        }

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '获取成功',
            'data' => $diggs,
        ]))->setStatusCode(200);
    }
}
