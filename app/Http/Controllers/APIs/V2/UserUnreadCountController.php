<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use DB;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Like as LikeModel;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Support\PinnedsNotificationEventer;

class UserUnreadCountController extends Controller
{
    /**
     * 获取用户未读消息.
     *
     * @param Request $request
     * @param CommentModel $commentModel
     * @param LikeModel $likeModel
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function index(Request $request, CommentModel $commentModel, LikeModel $likeModel, PinnedsNotificationEventer $eventer)
    {
        $user = $request->user();
        $counts = $user->unreadCount;

        // 查询最近几条评论记录
        $comments = $commentModel->select('user_id', DB::raw('max(id) as id, max(created_at) as time'))
            ->where(function ($query) use ($user) {
                return $query->where('target_user', $user->id)
                    ->orWhere('reply_user', $user->id);
            })
            ->where('user_id', '!=', $user->id)
            ->limit(5)
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('id', 'desc')
            ->get();

        // 查询最近几条点赞记录
        $likes = $likeModel->select('user_id', DB::raw('max(id) as id, max(created_at) as time'))
            ->where('target_user', $user->id)
            ->where('user_id', '!=', $user->id)
            ->limit(5)
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('id', 'desc')
            ->get();

        // 未处理审核统计
        $pinneds = $eventer->dispatch()->mapWithKeys(function ($pinnedModels) use ($user) {
            $model = new $pinnedModels['namespace']();

            $pinneds[$pinnedModels['name']] = $model->select(DB::raw('max(created_at) as time, count(*) as count'))->where($pinnedModels['owner_prefix'], $user->id)->where($pinnedModels['audit_prefix'], $pinnedModels['unaudited_value'])->first()->toArray();
            return $pinneds;
        });

        return response()->json([
            'counts' => $counts,
            'comments' => $comments,
            'likes' => $likes,
            'pinneds' => $pinneds,
        ], 200);
    }
}
