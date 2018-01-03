<?php

namespace Zhiyi\PlusGroup\API\Controllers;

use Illuminate\Http\Request;
use Zhiyi\PlusGroup\Models\GroupMember;
use Zhiyi\PlusGroup\Models\Post;

class PostCollectionController
{
    /**
     * 收藏帖子.
     *  
     */
    public function store(Request $request, Post $post, GroupMember $member)
    {
        $user = $request->user();

        if (! $member->isNormal($post->group_id, $user->id)) {
           return response()->json(['message' => '未加入该圈子或已被拉黑'], 403);
        }

        if ($post->collected($user)) {
            return response()->json(['message' => '已经收藏过'], 422);
        }

        $post->collect($user);

        return response()->json(['message' => '收藏成功'], 201);
    }

    /**
     * 取消收藏.
     *
     */
    public function destroy(Request $request, Post $post)
    {
        $post->uncollect(
            $request->user()->id
        );

        return response()->json(null, 204);
    }

    /**
     * 用户收藏的帖子.
     *
     * @param Request $request
     * @param FeedModel $feedModel
     * @param ResponseContract $response
     * @param FeedRepository $repository
     * @return mixed
     */
    public function collections(Request $request, Post $post)
    {
        $user = $request->user();

        $limit = $request->query('limit', 15);
        $offset = $request->query('offset', 0);

        $posts = $post->whereHas('collectors', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['user', 'images'])
        ->limit($limit)
        ->offset($offset)
        ->get();

        $items = $posts->map(function ($post) {
            $images = $post->images;
            unset($post->images);
            $post->images = $images->map(function ($image) {
                return ['id' => $image->id, 'size' => $image->size];
            });
            return $post;
        });

        return response()->json($items, 200);
    }
}
