<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;

class CurrentUserController extends Controller
{
    /**
     * Created the controller.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(Request $request)
    {
        // 用户认证.
        $this->middleware('auth:api')->except('logout');
    }

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
        ]);

        return response()->json($user, 200);
    }
}
