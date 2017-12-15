<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedCollection;

class FeedCollectionController extends Controller
{
    /**
     * 收藏一条动态
     *
     * @author bs<414606094@qq.com>
     * @param  [type] $feed_id [description]
     */
    public function addFeedCollection(Request $request, int $feed_id)
    {
        $feed = Feed::find($feed_id);
        if (! $feed) {
            return response()->json(static::createJsonData([
                'code' => 6004,
            ]))->setStatusCode(403);
        }
        $feedcollection['user_id'] = $request->user()->id;
        $feedcollection['feed_id'] = $feed_id;
        if (FeedCollection::where('feed_id', $feed_id)->where('user_id', $feedcollection['user_id'])->first()) {
            return response()->json(static::createJsonData([
                'code' => 6008,
                'status' => false,
                'message' => '收藏该动态',
            ]))->setStatusCode(400);
        }

        FeedCollection::create($feedcollection);

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '收藏成功',
        ]))->setStatusCode(201);
    }

    /**
     * 取消收藏一条动态
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @param  int     $feed_id [description]
     * @return [type]           [description]
     */
    public function delFeedCollection(Request $request, int $feed_id)
    {
        $feed = Feed::find($feed_id);
        if (! $feed) {
            return response()->json(static::createJsonData([
                'code' => 6004,
            ]))->setStatusCode(403);
        }
        $feedcollection['user_id'] = $request->user()->id;
        $feedcollection['feed_id'] = $feed_id;
        if (! FeedCollection::where('feed_id', $feed_id)->where('user_id', $feedcollection['user_id'])->first()) {
            return response()->json(static::createJsonData([
                'code' => 6006,
                'status' => false,
                'message' => '未对该动态收藏',
            ]))->setStatusCode(400);
        }

        FeedCollection::where('feed_id', $feed_id)->where('user_id', $feedcollection['user_id'])->delete();

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '取消收藏成功',
        ]))->setStatusCode(204);
    }
}
