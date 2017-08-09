<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\UserExtra as UserExtraModel;
use Zhiyi\Plus\Models\UserRecommended as UserRecommendedModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

/**
 * 找人.
 */
class FindUserController extends Controller
{
    /**
     * 热门用户, 根据粉丝数量倒序排列.
     */
    public function populars(Request $request, UserExtraModel $userExtra, ResponseContract $response)
    {
        $limit = $request->input('limit', 20);
        $offset = $request->input('offset', 0);
        $u = $request->user();

        $users = $userExtra
            ->when($offset, function ($query) use ($offset) {
                return $query->offset($offset);
            })
            ->limit($limit)
            ->select('user_id')
            ->with([
                'user',
            ])
            ->orderBy('followers_count', 'desc')
            ->get();

        return $response->json(
            $users->map(function ($user) use ($u) {
                $user->user->following = $u->hasFollwing($user->user->id);
                $user->user->follower = $u->hasFollower($user->user->id);

                return $user->user;
            })
        )
        ->setStatusCode(200);
    }

    /**
     * 最新用户,按注册时间倒序.
     */
    public function latests(Request $request, UserModel $user, ResponseContract $response)
    {
        $limit = $request->input('limit', 20);
        $after = $request->input('after', null);
        $u = $request->user();

        $users = $user->when($after, function ($query) use ($after) {
            return $query->where('id', '>', $after);
        })
            ->latest()
            ->limit($limit)
            ->get();

        return $response->json(
            $users->map(function ($user) use ($u) {
                $user->following = $u->hasFollwing($user->id);
                $user->follower = $u->hasFollower($user->id);

                return $user;
            })
        )
        ->setStatusCode(200);
    }

    /**
     * 推荐用户.
     */
    public function recommends(Request $request, UserRecommendedModel $userRecommended, ResponseContract $response)
    {
        $u = $request->user();
        $limit = $request->input('limit', 20);
        $offset = $request->input('offset', 0);

        $users = $userRecommended->when($offset, function ($query) use ($offset) {
            return $query->offset($offset);
        })
            ->with(['user'])
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $response->json(
            $users->map(function ($user) use ($u) {
                $user->user->following = $u->hasFollwing($user->user->id);
                $user->user->follower = $u->hasFollower($user->user->id);

                return $user->user;
            })
        )
        ->setStatusCode(200);
    }

    /**
     * search users by name
     */
    public function search(Request $request, UserModel $user, ResponseContract $response)
    {
        $u = $request->user();
        $limit = $request->input('limit', 20);
        $offset = $request->input('offset', 0);
        $keyword = $request->input('keyword', null);

        if(!$keyword) {
            abort(422, '请输入关键字');
        }

        $users = $user->where('name', 'like', "%{$keyword}%")
            ->when($offset, function ($query) use ($offset) {
                return $query->offset($offset);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $response->json(
            $users->map(function($user) use ($u) {
                $user->following = $u->hasFollwing($user->id);
                $user->follower = $u->hasFollower($user->id);

                return $user;
            })
        )
        ->setStatusCode(200);
    }
}
