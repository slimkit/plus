<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use DB;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;

class RankController extends Controller
{
    /**
     * Get the full rank of user who has more followers.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  Zhiyi\Plus\Models\User $userModel
     * @return mixed
     */
    public function followers(Request $request, User $userModel)
    {
        $auth = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $users = $userModel->select('users.*', 'user_extras.followers_count')
            ->join('user_extras', function ($join) {
                return $join->on('users.id', '=', 'user_extras.user_id');
            })
            ->orderBy('user_extras.followers_count', 'desc')
            ->orderBy('users.id', 'asc')
            ->offset($offset)
            ->take($limit)
            ->get();

        return response()->json($userModel->getConnection()->transaction(function () use ($users, $userModel, $auth, $offset) {
            return $users->map(function ($user, $key) use ($auth, $offset) {
                $user->extra->count = $user->followers_count;
                $user->extra->rank = $key + $offset + 1;

                $user->following = $user->hasFollwing($auth);
                $user->follower = $user->hasFollower($auth);

                unset($user->followers_count);

                return $user;
            });
        }), 200);
    }

    /**
     * Get the total fortune ranking.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  Zhiyi\Plus\Models\User $userModel
     * @return mixed
     */
    public function balance(Request $request, User $userModel)
    {
        $auth = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $users = $userModel->select('users.*', 'users.name')
            ->join('wallets', function ($join) {
                return $join->on('users.id', '=', 'wallets.user_id');
            })
            ->orderBy('wallets.balance', 'desc')
            ->orderBy('users.id', 'asc')
            ->offset($offset)
            ->take($limit)
            ->get();

        return response()->json($userModel->getConnection()->transaction(function () use ($users, $auth, $offset) {
            return $users->map(function ($user, $key) use ($auth, $offset) {
                $user->following = $user->hasFollwing($auth);
                $user->follower = $user->hasFollower($auth);

                $user = $user->toArray();
                $user['extra']['rank'] = $key + $offset + 1;

                return $user;
            });
        }), 200);
    }

    /**
     * Get the rank of user`s income.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  Zhiyi\Plus\Models\User $userModel
     * @return mixed
     */
    public function income(Request $request, User $userModel)
    {
        $auth = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $users = $userModel->select('users.id', 'users.name')
            ->join(DB::raw("(select `user_id`, SUM(`amount`) as `count` from `wallet_charges` where `action` = '1' and `channel` = 'user' group by `user_id`) as count"), function ($join) {
                return $join->on('users.id', '=', 'count.user_id');
            })
            ->orderBy('count.count', 'desc')
            ->orderBy('users.id', 'asc')
            ->offset($offset)
            ->take($limit)
            ->get();

        return response()->json($userModel->getConnection()->transaction(function () use ($users, $auth, $offset) {
            return $users->map(function ($user, $key) use ($auth, $offset) {
                $user->following = $user->hasFollwing($auth);
                $user->follower = $user->hasFollower($auth);

                $user = $user->toArray();
                $user['extra']['rank'] = $key + $offset + 1;

                return $user;
            });
        }), 200);
    }
}
