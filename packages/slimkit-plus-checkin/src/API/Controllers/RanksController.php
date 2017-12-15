<?php

namespace SlimKit\PlusCheckIn\API\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\UserExtra as UserExtraModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class RanksController extends Controller
{
    /**
     * Get all users check-in ranks.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\UserExtra $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response, UserExtraModel $model)
    {
        $user_id = $request->user('api')->id ?? 0;
        $limit = $request->query('limit', 10);
        $offset = max(0, $request->query('offset', 0));

        $users = $model->with('user')
            ->orderBy('last_checkin_count', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->map(function (UserExtraModel $item) {
                return $item->user;
            })
            ->filter()
            ->map(function (UserModel $user) {
                $user->follwing = false;
                $user->follower = false;

                return $user;
            })
            ->when($user_id, function (Collection $users) use ($user_id) {
                return $users->map(function (UserModel $user) use ($user_id) {
                    $user->follwing = $user->hasFollwing($user_id);
                    $user->follower = $user->hasFollower($user_id);

                    return $user;
                });
            })
            ->values();

        return $response->json($users, 200);
    }
}
