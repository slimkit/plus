<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Like as LikeModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

class UserLikeController extends Controller
{
    public function index(Request $request, ResponseContract $response, LikeModel $model)
    {
        $limit = $request->query('limit', 15);
        $after = $request->query('after', false);
        $user = $request->user();

        $likes = $model->with('likeable')
            ->where('target_user', $user->id)
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->where('user_id', '!=', $user->id)
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        if ($user->unreadCount !== null) {
            $user->unreadCount()->decrement('unread_likes_count', $user->unreadCount->unread_likes_count);
        }

        return $response->json($likes)->setStatusCode(200);
    }
}
