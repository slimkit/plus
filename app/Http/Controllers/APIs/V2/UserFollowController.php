<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class UserFollowController extends Controller
{
    /**
     * List followers of a user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followers(Request $request, ResponseFactoryContract $response, UserModel $user)
    {
        $target = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 20);
        $after = $request->query('after', false);

        $followers = $user->followers()
            ->when($after, function ($query) use ($after, $user) {
                return $query->where($user->getQualifiedKeyName(), '<', $after);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $user->getConnection()->transaction(function () use ($followers, $target, $response) {
            return $response->json($followers->map(function (UserModel $user) use ($target) {
                $user->following = $user->hasFollwing($target);
                $user->follower = $user->hasFollower($target);

                return $user;
            }))->setStatusCode(200);
        });
    }

    /**
     * List users followed by another user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followings(Request $request, ResponseFactoryContract $response, UserModel $user)
    {
        $target = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 20);
        $after = $request->query('after', false);

        $followings = $user->followings()
            ->when($after, function ($query) use ($after, $user) {
                return $query->where($user->getQualifiedKeyName(), '<', $after);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $user->getConnection()->transaction(function () use ($followings, $target, $response) {
            return $response->json($followings->map(function (UserModel $user) use ($target) {
                $user->following = $user->hasFollwing($target);
                $user->follower = $user->hasFollower($target);

                return $user;
            }))->setStatusCode(200);
        });
    }
}
