<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;


use Zhiyi\Plus\Models\User;
// use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     *  Get user.
     *
     * @param \Zhiyi\Plus\Models\User $user
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(User $user)
    {
        $user->load('datas');

        return response()->json($user, 200);
    }
}
