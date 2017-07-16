<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

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
        ]);

        return response()->json($user, 200);
    }

    /**
     * Show user followers.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followers(Request $request, ResponseFactoryContract $response)
    {
        $user = $request->user();
        $limit = $request->query('limit', 20);
        $after = $request->query('after', false);

        $followers = $user->followers()
            ->when($after, function ($query) use ($after, $user) {
                return $query->where($user->getQualifiedKeyName(), '<', $after);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $response->json($followers)->setStatusCode(200);
    }

    /**
     * Show user followings.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followings(Request $request, ResponseFactoryContract $response)
    {
        $user = $request->user();
        $limit = $request->query('limit', 20);
        $after = $request->query('after', false);

        $followings = $user->followings()
            ->when($after, function ($query) use ($after, $user) {
                return $query->where($user->getQualifiedKeyName(), '<', $after);
            })
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        return $response->json($followings)->setStatusCode(200);
    }

    /**
     * Attach a following user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\User $target
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function attachFollowingUser(Request $request, ResponseFactoryContract $response, UserModel $target)
    {
        $user = $request->user();

        if ($user->id === $target->id) {
            return $response->json(['message' => ['不可对自己进行操作']], 422);
        } elseif ($user->hasFollwing($target)) {
            return $response->json(['message' => ['非法的操作']], 422);
        }

        return $user->getConnection()->transaction(function () use ($user, $target, $response) {
            $user->followings()->attach($target);
            $user->extra()->firstOrCreate([])->increment('followings_count', 1);

            return $response->make(null, 204);
        });
    }

    /**
     * detach a following user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\User $target
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function detachFollowingUser(Request $request, ResponseFactoryContract $response, UserModel $target)
    {
        $user = $request->user();

        return $user->getConnection()->transaction(function () use ($user, $target, $response) {
            $user->followings()->detach($target);
            $user->extra()->firstOrCreate([])->decrement('followings_count', 1);

            return $response->make('', 204);
        });
    }
}
