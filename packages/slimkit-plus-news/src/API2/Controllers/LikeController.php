<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;

class LikeController extends Controller
{
    /**
     * 点赞一个资讯.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @param  int     $news_id [description]
     * @return [type]           [description]
     */
    public function like(Request $request, News $news)
    {
        $user = $request->user()->id;
        if ($news->liked($user)) {
            return response()->json([
                'message' => ['已赞过该资讯'],
            ])->setStatusCode(400);
        }

        $news->like($user);

        return response()->json()->setStatusCode(201);
    }

    /**
     * 取消点赞一个资讯.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request [description]
     * @param  int     $news_id [description]
     * @return [type]           [description]
     */
    public function cancel(Request $request, News $news)
    {
        $user = $request->user()->id;
        if (! $news->liked($user)) {
            return response()->json([
                'message' => ['未对该资讯点赞'],
            ])->setStatusCode(400);
        }

        $news->unlike($user);

        return response()->json()->setStatusCode(204);
    }

    /**
     * 一条资讯的点赞列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  News    $news
     * @return mix
     */
    public function index(Request $request, News $news)
    {
        $limit = $request->query('limit', 20);
        $after = $request->query('after', false);
        $userID = $request->user('api')->id ?? 0;
        $likes = $news->likes()
            ->with('user')
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(
            $news->getConnection()->transaction(function () use ($likes, $userID) {
                return $likes->map(function ($like) use ($userID) {
                    if (! $like->relationLoaded('user')) {
                        return $like;
                    }

                    $like->user->following = false;
                    $like->user->follower = false;

                    if ($userID && $like->user_id !== $userID) {
                        $like->user->following = $like->user->hasFollwing($userID);
                        $like->user->follower = $like->user->hasFollower($userID);
                    }

                    return $like;
                });
            })
        )->setStatusCode(200);
    }
}
