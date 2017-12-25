<?php

namespace Zhiyi\PlusGroup\Admin\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\PlusGroup\Models\Post as PostModel;
use Zhiyi\PlusGroup\Models\Group as GroupModel;
use Zhiyi\PlusGroup\Models\GroupRecommend as RecommendModel;

class GroupPostController
{
    /**
     * 帖子列表.
     * 
     * @param  Request $request
     * @param  int     $groupId
     * @return mixed
     */
    public function index(Request $request)
    {
        $user = $request->query('user');
        $type = $request->query('type');
        $title = $request->query('title');
        $pinned = $request->query('pinned');
        $groupId = $request->query('group_id');

        $limit = (int) $request->query('limit', 15);
        $offset = (int) $request->query('offset', 0);

        $data = $request->all();

        $query = PostModel::with(['user', 'latestPinned', 'group'])->has('group');

        $query->when($user, function($query) use($user) {
            return $query->whereHas('user', function($query) use($user) {
                return $query->where('name', 'like', sprintf('%%%s%%', $user));
            });
        })
        ->when($title, function($query) use($title) {
            return $query->where('title', 'like', sprintf('%%%s%%', $title));
        })
        ->when(! is_null($pinned), function($query) use ($pinned) {
            return $query->whereHas('latestPinned', function($query) use($pinned) {
                return $query->where('status', $pinned);
            });
        })
        ->when($groupId, function($query) use($groupId) {
            return $query->where('group_id', $groupId);
        })->when($type && $type =='trash', function($query) {
            return $query->onlyTrashed();
        });

        $count = $query->count();
        $items = $query->limit($limit)->offset($offset)->get();

        $posts = $items->map(function($item){
            if ($item->latestPinned && $item->latestPinned->status == 1) {
                $item->latestPinned->expires_state = $item->latestPinned->expires_at > Carbon::now()->toDateTimeString();
            }
            return $item;
        });

        return response()->json($posts, 200, ['x-total' => $count]);
    }

    public function delete(int $postId, int $groupId)
    {
        PostModel::find($groupId)->delete();

        return response()->json(null, 204);
    }

    /**
     * 还原帖子.
     * 
     * @param  int    $id
     * @return mixed
     */
    public function restore(int $id)
    {
        PostModel::withTrashed()->find($id)->restore();

        return response()->json(['message' => '恢复成功'], 201);
    }
}
