<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Http\Requests\API2\StoreUserPost;

class UserController extends Controller
{
    /**
     *  Get user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Models\User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user(Request $request, User $user)
    {
        $user->load('datas');

        // 我关注的处理
        $this->hasFollowing($request, $user);
        // 处理关注我的
        $this->hasFollower($request, $user);

        return response()->json($user, 200);
    }

    /**
     * 获取用户列表.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request)
    {
        $ids = array_filter(explode(',', $request->query('user')));
        $currentUser = $request->user('api') ? $request->user('api')->id : 0;

        if (empty($ids)) {
            return response()->json([], 200);
        }

        $users = User::whereIn('id', $ids)
            ->with([
                'datas',
                'followings' => function ($query) use ($currentUser) {
                    $query->where('id', $currentUser);
                },
                'followers' => function ($query) use ($currentUser) {
                    $query->where('id', $currentUser);
                },
            ])
            ->get();

        $users = $users->reduce(function (Collection $users, $user) {
            $temp = new Collection($user);

            $temp->pull('followings');
            $temp->pull('followers');
            $temp->offsetSet('following', $user->followings->isNotEmpty());
            $temp->offsetSet('follower', $user->followers->isNotEmpty());

            $users->push($temp);

            return $users;
        }, new Collection());

        return response()->json($users, 200);
    }

    /**
     * 创建用户.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(StoreUserPost $request)
    {
        $phone = $request->input('phone');
        $name = $request->input('name');
        $password = $request->input('password');
        $verifyCode = $request->input('verify_code');
    }

    /**
     * 处理我关注的状态.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Models\User &$user
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function hasFollowing(Request $request, User &$user)
    {
        $currentUser = $request->user('api');
        $hasUser = (int) $request->query('following', $currentUser ? $currentUser->id : 0);
        $user['following'] = $user->hasFollwing($hasUser);
    }

    /**
     * 验证是否关注了我.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Models\User &$user
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function hasFollower(Request $request, User &$user)
    {
        $currentUser = $request->user('api');
        $hasUser = (int) $request->query('follower', $currentUser ? $currentUser->id : 0);
        $user['follower'] = $user->hasFollower($hasUser);
    }
}
