<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;

class UserCommentController extends Controller
{
    /**
     * Get user comments.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\Comment $model
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseContract $response, CommentModel $model)
    {
        $user = $request->user();
        $limit = $request->query('limit', 20);
        $after = (int) $request->query('after', 0);

        $comments = $model->getConnection()->transaction(function () use ($user, $limit, $after, $model) {
            return $model->with('commentable')
                ->where(function ($query) use ($user) {
                    return $query->where('target_user', $user->id)
                        ->orWhere('reply_user', $user->id);
                })
                // ->where('user_id', '!=', $user->id)
                ->when($after, function ($query) use ($after) {
                    return $query->where('id', '<', $after);
                })
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();
        });

        return $model->getConnection()->transaction(function () use ($comments, $response) {
            return $response->json($comments, 200);
        });
    }
}
