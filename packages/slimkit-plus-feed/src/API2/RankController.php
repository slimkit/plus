<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\API2;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;

class RankController extends Controller
{
    /**
     * 动态排行.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feedModel
     * @param  Carbon\Carbon $datetime
     * @param  Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     */
    public function index(Request $request, FeedModel $feedModel, Carbon $datetime, ResponseContract $response)
    {
        $user = $request->user('api')->id ?? 0;
        $type = $request->query('type', 'day');
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        switch ($type) {
            case 'day':
                $date = $datetime->subDay();
                break;
            case 'week':
                $date = $datetime->subWeek();
                break;
            case 'month':
                $date = $datetime->subMonth();
                break;
            default:
                $date = $datetime->subDay();
                break;
        }

        $feeds = $feedModel->select('user_id', DB::raw('sum(like_count) as count'))
        ->where('feeds.created_at', '>', $date)
        ->where('audit_status', 1)
        ->with(['user' => function ($query) {
            return $query->select('id', 'name', 'sex');
        }, 'user.extra'])
        ->groupBy('user_id')
        ->orderBy('count', 'desc')
        ->orderBy('user_id', 'asc')
        ->offset($offset)
        ->take($limit)
        ->get();

        return $response->json($feedModel->getConnection()->transaction(function () use ($feeds, $user, $date, $feedModel, $offset) {
            return $feeds->map(function ($feed, $key) use ($user, $offset) {
                $feed->user->following = $feed->user->hasFollwing($user);
                $feed->user->follower = $feed->user->hasFollower($user);

                $return = $feed->user->toArray();
                $return['extra']['rank'] = $key + $offset + 1;
                $return['extra']['count'] = (int) $feed->count;

                return $return;
            });
        }), 200);
    }
}
