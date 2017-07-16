<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;

class CurrentUserController extends Controller
{
    /**
     * Get the user.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request)
    {
        $user = $request->user();

        $user->load([
            'wallet',
            'datas',
            'counts',
        ]);

        return response()->json($user, 200);
    }

    public function followers()
    {
        // todo.
    }

    public function followings()
    {
        // todo.
    }

    public function attachFollowingUser()
    {
        // todo.
    }

    public function detachFollowingUser()
    {
        // todo.
    }
}
