<?php
namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Controllers;

use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedDigg;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedStorage;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Services\FeedCount;
use Zhiyi\Plus\Models\UserDatas;

class FeedDiggController extends Controller
{
	/**
	 * 获取赞微博的用户
	 * 
	 * @author bs<414606094@qq.com>
	 * @return json
	 */	
	public function getDiggList(Request $request, int $feed_id)
	{
		$limit = $request->get('limit', 10);
		//intval($request->limit) ? : 10;
		$max_id = $request->get('max_id');
		$feed = Feed::byFeedId($feed_id)
			->with([
				'diggs' => function ($query) use ($limit, $max_id) {
					if (intval($max_id) > 0)  {
						$query->where('id', '<', intval($max_id));
					}
					$query->take($limit)->orderBy('id', 'desc');
				},
				'diggs.user'
			])
			->orderBy('id', 'desc')->first();
		if (!$feed) {
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
		foreach ($feed->diggs as $key => $value) {
				$user['feed_digg_id'] = $value->id;
				$user['user_id'] = $value->user_id;

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
		if (!$feed) {
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
		// $push = new Feedpush();
		// if ($push) {
		// 	$extras = ['action' => 'digg'];
		// 	$alert = '有人赞了你的动态，去看看吧';
		// 	$audience = 'all';

		// 	$push->push($alert, $audience, $extras);
		// }
		
		FeedDigg::create($feeddigg);
		Feed::byFeedId($feed_id)->increment('feed_digg_count');//增加点赞数量

		$count = new FeedCount();
		$count->count($feed->user_id, 'diggs_count', $method = 'increment');//更新动态作者收到的赞数量

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
		if (!$feed) {
            return response()->json(static::createJsonData([
                'code' => 6004,
            ]))->setStatusCode(403);
		}
		$feeddigg['user_id'] = $request->user()->id;
		$feeddigg['feed_id'] = $feed_id;
		if (!FeedDigg::where($feeddigg)->first()) {
            return response()->json(static::createJsonData([
            	'code' => 6006,
                'status' => false,
                'message' => '未对该动态点赞',
            ]))->setStatusCode(400);
		}

		if(FeedDigg::byFeedId($feed_id)->byUserId($feeddigg['user_id'])->delete()){
			Feed::byFeedId($feed_id)->decrement('feed_digg_count');//减少点赞数量
		
			$count = new FeedCount();
			$count->count($feed->user_id, 'diggs_count', $method = 'decrement');//更新动态作者收到的赞数量
		}

        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '取消点赞成功',
        ]))->setStatusCode(204);
	}

	/**
	 * 我收到的赞
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
		$diggs = FeedDigg::join('feeds', function ($query) use($user_id) {
			$query->on('feeds.id', '=', 'feed_diggs.feed_id')->where('feeds.user_id', $user_id);
		})->select(['feed_diggs.id', 'feed_diggs.user_id', 'feed_diggs.created_at', 'feed_diggs.feed_id', 'feeds.feed_content', 'feeds.feed_title'])
		->get()->toArray();
		foreach ($diggs as $key => &$value) {
			$value['storages'] = FeedStorage::where('feed_id', $value['feed_id'])->select('feed_storage_id')->get()->toArray();
		}
		
        return response()->json(static::createJsonData([
            'status' => true,
            'message' => '获取成功',
            'data' => $diggs,
        ]))->setStatusCode(200);
	}
}