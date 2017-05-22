<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Zhiyi\Plus\Models\User;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;

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

    public function show(Request $request)
    {
        //
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
        $hasUser = (int) $request->query('following', $request->user() ?: 0);
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
        $hasUser = (int) $request->query('follower', $request->user() ?: 0);
        $user['follower'] = $user->hasFollower($hasUser);
    }
}
