<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use DB;
use Carbon\Carbon;
use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;

class RankController extends Controller
{

    /**
     * 获取全站粉丝排行.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  Zhiyi\Plus\Models\User $userModel
     * @return mixed
     */
    public function followers(Request $request, User $userModel)
    {
        $auth = $request->user();
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $users = $userModel->select('users.id', 'users.name', 'user_extras.followers_count')
            ->join('user_extras', function ($join) {
                return $join->on('users.id', '=', 'user_extras.user_id');
            })
            ->orderBy('user_extras.followers_count', 'desc')
            ->offset($offset)
            ->take($limit)
            ->get();

        return response()->json($userModel->getConnection()->transaction(function () use ($users, $userModel, $auth, $offset) {
            $data = [
                'user_count' => 0,
                'ranks' => []
            ];

            $data['ranks'] = $users->map(function ($user, $key) use ($auth, $offset) {
                $user->addHidden('extra');
                $user->count = $user->followers_count;
                $user->rank = $key + $offset + 1;

                $user->following = $user->hasFollwing($auth);
                $user->follower = $user->hasFollower($auth);

                unset($user->followers_count);

                return $user;
            });

            $data['user_count'] = $userModel->extra()->where('user_id', $auth->id)->value('followers_count') ?? 0;

            return $data;
        }), 200);
    }

    /**
     * 获取全站财富排行.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  Zhiyi\Plus\Models\User $userModel
     * @return mixed
     */
    public function balance(Request $request, User $userModel)
    {
        $auth = $request->user();
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $users = $userModel->select('users.id', 'users.name')
            ->join('wallets', function ($join) {
                return $join->on('users.id', '=', 'wallets.user_id');
            })
            ->orderBy('wallets.balance', 'desc')
            ->offset($offset)
            ->take($limit)
            ->get();

        return response()->json($userModel->getConnection()->transaction(function () use ($users, $userModel, $auth, $offset) {
            return $users->map(function ($user, $key) use ($auth, $offset) {
                $user->addHidden('extra');
                $user->rank = $key + $offset + 1;

                $user->following = $user->hasFollwing($auth);
                $user->follower = $user->hasFollower($auth);

                return $user;
            });
        }), 200);
    }
}
